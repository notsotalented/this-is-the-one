@extends('includes::layout.app_admin_nova')

@section('title', 'Data Tables')

@section('css')
    <style>
        @include('includes::css.custom');
    </style>

    <style>
        .five-lines {
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection

@section('php')
    @php
        $products = \App\Containers\Product\Models\Product::all();

        //Depends on the order
        $releases = \App\Containers\ReleaseVueJS\Models\ReleaseVueJS::orderByDesc('id')->get();

        if (!function_exists('convertTimeToAppropriateFormat')) {
            function convertTimeToAppropriateFormat($time)
            {
                $suffix = ['sec(s)', 'min(s)', 'hour(s)', 'day(s)', 'week(s)', 'month(s)', 'year(s)', 'dummy'];
                $multi = [60, 60, 24, 7, 4.34, 12, 1111];

                $i = 0;

                while ($time >= $multi[$i] && $i <= 5) {
                    $time /= $multi[$i];
                    $i++;
                }

                return round($time, 0) . ' ' . $suffix[$i];
            }
        }
    @endphp
@endsection

@section('javascript')
    <script type="text/javascript">
        function lightsUp(element) {
          //Do something cool

        }

        function lightsDown(element) {
          //Do something uncool

        }

        function calculateTimeDifference(time) {

          var inputTime = new Date(time).getTime();
          var currentTime = new Date().getTime();
          var timeDifferenceInSeconds = Math.floor((currentTime - inputTime) / 1000);

          var seconds = timeDifferenceInSeconds % 60;
          var minutes = Math.floor((timeDifferenceInSeconds / 60) % 60);
          var hours = Math.floor((timeDifferenceInSeconds / 60 / 60) % 24);
          var days = Math.floor(timeDifferenceInSeconds / 60 / 60 / 24);

          var currentDate = new Date();
          var year = currentDate.getFullYear();
          var month = currentDate.getMonth() + 1;
          var day = currentDate.getDate();

          var formattedTimeDifference = hours + ":" + minutes + ":" + seconds + " " + day + ":" + month + ":" + year;

          console.log(formattedTimeDifference);
        }
    </script>
@endsection

@section('content')
    <!-- begin:timeline -->

    <div class="example example-basic" style="background-color: ivory">
        <div class="example-preview">
            <div class="timeline timeline-4">
                <div class="timeline-bar"></div>
                <div class="timeline-items">

                    @foreach ($releases as $key => $release)
                        <div class="timeline-item timeline-item-@if($key % 2 == 0){{ 'left' }}@else{{ 'right' }}@endif""
                        onmouseover="lightsUp(this)" onmouseout="lightsDown(this)">
                            <!--Style Indicator badge, but can be Icon, Images, ... -->
                            <!--Color code event E.g: Red = Alert, Yellow = Warning, Blue = Information, ...-->
                            <div class="timeline-badge">
                                @if ($release->id % 2 == 0)
                                    <div class="bg-danger"></div>
                                @else
                                    <div class="bg-primary"></div>
                                @endif

                            </div>

                            <div class="timeline-label">
                                <span class="text-info font-weight-bold">
                                    {{ $release->created_at->format('d-m-Y H:i:s') }}
                                    <br>
                                    {{"Đã ". convertTimeToAppropriateFormat(time() - strtotime($release->created_at)) ." trôi qua"}}
                                    <br>
                                    {{ $release->created_at->format('H:i A') }}
                                </span>
                                <br>
                                <b>{{ $release->name }}</b>
                            </div>




                            <!-- Original: <div class="timeline-content max-h-150px overflow-auto" > -->
                            <div class="timeline-content max-h-30 overflow-auto">
                                <span class="text-dark-75 font-weight-bold">
                                    <i class="fas fa-coins fa-sm fa-spin" style="color: #dfa134;"></i>
                                    {{ $release->title_description }}
                                </span>
                                <br>
                                <span class="text-dark-75 five-lines">
                                    <!-- Need to unescape the HTML to display the text as is -->
                                    {!! $release->detail_description !!}
                                </span>
                                <br>

                                <span class="text-dark-75">
                                    <!-- Route to specific release -->
                                    <a href="{{ route('web_releasevuejs_show_detail_release', $release->id) }}"
                                        class="text-hover-dark" style="float: right">To The Moon</a>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
    <!-- end:timeline -->
@endsection
