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
    </script>

    <form method="POST" action="{{ route('web_product_add_to_user', ['userId' => Auth::user()->id]) }}">
      <div class="container" style="margin-top: 5vh">

        <div class="row">
          <div class="col-xl-4">
            {{-- Product picture card--}}
            <div class="card mb-4 mb-xl-0">
                <div class="card-header"><h5>Product Picture(s)<h5></div>
                <div class="card-body text-center">
                  {{-- Product picture image--}}

                  {{-- Product picture help block--}}
                  
                  {{-- Product picture upload button--}}
                  <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload new image</button>
                </div>
            </div>

            <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
              <div class="card-header"><h5>Quantity<h5></div>
              <div class="card-body text-center">
                <div class="input-group mb-3">
                    <span class="input-group-text" style="width: 6vw">In Stock</span>
                    <input id="quantity" type="number" class="form-control" aria-label="quantity" aria-describedby="quantity" placeholder="[0:100]" value="{{ old('quantity') }}" min="0" max="100" required>
                </div>
              </div>
            </div>

            <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
              <div class="card-header"><h5>Price<h5></div>
              <div class="card-body text-center">
                <div class="input-group mb-3">
                    <span class="input-group-text" style="width: 6vw">Cost</span>
                    <input id="cost_base" type="number" class="form-control" aria-label="cost-base" aria-describedby="cost-base" placeholder="0" value="{{ old('cost-base') }}" onchange="update_cost()">
                    <span class="input-group-text" style="width: 5vw">VNĐ</span>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" style="width: 6vw">Labour</span>
                  <input id="cost_labour" type="number" class="form-control" aria-label="cost-labour" aria-describedby="cost-labour" placeholder="0" value="{{ old('cost-labour') }}" onchange="update_cost()">
                  <span class="input-group-text" style="width: 5vw">VNĐ</span>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" style="width: 6vw">Total</span>
                  <input id="cost" name="cost" type="number" class="form-control" aria-label="cost" aria-describedby="cost" placeholder="0" readonly>
                  <span class="input-group-text" style="width: 5vw">VNĐ</span>
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
                      <input id="name" name="name" type="text" class="form-control" aria-label="cost-labour" aria-describedby="cost-labour" placeholder="Text [5:255]" value="{{ old('name') }}" required>
                    </div>
                    {{-- Form Group (Description) --}}
                    <div class="input-group mb-3">
                      <span class="input-group-text" style="width: 10vw">Description</span>
                      <textarea class="form-control" aria-label="With textarea" id="description" name="description" rows="3" placeholder="Text [5:255]">{{ old('description') }}</textarea>
                    </div>
                    {{-- Form Group (Brand) --}}
                    <div class="input-group mb-3">
                      <div class="btn-group" role="group" aria-label="Toolbar with button groups" data-toggle="buttons">
                        <span class="input-group-text" style="width: 6vw">Brand</span>
                        <div id="role_table" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                          <input type="radio" class="btn-check" name="brand" id="brand_1" value="Tamiya ARC" required>
                          <label class="btn btn-outline-dark" for="brand_1">Tamiya ARC</label>
                          <input type="radio" class="btn-check" name="brand" id="brand_2" value="HobbyBoss ARC" required>
                          <label class="btn btn-outline-dark" for="brand_2">HobbyBoss ARC</label>
                          <input type="radio" class="btn-check" name="brand" id="brand_3" value="Tamiya LQR" >
                          <label class="btn btn-outline-dark" for="brand_3">Tamiya LQR</label>
                          <input type="radio" class="btn-check" name="brand" id="brand_4" value="HobbyBoss LQR">
                          <label class="btn btn-outline-dark" for="brand_4">HobbyBoss LQR</label>
                        </div>
                      </div>
                    </div>
                </div>
            </div>

            @csrf

            <button class="btn btn-primary" type="submit">Add Product</button>
            <a href="{{ route('user-profile', ['id' => Auth()->user()->id]) }}" class="btn btn-danger" type="button" style="white-space: nowrap; float: right">Back To Profile</a>

          </div>

        </div>

    </form>

  {{-- Modal Product Pictures --}}
  <div class="modal fade" id="uploadModal" tabindex="1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                    <p class="modal-title" id="uploadModalLabel"><b>Upload photo</b></p>
                    <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('profile-picture-upload', ['id' => request('userId')])}}" enctype="multipart/form-data">
                    <label for="photo" class="form-label">Default file input example</label>
                    <input class="form-control" type="file" id="photo" name="photo" accept="image/*">

                    {{--HIDDEN INPUT--}}
                    @csrf
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit" style="white-space: nowrap"><b>Upload <i class="fa-regular fa-image fa-2xs"></i></b></button>
                </form>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
            </div>
        </div>
    </div>
  </div>

</body>

</html>