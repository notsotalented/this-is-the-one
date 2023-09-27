<!DOCTYPE html>
<html lang="en-US">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

{{--FONT--}}
<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
{{--BOOSTRAP5--}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
{{--FONTAWSOME--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

{{--JQUERY--}}
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

  <style>
    @include('includes::css.include-css');
</style>

<title>Products</title>

@php
  function convertTimeToAppropriateFormat($time) {
    $suffix = ['sec(s)', 'min(s)', 'hour(s)', 'day(s)', 'week(s)', 'month(s)', 'year(s)', 'dummy'];
    $multi = [60, 60, 24, 7, 4.34, 12, 1111];

    $i = 0;

    while ($time >= $multi[$i] && $i <= 5) {
      $time /= $multi[$i];
      $i++;
    }

    return round($time, 0) . ' ' . $suffix[$i];
  }
@endphp


</head>
<body>
    @include('welcome::nav-bar')

    <div class="container-fluid" style="margin-top: 0vh">
      {{-- Pagination --}}
      <label for="level"><b>{{$products->links()}}</b></label>
      <div class="input-group mb-3" style="max-width: 16vw">
          <span class="input-group-text" style="width: 8vw">Per Page</span>
          <input type="number" class="form-control" name="per_page" style="width: 8vw" placeholder="1" min="1" max="100" value="@if(isset($_GET['paginate'])){{$_GET['paginate']}}@else{{'10'}}@endif" onchange="load_page(this.value)">
      </div>

      {{-- Add product to user button --}}
      @isset(Request()->userId)
        @if(Request()->userId == Auth::user()->id)
        <div class="input-group mb-3" style="max-width: 16vw">
          <a href="{{route('web_product_show_add_form', ['userId' => Auth::user()->id])}}" class="btn btn-primary" type="button">Add Product</a>
        </div>
        @endif
      @endisset
    </div>

    {{-- Item showcase WIP --}}
    <div class="container-fluid">
      <div class="card-deck">
        <div class="row">
          @foreach ($products as $product)
            <div class="col-sm-3">
              <div class="card mb-4 w-23 h-28 border-secondary" style="max-width: 23vw; min-height: 28vw;" onmouseover="lightsUp(this)" onmouseout="lightsDown(this)">

                <div id="carouselImages{{ str_replace(' ', '_', $product->name) }}" class="carousel carousel-dark slide" data-bs-ride="carousel">
                  <div class="carousel-indicators">

                    @foreach ($product->getImages as $key => $photo)
                      <button type="button" data-bs-target="#carouselImages{{ $product->name }}" data-bs-slide-to="{{ $key }}" class="@if($key == 0){{ 'active' }}@endif" aria-current="@if($key == 0){{ 'true' }}@endif" aria-label="Slide {{ $key }}"></button>
                    @endforeach

                  </div>
                  <div class="carousel-inner">

                    @foreach ($product->getImages as $key => $photo)
                      <div class="carousel-item @if($key == 0 ){{ 'active' }} @endif">
                        <img src="/uploads/product_images/{{ $photo->name }}" class="card-img-top border border-bottom" alt="{{ $photo->name }}" style="max-width: 23vw; max-height: 23vw; padding:1vw;">
                      </div>
                    @endforeach

                  </div>

                  @if(count($product->getImages) > 1)
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages{{ str_replace(' ', '_', $product->name) }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselImages{{ str_replace(' ', '_', $product->name) }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                  @endif
                </div>

                <div class="card-body" style="overflow: auto; position: relative">
                  <h5 class="card-title">{{$product->name}}</h5>
                  <p class="card-text">{{ $product->description }}</p>

                  @if(Auth::user()->id == $product->getOwner->id)
                    <a href="{{ route('web_product_show_specific_personal', ['userId' => Auth::user()->id,'id' => $product->id]) }}" class="stretched-link"></a>
                  @else
                    <a href="{{ route('web_product_get_specific_product', ['id' => $product->id]) }}" class="stretched-link"></a>
                  @endif

                </div>
                <div class="card-footer">
                  <small class="text-muted">Last updated: {{ convertTimeToAppropriateFormat(time()-strtotime($product->updated_at)) }} ago</small>
                  <br>
                  <small class="text-muted">Owner: @can('search-users')<a class="link-underline link-underline-opacity-0" href="{{ route('user-profile', ['id' => $product->getOwner->id]) }}">@endcan{{ $product->getOwner->name }}@can('search-users')</a>@endcan</small>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

</body>

<script>
    function load_page(per_page) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set("paginate", per_page);
        window.location.search = searchParams.toString();
    }

    function lightsUp(element) {
      element.style.border = "solid 2px";
      element.classList.remove("border-secondary");
      element.classList.add("border-primary");
    }

    function lightsDown(element) {
      element.style.border = "solid 1px";
      element.classList.remove("border-primary");
      element.classList.add("border-secondary");
    }
</script>

</html>
