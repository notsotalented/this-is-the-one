@extends('includes::layout.app_admin_nova')

@section('title', 'Create Role')

@section('css')
    <style>
        @include('includes::css.custom');
    </style>
@endsection


@section('content')
  <div class="container-fluid">
    <label><b>Create new role:</b></label> <br>
    <form action="/create-role-page" method="POST">
      {{--Standard info--}}
      <label for="name"><b>Name<i class="fa fa-asterisk fa-xs" style="color: #ff0000;"></i></b></label>
      <input type="text" class="form-control" placeholder="Length [2:20] Syntax: No spaces" name="name" value="{{old('name')}}" required>

      <label for="display_name"><b>Display name</b></label>
      <input type="text" class="form-control" placeholder="Length [0:100]" name="display_name" value="{{old('display_name')}}">
      

      <label for="description"><b>Description</b></label>
      <input type="text" class="form-control" placeholder="Length [0:255]" name="description" value="{{old('description')}}">

      <label for="level"><b>Level</b></label>
      <input type="number" class="form-control" placeholder="0" name="level" style="width: 10vw" value="{{old('level')}}" min="0" max="998">

        {{--Hidden stuff--}}
      @csrf
      
      <br>
      <button id="submit_button" class="btn btn-success" type="submit">Create</button>

    </form>

    <div class="container-fluid" style="margin-top: 5vh">
    {{--Display Roles and Permissions--}}
    <label><b>Existed roles</b></label>
      <div class="table-responsive">
        <table id="products_table" class="table table-info table-striped table-hover table-bordered">                     
          <thead class="thead-dark">
            <tr>
              <th>NAME</th>
              <th>DISPLAY NAME</th>
              <th>DESCRIPTION</th>
              <th>LEVEL</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
              @foreach($roles as $item)
                <tr>
                  <td scope="row" style="text-align:right">{{$item->name}}</td>
                  <td>{{$item->display_name}}</td>
                  <td>{{$item->description}}</td>
                  <td>{{$item->level}}</td>
                  <td>
                    @if($item->id != 1)
                      {{--<a href="{{ route('delete-role', ['id' => $item->id]) }}" type="button" class="btn btn-danger" role="button" aria-pressed="true">DEL</a>--}}
                      {{-- Button trigger modal --}}
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal{{$item->id}}">
                          DEL
                        </button>

                        {{-- Modal --}}
                        <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <p class="modal-title" id="exampleModalLabel"><b>Final confirmation</b></p>
                                <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                About to delete role: <span style="color: #f44336"><b>{{$item->display_name}}</b></span>
                              </div>
                              <div class="modal-footer">
                                <a href="{{ route('delete-role', ['id' => $item->id]) }}" type="button" class="btn btn-danger">Delete</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endif
                  </td>
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

@endsection


<script>
  new DataTable('#products_table', {
      order: [[3, 'desc']],
      columns:[
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
			      {
                "sortable": false
            }
        ],
  });
</script>
