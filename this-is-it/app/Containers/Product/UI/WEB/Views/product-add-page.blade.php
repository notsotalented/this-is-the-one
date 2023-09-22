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

</head>
<body>
    @include('welcome::nav-bar')

        
    <script type="text/javascript">
      function update_cost() {
        var cost_base = document.getElementById("cost_base").value;

        var cost_labour = document.getElementById("cost_labour").value;

        var cost = parseInt(cost_base ? cost_base : 0) + parseInt(cost_labour ? cost_labour : 0);

        document.getElementById("cost").value = cost;
      }

      $(function() {
        // Multiple images preview in browser
        var imagesPreview = function(input, placeToInsertImagePreview) {

            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }

        };

        $('#gallery-photo-add').on('change', function() {
            imagesPreview(this, 'div.gallery');
        });
      });
    </script>

    <form method="POST" action="{{ route('web_product_add_to_user', ['userId' => Auth::user()->id]) }}" oninput="">
      <div class="container" style="margin-top: 5vh">

        <div class="row">
          <div class="col-xl-4">
            {{-- Product picture card--}}
            <div class="card mb-4 mb-xl-0">
                <div class="card-header"><h5>Product Picture(s)<h5></div>
                <div class="card-body text-center">
                  <p>View Port</p>
                  <div class="container-fluid" style="overflow: auto; max-height: 40vh; max-width: 40vh">
                      <div class="gallery">
                      </div>
                  </div>
                </div>
                <div class="card-footer text-center">
                  <input type="file" id="gallery-photo-add" accept="image/*" name="image[]" class="form-control" multiple required">
                </div>
            </div>

            <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
                <div class="card-header"><h5>Price<h5></div>
                <div class="card-body text-center">
                  <div class="container-fluid">
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="width: 6vw">Cost</span>
                        <input id="cost_base" name="cost_base" type="number" class="form-control" aria-label="cost-base" aria-describedby="cost-base" placeholder="0" value="{{ old('cost-base') }}" onchange="update_cost()">
                        <span class="input-group-text" style="width: 5vw">VNĐ</span>
                    </div>

                    <div class="input-group mb-3">
                      <span class="input-group-text" style="width: 6vw">Labour</span>
                      <input id="cost_labour" name="cost_labour" type="number" class="form-control" aria-label="cost-labour" aria-describedby="cost-labour" placeholder="0" value="{{ old('cost-labour') }}" onchange="update_cost()">
                      <span class="input-group-text" style="width: 5vw">VNĐ</span>
                    </div>

                    <div class="input-group mb-3">
                      <span class="input-group-text" style="width: 6vw">Total</span>
                      <input id="cost" name="price" type="number" class="form-control" aria-label="cost" aria-describedby="cost" placeholder="0" value="{{ old('price') }}" readonly>
                      <span class="input-group-text" style="width: 5vw">VNĐ</span>
                    </div>
                  </div>
                </div>
            </div>
          </div>
            

          <div class="col-xl-8">
            {{-- Account details card--}}
            <div class="card mb-4">
                <div class="card-header"><h5>Product Information</h5></div>
                <div class="card-body">
                    {{-- Form Group (Name)--}}
                    <div class="input-group mb-3">
                      <span class="input-group-text" style="width: 6vw">Name</span>
                      <input id="name" name="name" type="text" class="form-control" aria-label="cost-labour" aria-describedby="cost-labour" placeholder="Text [5:255]" value="{{ old('name') }}" required minlength="5" maxlength="255">
                    </div>
                    {{-- Form Group (Description) --}}
                    <div class="input-group mb-3">
                      <span class="input-group-text" style="width: 10vw">Description</span>
                      <textarea class="form-control" aria-label="With textarea" id="description" name="description" rows="3" placeholder="Text [5:255]" required minlength="5" maxlength="255">{{ old('description') }}</textarea>
                    </div>
                    {{-- Form Group (Brand) --}}
                    @php
                      $brands = [
                        'Tamiya ARC',
                        'HobbyBoss ARC',
                        'Tamiya LQR',
                        'HobbyBoss LQR',
                        '私は黒狐です。',
                      ];
                    @endphp

                    <div class="input-group mb-3">
                      <div class="btn-group" role="group" aria-label="Toolbar with button groups" data-toggle="buttons">
                        <span class="input-group-text" style="width: 6vw">Brand</span>
                        <div id="role_table" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                          @foreach ($brands ?? [] as $brand)
                            <input type="radio" class="btn-check" name="brand" id="brand_{{ $loop->iteration }}" value="{{ $brand }}" required>
                            <label class="btn btn-outline-dark" for="brand_{{ $loop->iteration }}">{{ $brand }}</label>
                          @endforeach
                        </div>
                      </div>
                    </div>
                    {{-- Form Group (Quantity) --}}
                    <div class="input-group mb-3">
                      <span class="input-group-text" style="width: 6vw">In Stock</span>
                      <input id="quantity" name="quantity" type="number" class="form-control" aria-label="quantity" aria-describedby="quantity" placeholder="[0:100]" value="{{ old('quantity') }}" min="0" max="100" required>
                    </div>
                </div>
                  
                <div class="card-footer">
                  <button class="btn btn-primary" type="submit">Add Product</button>
                  <a href="{{ route('web_product_get_all_products', ['id' => Auth()->user()->id]) }}" class="btn btn-outline-warning" type="button" style="white-space: nowrap; float: right">Back To My Product</a>
                  <a href="{{ route('user-profile', ['id' => Auth()->user()->id]) }}" class="btn btn-outline-danger" type="button" style="white-space: nowrap; float: right">Back To Profile</a>
                </div>
            </div>

            @csrf

          </div>

        </div>

    </form>


</body>

</html>