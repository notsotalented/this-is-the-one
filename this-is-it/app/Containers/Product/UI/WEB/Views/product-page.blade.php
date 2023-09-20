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

    <div class="container-fluid" style="margin-top: 0vh">
        <label for="level"><b>{{$products->links()}}</b></label>
            <div class="input-group mb-3" style="max-width: 16vw">
                <span class="input-group-text" style="width: 8vw">Per Page</span>
                <input type="number" class="form-control" name="per_page" style="width: 8vw" placeholder="1" min="1" max="100" value="@if(isset($_GET['paginate'])){{$_GET['paginate']}}@else{{'10'}}@endif" onchange="load_page(this.value)">
            </div>
    </div>

    {{ 'WAZZAP' }}
    
</body>

<script>
    function load_page(per_page) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set("paginate", per_page);
        window.location.search = searchParams.toString();
    }
</script>

</html>