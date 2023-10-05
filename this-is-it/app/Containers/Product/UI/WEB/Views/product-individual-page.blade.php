<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    {{-- BOOSTRAP5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    {{-- FONTAWSOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <style>
        @include('includes::css.include-css');
    </style>

    <title>Products</title>

</head>

<body>
    @include('welcome::nav-bar')


    {{-- <form id="your-form-id" method="POST"
        action="{{ route('web_product_add_to_user', ['userId' => Auth::user()->id]) }}" enctype="multipart/form-data"> --}}
    <div class="container" style="margin-top: 5vh;">

        <div class="row">
            <div class="col-xl-4">
                {{-- Product picture card --}}
                <div class="card mb-4 mb-xl-0" style=" max-height: 80vh;">
                    <div class="card-header">
                        <h5>Product Picture(s)<h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="container-fluid" style="overflow: auto; max-height: 50vh; max-width: 40vh">
                            <div class="gallery">
                                <div id="carouselImages{{ str_replace(' ', '_', $product->name) }}"
                                    class="carousel carousel-dark slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">

                                        @foreach ($product->getImages as $key => $photo)
                                            <button type="button" data-bs-target="#carouselImages{{ $product->name }}"
                                                data-bs-slide-to="{{ $key }}"
                                                class="@if ($key == 0) {{ 'active' }} @endif"
                                                aria-current="@if ($key == 0) {{ 'true' }} @endif"
                                                aria-label="Slide {{ $key }}"></button>
                                        @endforeach

                                    </div>
                                    <div class="carousel-inner">

                                        @foreach ($product->getImages as $key => $photo)
                                            <div
                                                class="carousel-item @if ($key == 0) {{ 'active' }} @endif">
                                                <img src="/storage/uploads/product_images/{{ $photo->name }}"
                                                    class="card-img-top border border-bottom" alt="{{ $photo->name }}"
                                                    style="max-width: 23vw; max-height: 23vw; padding:1vw;">
                                            </div>
                                        @endforeach

                                    </div>

                                    @if (count($product->getImages) > 1)
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselImages{{ str_replace(' ', '_', $product->name) }}"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselImages{{ str_replace(' ', '_', $product->name) }}"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <input type="file" id="gallery-photo-add" accept="image/*" name="image[]"
                            class="form-control" multiple required oninput="checkNumFiles(this)">
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                {{-- Account details card --}}
                <div class="card mb-4 mb-xl-0" style="max-height: 80vh; overflow:auto">
                    <div class="card-header">
                        <h5>Product Information</h5>
                    </div>
                    <div class="card-body">
                        {{-- Form Group (Name) --}}
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="width: 6vw">Name</span>
                            <input id="name" name="name" type="text" class="form-control"
                                aria-label="cost-labour" aria-describedby="cost-labour" placeholder="Text [5:255]"
                                value="{{ $product->name }}" required minlength="5" maxlength="255" readonly>
                        </div>
                        {{-- Form Group (Description) --}}
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="width: 10vw">Description</span>
                            <textarea class="form-control" aria-label="With textarea" id="description" name="description" rows="3"
                                placeholder="Text [5:255]" required minlength="5" maxlength="255" readonly>{{ $product->description }}</textarea>
                        </div>
                        {{-- Form Group (Brand) --}}
                        @php
                            $brands = ['Tamiya ARC', 'HobbyBoss ARC', 'Tamiya LQR', 'HobbyBoss LQR', '私は黒狐です。', 'Sasebo', 'Kure', 'Mitsubishi'];
                        @endphp

                        <div class="input-group mb-3">
                            <div class="btn-group" role="group" aria-label="Toolbar with button groups"
                                data-toggle="buttons">
                                <span class="input-group-text" style="width: 6vw">Brand</span>
                                <div id="role_table" class="btn-group" role="group"
                                    aria-label="Basic checkbox toggle button group">
                                    @foreach ($brands ?? [] as $brand)
                                        @if ($brand == $product->brand)
                                            <input type="radio" class="btn-check" name="brand"
                                                id="brand_{{ $loop->iteration }}" value="{{ $brand }}"
                                                required checked>
                                            <label class="btn btn-outline-dark"
                                                for="brand_{{ $loop->iteration }}">{{ $brand }}</label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- New Column --}}
                        <div class="row gx-3 mb-3">
                            {{-- Form Group (Quantity) --}}
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="width: 6vw">In Stock</span>
                                    <input id="quantity" name="quantity" type="number" class="form-control"
                                        aria-label="quantity" aria-describedby="quantity" placeholder="[0:100]"
                                        value="{{ old('quantity') }}" min="0" max="100" required>
                                </div>
                            </div>

                            {{-- Form Group (Price) --}}
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="width: 6vw">Price</span>
                                    <input id="price" name="price" type="number" class="form-control"
                                        aria-label="price" aria-describedby="price" placeholder="0"
                                        value="{{ old('cost') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        @if (Auth::check())
                            @if (Auth::user()->id == $product->user_id)
                                <button class="btn btn-primary" type="submit">Add Product</button>
                            @endif

                            <div class="btn-group" role="group" aria-label="Toolbar with back to buttons"
                                style="float: right;">
                                <a href="
                              @if (Auth::user()->id == $product->user_id) {{ route('user-profile', ['id' => Auth()->user()->id]) }}
                              @else{{ route('user-profile', ['id' => $product->user_id]) }} @endif
                              "
                                    class="btn btn-outline-danger" type="button"
                                    style="white-space: nowrap; float: right; margin-left: 0.5vw">
                                    @if (Auth::user()->id == $product->user_id)
                                        {{ 'My' }}@else{{ 'To' }}
                                    @endif Profile
                                </a>
                                <a href="
                                        @if (Auth::user()->id == $product->user_id) {{ route('web_product_show_all_personal', ['userId' => Auth::user()->id]) }}
                                        @else
                                        {{ route('web_product_show_all_personal', ['userId' => $product->user_id]) }} @endif"
                                    class="btn btn-outline-info" type="button"
                                    style="white-space: nowrap; float: right">
                                    @if (Auth::user()->id == $product->user_id)
                                        {{ 'My' }}@else{{ 'Their' }}
                                    @endif Product
                                </a>
                            </div>
                        @else
                            <a href="{{ route('web_product_show_all_personal', ['userId' => $product->user_id]) }}"
                                class="btn btn-outline-info" type="button"
                                style="white-space: nowrap; float: right">Their Product</a>

                        @endif
                    </div>
                </div>

                {{-- CSRF AND HIDDEN STUFF --}}
                @csrf
                <input id="deletedImages" type="hidden" name="deleted[]">

            </div>

        </div>

        </form>


</body>

</html>
