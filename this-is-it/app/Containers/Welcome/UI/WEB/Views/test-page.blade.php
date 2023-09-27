@extends('includes::layout.app_admin_nova')

@section('title', 'Data Tables')

@section('css')
    <style>
        @include('includes::css.custom');
    </style>
@endsection

@php
    $child = '/test-page?table=1';

    $child2 = '/test-page/table/2';

    $child3 = '/test-page/table/3';

    $child4 = '/test-page/table';

    function checkIfChild($child) {
        $list =  explode('/', $child);

        $list_url = explode('/', request()->url());
        $list_url = array_slice($list_url, 3);

        //print_r($list);
        //print_r($list_url);

        $diff = array_diff($list_url, $list);

        if (!$diff) {
            return true;
        }
        else {
            return false;
        }
    }

@endphp

@section('content')
    <div>
        <a id="test" href="{{ $child }}" target="_self">
            <div class="items-center" style="width: 100%">
                <span class="text" style="width: 20%; display: inline-block">

                    @if(checkIfChild($child))
                    {!! '<fa class="fa fa-eye" aria-hidden="true"></fa>' !!}
                    @endif

                </span>
                <span class="text"
                    style="width: 80%; display: inline-block; @if(checkIfChild($child)) {{ 'font-weight: bold; font-style: italic' }} @endif">
                    {{ $child }}
                </span>
            </div>
        </a>

        <a id="test" href="{{ $child2 }}" target="_self">
            <div class="items-center" style="width: 100%">
                <span class="text" style="width: 20%; display: inline-block">

                    @if(checkIfChild($child2))
                    {!! '<fa class="fa fa-eye" aria-hidden="true"></fa>' !!}
                    @endif

                </span>
                <span class="text"
                    style="width: 80%; display: inline-block; @if(checkIfChild($child2)) {{ 'font-weight: bold; font-style: italic' }} @endif">
                    {{ $child2 }}
                </span>
            </div>
        </a>

        <a id="test" href="{{ $child3 }}" target="_self">
            <div class="items-center" style="width: 100%">
                <span class="text" style="width: 20%; display: inline-block">

                    @if(checkIfChild($child3))
                    {!! '<fa class="fa fa-eye" aria-hidden="true"></fa>' !!}
                    @endif

                </span>
                <span class="text"
                    style="width: 80%; display: inline-block; @if(checkIfChild($child3)) {{ 'font-weight: bold; font-style: italic' }} @endif">
                    {{ $child3 }}
                </span>
            </div>
        </a>

        <a id="test" href="{{ $child4 }}" target="_self">
            <div class="items-center" style="width: 100%">
                <span class="text" style="width: 20%; display: inline-block">

                    @if(checkIfChild($child4))
                    {!! '<fa class="fa fa-eye" aria-hidden="true"></fa>' !!}
                    @endif

                </span>
                <span class="text"
                    style="width: 80%; display: inline-block; @if(checkIfChild($child4)) {{ 'font-weight: bold; font-style: italic' }} @endif">
                    {{ $child4 }}
                </span>
            </div>
        </a>
    </div>

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100" src="..." alt="First slide">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100" src="..." alt="Second slide">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100" src="..." alt="Third slide">
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
@endsection