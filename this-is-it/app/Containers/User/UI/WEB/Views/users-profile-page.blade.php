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

<div class="container-fluid" style="margin-top: 0vh">
    <label for="level"><b>{{$users->links()}}</b></label>
        <div class="input-group mb-3" style="max-width: 15vw">
            <span class="input-group-text" style="width: 8vw">Per Page</span>
            <input type="number" class="form-control" name="per_page" style="width: 7vw" placeholder="1" min="1" max="100" value="@if(isset($_GET['paginate'])){{$_GET['paginate']}}@else{{'10'}}@endif" onchange="load_page(this.value)">
        </div>
</div>

<div class="accordion accordion-flush" id="accordionExample" style="margin-top:5vh">

@foreach ($users as $user)

<div class="accordion-item">
    <h2 class="accordion-header" id="flush-heading{{$user->id}}">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{$user->id}}" aria-expanded="false" aria-controls="flush-collapse{{$user->id}}" aria-pressed="false">
            <b>{{$user->name}}</b>
        </button>
    </h2>

    <div id="flush-collapse{{$user->id}}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{$user->id}}" data-bs-parent="#accordionExample">
    
        <div class="accordion-body">

        <div class="container" style="margin-top: 5vh">
            <div class="row">
                <div class="col-xl-4">
                    {{-- Profile picture card--}}
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header"><h5>Profile Picture<h5></div>
                        <div class="card-body text-center">
                        {{-- Profile picture image--}}

                        @if($user->social_avatar)
                        <img class="img-account-profile rounded-circle mb-2" style="max-width: 20vh; max-height:20vh; overflow: hidden" src="{{ asset('uploads/photos/'.$user->social_avatar) }}" alt="{{$user->name}} profile picture">
                        @endif 
                        {{-- Profile picture help block--}}
                        
                        {{-- Profile picture upload button--}}
                        </div>
                    </div>

                    <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
                        <div class="card-header"><h5>Role<h5></div>
                        <div class="card-body text-center">
                            <div id="role_table{{$user->id}}" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                            @foreach ($user->roles as $item)
                                <button class="btn btn-outline-dark btn-sm" type="button" style="white-space: nowrap"><b>{{$item->name}}</b></button>
                            @endforeach

                            @if(!$user->roles->first())
                                <button class="btn btn-outline-dark btn-sm" type="button" style="white-space: nowrap"><b>guest</b></button>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    {{-- Account details card--}}
                    <div class="card mb-4">
                        <div class="card-header"><h5>Account Details</h5></div>
                        <div class="card-body">
                                {{-- Form Group (Email)--}}
                                <div class="mb-3">
                                    <label class="small mb-1" for="email{{$user->id}}"><b>Email address</b></label>
                                    <input class="form-control" id="email{{$user->id}}" type="text" value="{{$user->email}}" readonly>
                                </div>
                                {{-- Form Row--}}
                                <div class="row gx-3 mb-3">
                                    {{-- Form Group (Name)--}}
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="name{{$user->id}}"><b>Name</b></label>
                                        <div class="input-group-prepend">
                                            <input class="form-control" id="name{{$user->id}}" type="text" value="{{$user->name}}" readonly>
                                        </div>
                                    </div>
                                    {{-- Form Group (ID)--}}
                                    <div class="col-md-6">
                                        <label for="id{{$user->id}}"><b>ID</b></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" style="width: 10vw">/users/</span>
                                            <input id="id{{$user->id}}" name="id" type="text" class="form-control" style="width: 10vw" aria-label="id{{$user->id}}" aria-describedby="id{{$user->id}}" value="{{$user->id}}" readonly>
                                        </div>
                                    </div>
                                    {{-- Form Group (Gender)--}}
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="gender{{$user->id}}"><b>Gender</b></label>
                                        <input class="form-control" id="gender{{$user->id}}" type="text" value="{{$user->gender}}" readonly>
                                    </div>

                                    {{-- Form Group (Birthday)--}}
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="birth{{$user->id}}"><b>Birthday</b></label>
                                        <input class="form-control" id="birth{{$user->id}}" type="text" name="birthday" value="{{date("d-m-Y", strtotime($user->birth))}}" readonly>
                                    </div>
                                </div>
                                {{-- Form Row--}}
                                <div class="row gx-3 mb-3">
                                    {{-- Form Group (Created_at)--}}
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="created_at"><b>Created at</b></label>
                                        <input class="form-control" id="created_at" type="text" value="{{date("d-m-Y \\\n \\\n H:i:s", strtotime($user->created_at))}}" readonly>
                                    </div>
                                    {{-- Form Group (Updated_at)--}}
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="updated_at"><b>Last updated:</b></label>
                                        <input class="form-control" id="updated_at" type="text" value="{{date("d-m-Y \\\n \\\n H:i:s", strtotime($user->updated_at))}}" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <br><a href="{{route('user-profile', ['id' => $user->id])}}" class="btn btn-outline-info" type="button">VISIT</a>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endforeach

</div>  
</body>

<script>
    function load_page(per_page) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set("paginate", per_page);
        window.location.search = searchParams.toString();
    }
</script>

</html>