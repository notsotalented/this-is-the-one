<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  @if(!auth()->check('manage-roles')) <meta http-equiv="refresh" content="0; URL=http://apiato.test/register" /> @endif

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
    html, body {
        background-color: lightsteelblue;
        color: midnightblue;
        font-family: 'Raleway', sans-serif;
        font-weight: 400;
        height: 100vh;
        margin: 0;
        scrollbar-gutter: 'stable', 'both-edges';
    }
  
    form {border: 0px solid #f1f1f1;
    }
  
    .container input[type=text], .container input[type=password] {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
  
    .container button {
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
    }
  
    .container .cancelbtn {
      width: 20%;
      padding: 10px 18px;
      background-color: #f44336;
    }
  
    .container {
      padding: 20px;
      margin-top: 0px;
    }
  
    span.psw {
      float: right;
      padding-top: 10x;
      text-decoration: none;
    }
  
    #back {
      padding: 2px;
      padding-left: 9px;
      width: 86%;
    }
  
    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
      span.psw {
        display: block;
        float: none;
      }
      .cancelbtn {
        width: 100%;
      }
    }
  
    .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
    }
  
    .position-ref {
              position: relative;
          }
  
    .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
    }
  
    .links > a {
                color: midnightblue;
                padding: 0 25px;
                font-size: 14px;
                font-weight: 800;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
    }
  
    .text-red {
                  color: darkred;
                  margin-bottom: 10px;
    }
  </style>

<title>Add Power User</title>

</head>
<body>

  @include('welcome::nav-bar')
  <div class="container" style="margin-top: 0vh">
  
    <form action="/register-power" method="POST" oninput='password_r.setCustomValidity(password.value != password_r.value ? "Passwords do not match." : "")'>
        {{--Standard info--}}
        <label for="email"><b>Email address<i class="fa-regular fa-asterisk fa-2xs" style="color: #ff0000;"></i></b></label>
        <input type="text" class="form-control" placeholder="jamesbond007@sis.gov.uk" name="email" value="{{old('email')}}" required>
  
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

      <label for="role"><b>Roles:</b></label> <br>
        {{--ROLE TABLE--}}
        <div id="role_table" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
          @foreach ($roles as $item)
            <input type="checkbox" class="btn-check" name="role_id[]" id="check{{$item->id}}" onclick="if(this.checked){RadioLocation(<?php echo $item->id ?>);}" value="{{$item->id}}">
            <label class="btn btn-outline-dark" for="check{{$item->id}}">{{$item->display_name}}</label>
          @endforeach
        </div>

          {{--Hidden stuff--}}
          @csrf
          
          <br>
          <button class="btn btn-warning" type="submit[]">Register</button>
    </form>
  </div>

    {{--
      @dump(session()->all())
    --}}
  </div>

</body>

</html>