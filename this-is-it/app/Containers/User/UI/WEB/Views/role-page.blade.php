@extends('includes::layout.app_admin_nova')

@section('title', 'Manage Permissions')

@section('css')
    <style>
        @include('includes::css.custom');
    </style>
@endsection

@section('content')

    @isset($status)
        <div class="alert alert-success" role="alert">
            {{ $status }}
        </div>
    @endisset

    @isset($errors)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endisset

    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-success" href="{{ route('role-page-action', ['action' => 'attach']) }}">Attach</a>
        <a class= "btn btn-danger" href="{{ route('role-page-action', ['action' => 'detach']) }}">Detach</a>
    </div>

    <br>

    @foreach ($roles as $role)
    <form method="POST" action="{{ route('role-page-action', ['action' => $action]) }}" id="form{{$role->id}}">
        <div class="card mb-4 mb-xl-0" style="margin-top: 5vh">
            <div class="card-header">
                <h5 style="float: left">{{ $role->display_name . " (" . $role->name . ")" }}<h5>
                @csrf
                <button class="btn btn-@if($action=='attach'){{'success'}}@else{{'danger'}}@endif" type="submit" name="form{{$role->id}}" style="float: right"><b>@if($action=='attach') Attach @else Detach @endif</b></button>
            </div>
            <div class="card-body text-center" style="overflow: scroll">
                <div class="btn-group btn-group-toggle" aria-label="Toolbar with button groups" data-toggle="buttons">
                    @foreach ($permissions as $permission)
                        <label class="btn
                        @php
                            $active = false;
                            foreach ($assigned[$permission->id] as $check) {
                                if($check->role_id == $role->id){
                                    $active = true;
                                }
                            }
                            $active ? $msg = 'btn-danger' : $msg = 'btn-outline-success';
                            switch ($msg) {
                                case 'btn-danger':
                                    if($action == 'attach') $msg .= ' disabled';
                                    else $msg = 'btn-outline-danger';
                                    break;
                                case 'btn-outline-success':
                                    if ($action == 'detach') $msg .= ' disabled';
                                    break;
                                default:
                                    break;
                            }
                            echo $msg;
                        @endphp
                        ">
                            <input type="checkbox" name="permissions_ids[]" id="checkR{{$role->id}}P{{$permission->id}}" value="{{$permission->id}}"
                            @foreach ($assigned[$permission->id] as $check)
                                @if($check->role_id == $role->id)
                                    {{ '' }}
                                @endif
                            @endforeach
                            >{{$permission->name}}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <input type="hidden" name="role_id" value="{{ $role->id }}">
    </form>
    @endforeach
@endsection