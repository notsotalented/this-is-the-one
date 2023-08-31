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

<title>Users' Profile</title>

</head>

<body>
    @include('welcome::nav-bar')

    <div class="container-fluid" style="margin-top: 5vh">
        @if(auth()->check())
            <div class="title m-b-md"><h1>Some how I messed up. Logged in as {{ auth()->user()->name }}</h1></div>
        @else
            <div class="title m-b-md"><h1>Some how I messed up</h1></div>
        @endif

        <div class="links">
            <a href="http://apiato.test/api/documentation/">API DOC</a>
            <a href="http://apiato.test/api/private/documentation/">API DOC-P</a>
            <a href="https://time.is" id="clock"></a>
        </div>
    </div>

</body>

<script>
    $(document).ready(function() {

        //function to display time in current time zone (PST) 
        function timeDisplay() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();
        var meridiem = " ";

        if(hours >= 12){
        hours = hours - 12;
        meridiem = "pm";
        }
        else{
        meridiem = "am";
        }
        if(hours === 0){
        hours = 12;
        }
        if(hours < 10){
        hours = "0" + hours;
        }
        if(minutes < 10){
        minutes = "0" + minutes;
        }
        if(seconds < 10){
        seconds = "0" + seconds;
        }

        var clockDiv = document.getElementById('clock');
            clockDiv.innerText = hours + ":" + minutes + ":" + seconds + " " + meridiem;
        }
        
    timeDisplay();
    setInterval(timeDisplay, 1000);
    });

</script>

</html>