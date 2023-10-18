@extends('releasevuejs::layout.layout_cloud')

@section('title')
    {{ 'Clients see releases' }}
    {!! '<i class="far fa-eye text-success"></i> <i class="far fa-eye text-warning"></i>' !!}
@endsection

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
        //$releases = \App\Containers\ReleaseVueJS\Models\ReleaseVueJS::orderByDesc('id')->paginate(request()->paginate ?? 10);

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
        function toggleDateDisplay(element) {
            if ((element.id).includes('difference')) {
                toggleOn = (element.id).replace('difference', 'date');
            } else {
                toggleOn = (element.id).replace('date', 'difference');
            }

            document.getElementById(toggleOn).style.display = 'flex';
            element.style.display = 'none';
        }

        function load_page(per_page) {
            var searchParams = new URLSearchParams(window.location.search);
            searchParams.set("paginate", per_page);
            window.location.search = searchParams.toString();
        }

        var openEyes = document.getElementById('sub_title_h5').innerHTML;
        var closedEyes =
            'Clients see releases <i class="far fa-window-minimize text-success"></i> <i class="far fa-window-minimize text-warning"></i>';
        //made blink a named function to improve readability a bit
        var blink = function(isSecondBlink) {
            //got rid of the ternary expressions since we're always doing
            //open eyes -> close eyes -> delay -> open eyes

            //close both eyes
            document.getElementById('sub_title_h5').innerHTML = closedEyes;

            //let's reopen those eyes after a brief delay to make a nice blink animation
            //as it happens, humans take ~250ms to blink, so let's use a number close to there
            setTimeout(function() {
                document.getElementById('sub_title_h5').innerHTML = openEyes;
            }, 200);

            if (isSecondBlink) {
                return;
            } //prevents us from blinking 3+ times

            //This provides a 40% chance of blinking twice, adjust as needed
            var blinkAgain = Math.random() <= 0.3;

            //if we need to blink again, go ahead and do it in 300ms
            if (blinkAgain) {
                setTimeout(function() {
                    blink(true);
                }, 300);
            }
        }

        //go ahead and blink every 2 seconds
        window.onload = setInterval(blink, 6000);

        //Prevent auto-close dropdown
        $('.dropdown-menu').on('click', function(e) {
            e.stopPropagation();
        });
        //Reset form on lose-focus
        //$('form').get(0).reset()
    </script>
@endsection

@section('header_sub')
    <div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <h2>{{ __('Show Releases - Cliént') }}</h2>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- begin:timeline -->
    <div class="container">
        {{-- Filter button --}}
        <button class="btn btn-circle btn-icon btn-light-primary btn-hover-primary pulse pulse-dark" type="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="flaticon2-dashboard text-primary"></i>
            <span class="pulse-ring"></span>
        </button>
        <div class="dropdown-menu dropdown-menu">
            <form class="px-8 py-8">
                <div class="form-group row">
                    <label for="pagination-number-input" class="col-4 col-form-label">Per page:</label>
                    <div class="col-8">
                        <input class="form-control" type="number" value="{{ request()->paginate ?? "10" }}" id="pagination-number-input" min="1" />
                    </div>
                </div>
                <div class="form-group row">
                  <label for="sorted-by-input" class="col-4 col-form-label">Sorted By:</label>
                  <div class="col-8">

                  </div>
                </div>
                <div class="form-group">

                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>
        <label for="level"><b>{{ $releases->withQueryString()->onEachSide(2)->links() }}</b></label>
    </div>

    <div class="example example-basic bg-white">
        <div class="example-preview">
            <div class="timeline timeline-4">
                <div class="timeline-bar"></div>
                <div class="timeline-items">

                    @foreach ($releases as $key => $release)
                        <div
                            class="timeline-item @if ($key % 2 == 0) {{ 'timeline-item-left' }}@else{{ 'timeline-item-right' }} @endif"">
                            <!--Style Indicator badge, but can be Icon, Images, ... -->
                            <!--Color code event E.g: Red = Alert, Yellow = Warning, Blue = Information, ...-->
                            <div class="timeline-badge">
                                @if ($key % 2 == 0)
                                    <div class="bg-success"></div>
                                @else
                                    <div class="bg-danger"></div>
                                @endif
                            </div>

                            <div class="timeline-label" id="timeline-label_difference_{{ $release->id }}"
                                style="display: flex; @if($key % 2 == 0) {{ 'flex-direction: row-reverse; ' }} @endif"
                                onclick="toggleDateDisplay(this)">
                                <span
                                    class="text-info label label-inline @if ($key % 2 == 0) {{ 'label-light-success' }}@else{{ 'label-light-danger' }} @endif font-weight-bolder">
                                    <!--Pick one-->
                                    <i class="fas fa-hourglass-end fa-sm text-info mr-1"></i>
                                    {{ convertTimeToAppropriateFormat(time() - strtotime($release->created_at)) . ' ago' }}
                                </span>
                            </div>

                            <div class="timeline-label" id="timeline-label_date_{{ $release->id }}"
                                style="display: none; @if($key % 2 == 0) {{ 'flex-direction: row-reverse;' }} @endif"
                                onclick="toggleDateDisplay(this)">
                                <span
                                    class="text-info label label-inline @if ($key % 2 == 0) {{ 'label-light-success' }}@else{{ 'label-light-  danger' }} @endif font-weight-bolder">
                                    <!--Pick one-->

                                    <i class="flaticon2-calendar-9 fa-sm text-info mr-1"></i>
                                    {{ date('H:i A d/m/Y', strtotime($release->created_at)) }}
                                </span>
                            </div>

                            <!-- Original: <div class="timeline-content max-h-150px overflow-auto" > -->
                            <div class="timeline-content gutter-b">
                                <div class="card card-custom card-stretch" id="kt_card_{{ $release->id }}">
                                    <div class="card-header card-header-tabs-line bg-secondary">
                                        <div class="card-title">
                                            <a class="card-label font-weight-bolder @if ($key % 2 == 0) {{ 'text-success' }}@else{{ 'text-danger' }} @endif"
                                                href="/releasevuejs/{{ $release->id }}">
                                                {{ $release->name }}
                                            </a>
                                        </div>
                                        <div class="card-toolbar">
                                            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                                {{-- Title_Description tab --}}
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab"
                                                        href="#kt_tab_pane_1_3_{{ $release->id }}">
                                                        <span class="nav-icon"><i class="flaticon2-information"></i></span>
                                                        <span class="nav-text">Tiêu đề</span>
                                                    </a>
                                                </li>
                                                {{-- Detail_Description tab --}}
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab"
                                                        href="#kt_tab_pane_2_3_{{ $release->id }}">
                                                        <span class="nav-icon"><i class="flaticon2-list-2"></i></span>
                                                        <span class="nav-text">Tóm tắt</span>
                                                    </a>
                                                </li>
                                                {{-- Images tab --}}
                                                {{-- <li class="nav-item">
                                                  <a class="nav-link" data-toggle="tab"
                                                      href="#kt_tab_pane_3_3_{{ $release->id }}">
                                                      <span class="nav-icon"><i class="flaticon2-photograph mr-2"></i></span>
                                                      <span class="nav-text">Ảnh</span>
                                                  </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content five-lines">
                                            {{-- Tab Short description --}}
                                            <div class="tab-pane fade show active" id="kt_tab_pane_1_3_{{ $release->id }}"
                                                role="tabpanel" aria-labelledby="kt_tab_pane_1_3_{{ $release->id }}">
                                                {{ $release->title_description }}
                                            </div>
                                            {{-- Tab Detail description --}}
                                            <div class="tab-pane fade max-h-200px overflow-ellipsis"
                                                id="kt_tab_pane_2_3_{{ $release->id }}" role="tabpanel"
                                                aria-labelledby="kt_tab_pane_2_3_{{ $release->id }}">
                                                {!! str_replace('src="', 'class="h-75px w-auto" src="', $release->detail_description) !!}
                                            </div>
                                            {{-- Tab images --}}
                                            {{-- <div class="tab-pane fade overflow-ellipsis max-hpx" id="kt_tab_pane_3_3_{{ $release->id }}"
                                                role="tabpanel" aria-labelledby="kt_tab_pane_3_3_{{ $release->id }}" style="max-height: 310px">

                                                @foreach ($release->images as $key => $image)
                                                  <img class="img-fluid border border-secondary mb-2 max-h-150px w-auto" src="{{ $image }}" alt="{{  $image  }}" width="100%" height="100%">
                                                @endforeach
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- end:timeline -->


    <!-- Modal-->
    <div class="modal fade" id="dataSortModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataSortModal1">Filters</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Show</span></div>
                            <input type="number" class="form-control" placeholder="Email" min="1"
                                value="{{ request()->paginate ?? '10' }}" />
                            <div class="input-group-append"><span class="input-group-text">result(s) per page</span></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label text-right col-lg-3 col-sm-12">Sorted By</label>
                        <div class="col-lg-4 col-md-9 col-sm-12">
                            <select class="form-control selectpicker">
                                <option>Ascending</option>
                                <option>Descending</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-9 col-sm-12">
                            <select class="form-control selectpicker">
                                <option>Ascending</option>
                                <option>Descending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Abort</button>
                    <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal"
                        onclick="applyFilter()">Apply</button>
                </div>
            </div>
        </div>
    </div>
@endsection
