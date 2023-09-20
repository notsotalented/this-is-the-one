<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

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

</head>
<body>

  @include('welcome::nav-bar')

  <div class="container" style="margin-top: 5vh">
    <form method = "POST" action = "{{ route('post_login_form') }}" oninput = 'password.setCustomValidity(password.length < 5 || password.length > 30  ? "Passwords must be between 5 and 30 characters." : "")'>
    <div class="col-xl-8">
    {{-- Account details card--}}
      <div class="card mb-4">
        <div class="card-header"><h5>Login</h5></div>
          <div class="card-body">

            <label for="email"><b>Email address<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
            {{-- MESSING WITH INPUT --}}
            <input type="text" class="form-control" placeholder="Mail admin" name="email" value="{{ old('email') }}" required>

            <label for="password"><b>Password<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
            <input type="password" class="form-control" placeholder="Should be admin" name="password" value="{{ old('password') }}"required>

            @csrf

            <div style="width: 55vw">
              <input type="checkbox" name="remember" value="true">
              <label for="remember"> Remember me (not yet) </label>
              <span style="float: right" class="psw">(Not yet) Forgot <a style="color: red; text-decoration-line: none;" data-toggle="modal" data-target="#forgotModal" href="password/forgot">password?</a></span>
            </div>
            <button class="btn btn-success btn-block" type="submit">Login</button>

          </div>
        </div>
      </div>
    </div>

    </form>

    <!-- Modal -->
    <div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>

</html>