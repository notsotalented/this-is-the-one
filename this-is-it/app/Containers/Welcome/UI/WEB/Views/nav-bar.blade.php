    <nav class="navbar sticky-top navbar-expand-lg bg-light" style="height: 60px">
    <div class="container-fluid">
        <a class="navbar-brand" href=" {{ route('home') }}"><b>HOME</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle @if(!auth()->check()){{'disabled'}}@endif" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Go-to <i class="fa-solid fa-rocket"></i>
                </a>
                <ul class="dropdown-menu">
                    <div class="container-fluid">

                    
                    @can('list-users')
                        <li><a id="go-to-user" class="dropdown-item" href="{{route('users-profile')}}">Users</a></li>
                    @endcan
                    
                    </div>
                </ul>
            </li>

            @can('access-dashboard')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Dashboard <i class="fa-solid fa-gear fa"></i>
                </a>
                <ul class="dropdown-menu">
                    <div class="container-fluid">
                    <li><a class="dropdown-item" href="{{route('list-page')}}">Data Tables</a></li>
                    @can('manage-roles')
                        <li><hr class="dropdown-divider"></li>
                        <li><a id="go-to-permission" class="dropdown-item" href="{{ route('role-page', ['action' => 'attach']) }}">Manage Permissions</a></li>
                        <li><a id="go-to-role" class="dropdown-item" href="{{route('create-role')}}">Manage Roles</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" aria-current="page" href="{{route('register-power')}}">Add Power User</a></li>
                    @endcan
                    </div>
                </ul>
            </li>
            @endcan

        </ul>
        {{--@can('search-users')--}}
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">

            
                <div class="input-group me-auto mb-2 mb-lg-0 ml-1">
                    <span class="input-group-text bg-info" id="search_barr"><i class="fa-solid fa-binoculars fa-sm" style="color: white"></i></span>
                    <input data-bs-toggle="dropdown" data-bs-target="alibabon" type="text" id="search_bar" name="search_bar" class="form-control me-0" placeholder="Search anything" aria-label="search" aria-describedby="search_box" style="width: 22vw" autocomplete="off">
                
                    <ul id="alibabon" class="dropdown-menu dropdown-menu-lg-end" style="overflow:auto; max-height: 50vh;">
                        <div class="container-fluid" id="alibaba"></div>
                    </ul>
                </div>
  
            </li>
        </ul>
        {{-- Button trigger modal --}}
        <button type="button" class="btn btn-outline-info mb-2 mb-lg-0 ml-1" data-bs-toggle="modal" data-bs-target="#exampleModal" style="margin-left: 1%">
            <b>Search <i class="fa-solid fa-magnifying-glass fa-2xs"></i></b>
        </button>

        {{--@endcan--}}

        @if(auth()->check())
        <div class="btn-group mb-2 mb-lg-0 ml-1" style="margin-left: 1%">
            <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="width: 25vh; overflow: hidden">
                <span>
                    @if(auth()->user()->social_avatar)
                        <img class="img-account-profile rounded-circle mb-lg-0" style="max-width: 4vh; max-height: 4vh; overflow: auto" src="{{ asset('uploads/photos/'.auth()->user()->social_avatar) }}" alt="{{auth()->user()->name}} profile picture">
                    @else
                        <i class="fa-regular fa-user"></i>
                    @endif 
                    {{ auth()->user()->name }}
                <span>
              </button>
            <ul class="dropdown-menu dropdown-menu-lg-end">
                <li><a class="dropdown-item" href="{{ route('user-profile', ['id' => auth()->id()])}}">User Profile <i class="fa-regular fa-address-card"></i></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href = {{ route('test-page') }}> Test ground <i class="fa-solid fa-vials"></i></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item bg-color-red" href="{{route('logout')}}" style="color:red">Logout <i class="fa-solid fa-power-off"></i></a></li>
            </ul>
        </div>
        @else
            <a class="btn btn-success" aria-current="page" href="{{route('login')}}" style="margin-left: 1%"><b>Login</b></a>
            <a class="btn btn-warning" aria-current="page" href="{{route('register')}}" style="margin-left: 1%"><b>Register</b></a>
        @endif
        </div>
    </div>
</nav>

<div class="container-fluid" style="padding: 0vw">
    {{--MESSAGE CENTER--}}
    @if(session('status'))
    <div class="alert alert-@if(preg_match('/successfully/', session('status'))){{'success'}}@else{{'warning'}}@endif alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-info fa"></i> {{session('status')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
          <li class="list-group-item"><i class="fa-solid fa-triangle-exclamation fa"></i> {{$error}} </li>
        @endforeach
      <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

    {{-- Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title" id="exampleModalLabel"><b>Advanced Search Box</b></p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{--Search section--}}
                        <div class="input-group mb-3">
                            <select id="search_where" class="form-select" style="width: 10vw" onchange="search_what()">
                                <option class="dropdown-item" value="/users">Users</option>
                                <option class="dropdown-item" value="/products">Products</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" style="width: 20%">ID</span>
                            <input id="search_id" type="text" class="form-control" placeholder="Exact search" aria-label="search_id" aria-describedby="search_id">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="width: 20%">Name</span>
                            <input id="search_name" type="text" class="form-control" placeholder="Relative search" aria-label="search_name" aria-describedby="search_name">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="width: 20%">Email</span>
                            <input id="search_email" type="text" class="form-control" placeholder="Relative search" aria-label="search_email" aria-describedby="search_email">
                        </div>

                        <hr>

                        <div class="input-group mb-3">
                            <span class="input-group-text" style="width: 20%">Order by</span>
                            <select id="orderBy" class="form-select" aria-label="orderBy">
                                <option value="">None</option>
                                <option value="id">ID</option>
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                            </select>
                            <select id="sortedBy" class="form-select " aria-label="sortedBy">
                                <option value="">Sort</option>
                                <option value="asc">ASC</option>
                                <option value="desc">DESC</option>
                            </select>
                        </div>
                        {{--End Search section--}}
                    </div>
                    <div class="modal-footer">
                        <a id="search_btn" href="#" class="btn btn-info" type="button" style="white-space: nowrap" onclick="finalize_search()"><b>Search <i class="fa-solid fa-magnifying-glass fa-2xs"></i></b></a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
                    </div>
                </form>
                </div>
        </div>
    </div>

</div>

<script type="text/javascript">

    function finalize_search() {
        var where = document.getElementById("search_where").value;
        var id = document.getElementById("search_id").value;
        var name = document.getElementById("search_name").value;
        var email = document.getElementById("search_email").value;
        var sortedBy = document.getElementById("sortedBy").value;
        var orderBy = document.getElementById("orderBy").value;


        var search_what = '';
        var add = false;

        if (id) {
           search_what = search_what + 'id:' + id;
           add = true;
        }
        if (name) {
            if(add) {
                add = false;
                search_what = search_what + ';';
            }
           search_what = search_what + 'name:' + name;
           add = true;
        }
        if (email) {
            if(add) {
                add = false;
                search_what = search_what + ';';
            }
           search_what = search_what + 'email:' + email; 
        }
        if (orderBy) {
            if(search_what != '') {
                search_what = search_what + '&orderBy=' + orderBy + '&sortedBy=' + sortedBy;
            }
        }
        //console.log(search_what);

        document.getElementById("search_btn").href = where + "?search=" + search_what;
    }

    $('#exampleModal').on('hidden.bs.modal', function () {
        //Clean data of modal
        $('#search_where').val('/users');
        $('#orderBy').val('');
        $('#sortedBy').val('');
        $('#search_id').val('');
        $('#search_name').val('');
        $('#search_email').val('');
    })

    $("#form_id").trigger("reset");

    //Change properties of HTML entities with URI
    $( document ).ready(function() {
        var searchParams = window.location.href;
        //get last element of url
        var links_list = document.getElementsByTagName("a");

        for (var i = 0; i < links_list.length; i++) {
            if(links_list[i].href == searchParams && links_list[i].className != "navbar-brand") {
                links_list[i].classList.add("active");
                
                if(links_list[i].classList.contains("btn")) {
                links_list[i].classList.add("disabled");
                }
            }
        }

    });

</script>

<script type="text/javascript">
    $('#search_bar').on('keyup',function(){
        $value = $(this).val();
        if ($value != "...") {
            $.ajax({
                type: 'GET',
                url: '{{ URL::to('/search') }}',
                data: {
                    'search_bar': $value
                },
                success:function(data){
                    //func search()
                    
                    $('#alibaba').html(data);
                    $('#alibaba').css('display', 'block');
                    $("#alibaba").focusout(function () {
                        $('#alibaba').css('display', 'none');
                    });
                    $("#search_bar").focusin(function () {
                        $('#alibaba').css('display', 'block');
                    });
                    


                    //func search_this()
                    //console.log(data);

                }
            });
        }
        else {
            $('#alibaba').css('display', 'none');
        }

    })
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>



