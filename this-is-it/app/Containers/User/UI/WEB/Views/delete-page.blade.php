<!DOCTYPE html>
<html lang="en-US">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

@if(!auth()->check()) <meta http-equiv="refresh" content="0; URL=http://apiato.test/list-page" /> @endif

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

<title>Confirmation Delete</title>

</head>
<body>

  @include('welcome::nav-bar')

  <form method="POST" action = "/delete-page">
    <div class="container">
      {{--Target aquisition--}}
      <p>
        <label for="targetid" style="color: orangered"><strong>Target user:</strong></label>
        <b>@isset($id){{$id}}@endisset<b>
        </p>
        <label for="targetname" style="color: orangered"><strong>Name:</strong></label>
        <b>@isset($name){{$name}}@endisset<b>
      </p>

      {{--Data passing through form--}}
      {{--Pass the method--}}
      <input type="hidden" name="_METHOD" value="DELETE"/>

      <label for="role"><b>Please confirm delete</b></label>

      @csrf

      <input type="hidden" name="id" value="@isset($id){{$id}}@endisset">

      <br>
      <div>
          <button class="btn btn-danger" type="submit">Confirm Delete</button>
      </div>
  </form>
  {{--
    @dump(session()->all())
  --}}

</div>

</body>
</html>