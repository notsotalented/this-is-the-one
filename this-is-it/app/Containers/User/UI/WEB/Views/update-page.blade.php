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
    @include('includes::css.include-css');
</style>


<title>Update User</title>

</head>
<body>

  @include('welcome::nav-bar')

    <form method="POST" action="/users/{{$id}}/@can('manage-roles'){{'power-'}}@endcan{{'update'}}" oninput='password_new2.setCustomValidity(password_new.value != password_new2.value ? "Passwords do not match." : "")'>
        <div class="container" style="margin-top: 5vh">

          <div class="row">
              <div class="col-xl-4">
                  {{-- Profile picture card--}}
                  <div class="card mb-4 mb-xl-0">
                      <div class="card-header"><h5>Profile Picture<h5></div>
                      <div class="card-body text-center">
                        {{-- Profile picture image--}}

                        @if($user->social_avatar)
                        <img class="img-account-profile rounded-circle mb-2" style="max-width: 20vh; max-height:20vh; overflow: auto" src="{{ asset('/storage/uploads/photos/'.$user->social_avatar) }}" alt="{{$user->name}} profile picture">
                        @endif
                        {{-- Profile picture help block--}}

                        {{-- Profile picture upload button--}}
                        @if(auth()->id() == $user->id)
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload new image</button>
                        @endif
                      </div>
                  </div>

                  @can('manage-roles')
                  <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
                      <div class="card-header"><h5>Role<h5></div>
                      <div class="card-body text-center">
                          <div id="role_table" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                            @foreach ($roles as $item)
                            <input type="checkbox" class="btn-check" name="roles_ids[]" id="check{{$item->id}}" value="{{$item->id}}"
                            @foreach ($assigned as $item_2)
                                @if($item->id == $item_2->id)
                                  checked
                                  @break
                                @endif
                            @endforeach
                            >
                            <label class="btn btn-outline-dark btn-sm" for="check{{$item->id}}">{{$item->name}}</label>
                          @endforeach
                          </div>
                      </div>
                  </div>

                  <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
                      <div class="card-header"><h5>Permissions<h5></div>
                      <div class="card-body text-center">
                          @foreach ($user->getAllPermissions() as $item)
                              <button class="btn btn-outline-success btn-sm" type="button" style="width: 20vw"><b>{{$item->name}}</b></button>
                          @endforeach
                      </div>
                  </div>
                  @endcan

              </div>

              <div class="col-xl-8">
                  {{-- Account details card--}}
                  <div class="card mb-4">
                      <div class="card-header"><h5>Update Information</h5></div>
                      <div class="card-body">
                              {{-- Form Group (Email)--}}
                              <div class="mb-3">
                                  <label class="small mb-1" for="email"><b>Email address</b></label>
                                  <input class="form-control" name="email" id="email" type="text" placeholder="{{$user->email}}" value="{{old('email')}}" >
                              </div>
                              {{-- Form Row--}}
                              <div class="row gx-3 mb-3">
                                  {{-- Form Group (Name)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="name"><b>Name</b></label>
                                      <div class="input-group-prepend">
                                      <input class="form-control" name="name" id="name" type="text" placeholder="{{$user->name}}" value="{{old('name')}}">
                                      </div>
                                  </div>
                                  {{-- Form Group (ID)--}}
                                  <div class="col-md-6">
                                      <label for="id{{$user->id}}"><b>ID</b></label>
                                      <div class="input-group mb-3">
                                          <span class="input-group-text" style="width: 10vw">/users/</span>
                                          <input id="id{{$user->id}}" name="id" type="text" class="form-control" aria-label="id{{$user->id}}" aria-describedby="id{{$user->id}}" value="{{$user->id}}" readonly>
                                      </div>
                                  </div>
                                  {{-- Form Group (Gender)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="gender"><b>Gender</b></label>
                                      <select class="form-select" name="gender">
                                        <option id="NULL" value="0" name="gender">Excercise the right to remain silent</option>
                                        <option id="Male" name="gender" @if($user->gender == "Male") selected @endif>Male</option>
                                        <option id="Female" name="gender" @if($user->gender == "Female") selected @endif>Female</option>
                                        <option id="Non-binary" name="gender" @if($user->gender == "Non-binary") selected @endif>Non-binary</option>
                                      </select>
                                  </div>

                                  {{-- Form Group (Birthday)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="birth"><b>Birthday</b></label>
                                      <input class="form-control" type="date" id="birth" name="birth" value="{{$user->birth}}" required>
                                  </div>
                              </div>

                                {{-- Form Row        --}}
                                <div class="row gx-3 mb-3">
                                  {{-- Form Group (Password_New)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="password_new"><b>New Password</b></label>
                                      <input class="form-control" name="password_new" id="password_new" type="password" placeholder="Optional">
                                  </div>
                                  {{-- Form Group (Password_New2)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="password_new2"><b>Repeat New Password</b></label>
                                      <input class="form-control" name="password_new2" id="password_new2" type="password">
                                  </div>
                                </div>

                              {{-- Form Row--}}
                              <div class="row gx-3 mb-3">
                                  {{-- Form Group (Social provider)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="social_provider"><b>Social provider</b></label>
                                      <input class="form-control" id="social_provider" type="text" value="{{$user->social}}" >
                                  </div>

                                  {{-- Form Group (Social Nickname)--}}
                                  <div class="col-md-6">
                                      <label class="small mb-1" for="social_nickname"><b>Social nickname</b></label>
                                      <input class="form-control" id="social_nickname" type="text" value="{{$user->social}}" >
                                  </div>
                              </div>
                              {{--HIDDEN INPUT--}}
                              @csrf
                              <input type="hidden" name="_METHOD" value="PUT"/>

                              {{-- SUBMIT--}}
                                <button class="btn btn-info" type="submit[]">UPDATE</button>
                                {{-- SUBMIT--}}
                                <a href="{{ route('user-profile', ['id' => $id]) }}" class="btn btn-outline-secondary" type="button">BACK TO PROFILE</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </form>

  </div>


        {{-- Modal --}}
        <div class="modal fade" id="uploadModal" tabindex="1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                            <p class="modal-title" id="uploadModalLabel"><b>Upload photo</b></p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('profile-picture-upload', ['id' => $user->id])}}" enctype="multipart/form-data">
                            <label for="photo" class="form-label">Default file input example</label>
                            <input class="form-control" type="file" id="photo" name="photo" accept="image/*">

                            {{--HIDDEN INPUT--}}
                            @csrf
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" style="white-space: nowrap"><b>Upload <i class="fa-regular fa-image fa-2xs"></i></b></button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
                    </div>
                </div>
            </div>
        </div>

</body>

<script>
</script>

</html>
