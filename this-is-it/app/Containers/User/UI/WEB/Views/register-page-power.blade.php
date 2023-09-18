<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

<title>Register Power User</title>

</head>

<body>

  @include('welcome::nav-bar')

  <div class="container" style="margin-top: 5vh">
  
    <form action="/register-power" method="POST" oninput='password_r.setCustomValidity(password.value != password_r.value ? "Passwords do not match." : "")'>
      <div class="col-xl-8">
        {{-- Account details card--}}
          <div class="card mb-4">
            <div class="card-header"><h5>Register Power User</h5></div>
              <div class="card-body">
                {{--Standard info--}}
                <label for="email"><b>Email address<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
                {{-- MESSING WITH INPUT --}}
                <input type="text" class="form-control" placeholder="Mail admin" name="email" value="{{old('email')}}" required>
          
                <label for="password"><b>Password<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
                <input type="password" class="form-control" placeholder="Length [5:30]" name="password" required>
          
                <label for="password_r"><b>Repeat password<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
                <input type="password" class="form-control" placeholder="Please confirm your password" name="password_r" required>
          
                
                {{--Custom info--}}
                <label for="name"><b>Name<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
                <input type="text" class="form-control" placeholder="Length [2:50]" name="name" value="{{old('name')}}">

                <div class="row">
                  <div class="col-md">
                    <label for="gender"><b>Select gender</b></label>
                    <select class="form-select" name="gender">
                      <option value="" name="gender">Excercise the right to remain silent</option>
                      <option value="Male" name="gender">Male</option>
                      <option value="Female" name="gender">Female</option>
                      <option value="Non-binary" name="gender">Non-binary</option>
                    </select>
                  </div>

                  <div class="col-md">
                    <label for="birth"><b>Select your date of birth<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i><b></label>
                    <input class="form-control" type="date" id="birth" name="birth" required value="{{old('birth')}}">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md">
                    <label for="role_table"><b>Assign Roles</b></label><br>
                    <div id="role_table" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                      @foreach ($roles as $item)
                      <input type="checkbox" class="btn-check" name="roles_ids[]" id="check{{$item->id}}" value="{{$item->id}}">
                      <label class="btn btn-outline-dark btn-sm" for="check{{$item->id}}">{{$item->name}}</label>
                    @endforeach
                    </div>
                  </div>

                  <div class="col-md">
                    <br><button class="btn btn-warning" type="submit" style="float:right">Register</button>
                  </div>
              </div>
            </div>
                  {{--Hidden stuff--}}
                  @csrf
              </div>
            </div>
          </div>
        </div>
    </form>

    {{--
      @dump(session()->all())
    --}}
  </div>

</body>

</html>