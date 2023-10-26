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

        input[type="checkbox"] {
            vertical-align: middle;
            display: -webkit-inline-flex
        }
    </style>
@endsection

@section('php')
    @php
        //Convert from created_at to Time difference (i.e 01-01-2021 <--> 3 years ago)
        if (!function_exists('convertTimeToAppropriateFormat')) {
            function convertTimeToAppropriateFormat($time)
            {
                $suffix = ['giây', 'phút', 'giờ', 'ngày', 'tuần', 'tháng', 'năm', 'cái này ở đây để khỏi bị lỗi'];
                //Minute-Hour-Day-"Month"-Year
                $multi = [60, 60, 24, 7, 4.34, 12, 1111];

                $i = 0;

                //Repeatedly find the appropriate time different (i.e 1000/60 = 16.67 = ~17 mins ago; 10000/60/60 = 2.78 = ~3 hours ago)
                while ($time >= $multi[$i] && $i <= 5) {
                    $time /= $multi[$i];
                    $i++;
                }

                return round($time, 0) . ' ' . $suffix[$i];
            }
        }

        //Adjust date color depends on the time difference ([0-7): fresh [7:30): medium [30:infinity): old)
        if (!function_exists('dateColorFading')) {
            function dateColorFading($date = null, $elem = 1)
            {
                if ($date === null) {
                    return 'bg-success-o-20 text-dark-50';
                }
                //Get input time and current time
                $date = strtotime($date);
                $now = time();

                $diff = $now - $date;
                $result = '';
                if ($diff < 60 * 60 * 24 * 7) {
                    //Fresh [0:7)
                    $background = 'bg-success-o-70';
                    $text = 'text-primary';
                } elseif ($diff >= 60 * 60 * 24 * 7 && $diff < 60 * 60 * 24 * 30) {
                    //Medium [7:30)
                    $background = 'bg-success-o-40';
                    $text = 'text-dark-75';
                } else {
                    //Old [30:infinity)
                    $background = 'bg-success-o-20';
                    $text = 'text-dark-50';
                }
                //Elem 1 =
                switch ($elem) {
                    case '1':
                        return $background . ' ' . $text;
                        break;
                    case '2':
                        return $text;
                        break;
                    default:
                        return $background . ' ' . $text;
                        break;
                }
            }
        }
    @endphp
@endsection

@section('javascript')
    <script type="text/javascript">
        //Switch display of Time difference <-> Time create (i.e 01-01-2021 <--> 3 years ago)
        function toggleDateDisplay(element) {
            //Get elems
            diff = document.getElementById("display_diff_" + element);
            date = document.getElementById("display_date_" + element);
            //Alternate between 2 display style: none vs -webkit-inline-box (hide vs show)
            if (date.style.display == "none") {
                date.style.display = "-webkit-inline-box";
                diff.style.display = "none";
            } else {
                date.style.display = "none";
                diff.style.display = "-webkit-inline-box";
            }
        }

        //Lights "up"
        function lightsUp(element) {
            element.style.brightness = "0.95";
        }

        //Lights "down"
        function lightsDown(element) {
            element.style.brightness = "1";
        }

        //Add filter to url (i.e ?filter=id;name;...)
        function filterFormParams() {
            //Get url
            link = window.location.href;
            //Prepare all params
            param = '';
            sortedBy = document.querySelector('select[name="sortedBy"]');
            orderBy = document.querySelector('select[name="orderBy"]');
            paginate = document.querySelector('input[name="paginate"]');
            filter = document.querySelectorAll('input[name="filter[]"]');
            //Add existed params to the param variable, prefix ? if param empty or & if not empty
            if (paginate && paginate.value != '10')(param == '') ? param += '?paginate=' + paginate.value : param +=
                '&paginate=' +
                paginate.value;
            if (orderBy && orderBy.value != '')(param == '') ? param += '?orderBy=' + orderBy.value : param +=
                '&orderBy=' +
                orderBy.value;
            if (sortedBy && sortedBy.value != '')(param == '') ? param += '?sortedBy=' + sortedBy.value : param +=
                '&sortedBy=' +
                sortedBy.value;
            //Filter string, add ; if filter_string not empty when element is checked (i.e checked fields - name, id, ...)
            filter_string = '';
            filter.forEach(element => {
                (element.checked) ? (filter_string == '') ? filter_string += element.value: filter_string +=
                    ';' + element.value: null
            });
            (filter_string != '') ? (param == '') ? param += '?filter=' + filter_string: param += '&filter=' +
                filter_string: null;
            //Redirect
            window.location.href = window.location.pathname + param;
        }

        function checkParamsLegitToNotify() {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            //Generate parameters object
            const paramsObject = {};
            for (const [key, value] of urlParams.entries()) {
                if (!paramsObject[key]) {
                    paramsObject[key] = [];
                }
                paramsObject[key].push(value);
            }

            legit = false;

            if ((paramsObject.filter && paramsObject.filter != '') || (paramsObject.sortedBy && paramsObject.sortedBy !=
                    '') ||
                (paramsObject.orderBy && paramsObject.orderBy != '') || (paramsObject.paginate && paramsObject
                    .paginate != '10')) {
                legit = true;
            }

            if (legit) {
                document.getElementById('pulseRingHere').classList.add('pulse', 'pulse-success');
                document.getElementById('clearFilterButton').removeAttribute("hidden");
            }

            return legit;
        }

        //THIS IS A TEST FOR ANIMATION
        //Blink animation, testing for some Js function
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
        window.onload = checkParamsLegitToNotify();
        //END TEST

        function submitFunction(e) {
            if (document.activeElement != document.getElementById('apply-filter-btn')) {
                e.preventDefault();
            }
        }
    </script>
@endsection

@section('header_sub')
    {{-- Header/Title --}}
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
    {{-- Filter Accordion --}}
    <div class="container">
        <div class="accordion accordion-solid accordion-svg-toggle" id="accordionFilter">
            <div class="card">
                {{-- Filter header --}}
                <div class="card-header" id="headingOne6">
                    <div class="card-title bg-secondary collapsed" data-toggle="collapse" data-target="#collapseFilter"
                        aria-expanded="false">
                        <span id="pulseRingHere" class="label label-white mr-2">
                            <span class="pulse-ring"></span>
                            <span
                                class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Tools/Tools.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M15.9497475,3.80761184 L13.0246125,6.73274681 C12.2435639,7.51379539 12.2435639,8.78012535 13.0246125,9.56117394 L14.4388261,10.9753875 C15.2198746,11.7564361 16.4862046,11.7564361 17.2672532,10.9753875 L20.1923882,8.05025253 C20.7341101,10.0447871 20.2295941,12.2556873 18.674559,13.8107223 C16.8453326,15.6399488 14.1085592,16.0155296 11.8839934,14.9444337 L6.75735931,20.0710678 C5.97631073,20.8521164 4.70998077,20.8521164 3.92893219,20.0710678 C3.1478836,19.2900192 3.1478836,18.0236893 3.92893219,17.2426407 L9.05556629,12.1160066 C7.98447038,9.89144078 8.36005124,7.15466739 10.1892777,5.32544095 C11.7443127,3.77040588 13.9552129,3.26588995 15.9497475,3.80761184 Z"
                                            fill="#000000" />
                                        <path
                                            d="M16.6568542,5.92893219 L18.0710678,7.34314575 C18.4615921,7.73367004 18.4615921,8.36683502 18.0710678,8.75735931 L16.6913928,10.1370344 C16.3008685,10.5275587 15.6677035,10.5275587 15.2771792,10.1370344 L13.8629656,8.7228208 C13.4724413,8.33229651 13.4724413,7.69913153 13.8629656,7.30860724 L15.2426407,5.92893219 C15.633165,5.5384079 16.26633,5.5384079 16.6568542,5.92893219 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                        </span>
                        <span class="mr-2 font-weight-bold">Bộ lọc</span>
                    </div>
                </div>
                {{-- Filter content --}}
                <div id="collapseFilter" class="collapse" data-parent="#accordionFilter">
                    <div class="card-body">
                        {{-- Filter input fake form --}}
                        <form id="filter_input" onsubmit="submitFunction(this)">
                            {{-- Exploit to disable submit form while hit Enter inside a text input field --}}
                            <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                            <div class="form-group row">

                            </div>
                            <div class="form-group row">
                                <label for="paginate" class="col-4 col-form-label">{{-- Per page: --}}Hiển thị tối
                                    đa:</label>
                                <div class="col-8">
                                    <input class="form-control" type="number" value="{{ request()->paginate ?? '10' }}"
                                        name="paginate" min="1" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orderBy" class="col-4 col-form-label">{{-- Sorted By: --}}Sắp xếp
                                    theo:</label>
                                <div class="col-4" style="display: -webkit-inline-block">
                                    <select class="form-control" name="orderBy">
                                        <option value="">...</option>
                                        <option value="id" @if (request()->orderBy == 'id') {{ 'selected' }} @endif>
                                            ID</option>
                                        <option value="title_description"
                                            @if (request()->orderBy == 'title_description') {{ 'selected' }} @endif>
                                            Tựa đề</option>
                                        <option value="detail_description"
                                            @if (request()->orderBy == 'detail_description') {{ 'selected' }} @endif>
                                            Tóm tắt</option>
                                        <option value="created_at"
                                            @if (request()->orderBy == 'created_at') {{ 'selected' }} @endif>
                                            Ngày
                                            khởi tạo</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <select class="form-control" name="sortedBy">
                                        <option value="">...</option>
                                        <option @if (request()->sortedBy == 'asc') {{ 'selected' }} @endif value="asc">
                                            {{-- ASC --}}Tăng</option>
                                        <option @if (request()->sortedBy == 'desc') {{ 'selected' }} @endif value="desc">
                                            {{-- DESC --}}Giảm
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline-flex">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input type="checkbox" value="id"
                                                name="filter[]"@if (strpos(request()->filter, 'id') !== false) {{ 'checked' }} @endif />
                                            <span></span>
                                            ID
                                        </label>
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input type="checkbox" value="name" name="filter[]"
                                                @if (strpos(request()->filter, 'name') !== false) {{ 'checked' }} @endif />
                                            <span></span>
                                            Tên
                                        </label>
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input type="checkbox" value="title_description" name="filter[]"
                                                @if (strpos(request()->filter ?? '', 'title_description') !== false) {{ 'checked' }} @endif />
                                            <span></span>
                                            Tựa đề
                                        </label>
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input type="checkbox" value="detail_description" name="filter[]"
                                                @if (strpos(request()->filter ?? '', 'detail_description') !== false) {{ 'checked' }} @endif />
                                            <span></span>
                                            Tóm tắt
                                        </label>
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input type="checkbox" value="created_at" name="filter[]"
                                                @if (strpos(request()->filter ?? '', 'created_at') !== false) {{ 'checked' }} @endif />
                                            <span></span>
                                            Ngày khởi tạo
                                        </label>
                                    </div>
                                    <span class="form-text text-muted">Chọn các nội dung cần hiện và ẩn những thứ
                                        khác</span>
                                </div>
                            </div>

                            <button id="clearFilterButton" type="button" class="btn btn-secondary" hidden
                                onclick="window.location.replace(location.pathname)">Xóa bộ lọc <span id="numberOfFilters"
                                    class="badge badge-danger">
                                    {{ count(request()->all()) }}</span></button>

                            <button type="reset" class="btn btn-light btn-hover-secondary"">Cài lại
                                <i class="flaticon2-refresh-1"></i></button>
                            <button id='apply-filter-btn' type="button" class="btn btn-primary"
                                onclick="filterFormParams(this)">Áp dụng <i
                                    class="flaticon2-check-mark icon-nm"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="example example-basic">
            <div class="example-preview bg-white">
                <div class="timeline timeline-4">
                    <div class="timeline-bar"></div>
                    <div class="timeline-items">

                        @foreach ($releases as $key => $release)
                            <div
                                class="timeline-item @if ($key % 2 == 0) {{ 'timeline-item-left' }}@else{{ 'timeline-item-right' }} @endif"">
                                {{-- Badge indicator: at the moment, just for the visual --}}
                                {{-- Suggestion: use it (and the color scheme) to indicate errors-danger, notification-info, ... --}}
                                <div class="timeline-badge">
                                    @if ($key % 2 == 0)
                                        <div class="bg-success"></div>
                                    @else
                                        <div class="bg-danger"></div>
                                    @endif
                                </div>
                                {{-- Date/Time difference label --}}
                                <div class="timeline-label">
                                    @if (date('Y-m-d', strtotime($release->created_at)) == date('Y-m-d'))
                                        <span class="label label-danger label-inline" style="font-style: italic">Mới ra
                                            lò</span>
                                    @endif
                                    <span id="display_diff_{{ $key }}"
                                        class=" label label-inline label-light-success {{ dateColorFading($release->created_at, 1) }} font-weight-bolder"
                                        style="display: -webkit-inline-box;"
                                        onclick="toggleDateDisplay({{ $key }})">
                                        {{-- Display time difference (from create till now) --}}
                                        <i
                                            class="far fa-clock icon-nm {{ dateColorFading($release->created_at, 2) }} mr-1"></i>
                                        @if ($release->created_at)
                                            {{ convertTimeToAppropriateFormat(time() - strtotime($release->created_at)) . ' trước' }}
                                        @elseif ($release->created_at && strpos(request()->filter ?? '', 'created_at') === false)
                                            {{ 'Thời gian đã ẩn' }}
                                        @else
                                            {{ 'Không có dữ liệu thời gian' }}
                                        @endif
                                    </span>
                                    <span id="display_date_{{ $key }}"
                                        class=" label label-inline label-light-success {{ dateColorFading($release->created_at, 1) }} font-weight-bolder"
                                        style="display: none;" onclick="toggleDateDisplay({{ $key }})">
                                        {{-- Display create date --}}
                                        <i
                                            class="far fa-calendar-alt icon-nm {{ dateColorFading($release->created_at, 2) }} mr-1"></i>
                                        @if ($release->created_at)
                                            {{ date('d-m-y H:i:s', strtotime($release->created_at)) }}
                                        @else
                                            {{ 'Không có dữ liệu thời gian' }}
                                        @endif
                                    </span>
                                </div>

                                {{-- Original: <div class="timeline-content max-h-150px overflow-auto" > --}}
                                <div class="timeline-content gutter-b">
                                    <div class="card card-custom card-stretch" id="kt_card_{{ $key }}">
                                        <div class="card-header card-header-tabs-line bg-secondary">
                                            <div class="card-title">
                                              {{-- Card title, with optional "dummy anchor <a>" when the IDs are hidden --}}
                                                <a class="card-label font-weight-bolder @if ($key % 2 == 0) {{ 'text-success' }}@else{{ 'text-danger' }} @endif"
                                                    @isset($release->id){{ "href=/releasevuejs/$release->id" }}@else {{ 'role="link" aria-disabled="true"' }}@endisset>
                                                    {{ $release->name }}
                                                </a>
                                            </div>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                                    {{-- Title_Description tab --}}
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab"
                                                            href="#kt_tab_pane_1_3_{{ $key }}">
                                                            <span class="nav-icon"><i
                                                                    class="flaticon2-information"></i></span>
                                                            <span class="nav-text">Tựa đề</span>
                                                        </a>
                                                    </li>
                                                    {{-- Detail_Description tab --}}
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab"
                                                            href="#kt_tab_pane_2_3_{{ $key }}">
                                                            <span class="nav-icon"><i class="flaticon2-list-2"></i></span>
                                                            <span class="nav-text">Tóm tắt</span>
                                                        </a>
                                                    </li>
                                                    {{-- Images tab --}}
                                                    {{-- <li class="nav-item">
                                                  <a class="nav-link" data-toggle="tab"
                                                      href="#kt_tab_pane_3_3_{{ $key }}">
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
                                                <div class="tab-pane fade show active"
                                                    id="kt_tab_pane_1_3_{{ $key }}" role="tabpanel"
                                                    aria-labelledby="kt_tab_pane_1_3_{{ $key }}">
                                                    @if ($release->title_description)
                                                        {{ $release->title_description }}
                                                    @else
                                                        {{ 'Không có dữ liệu' }}
                                                    @endif
                                                </div>
                                                {{-- Tab Detail description --}}
                                                <div class="tab-pane fade" id="kt_tab_pane_2_3_{{ $key }}"
                                                    role="tabpanel"
                                                    aria-labelledby="kt_tab_pane_2_3_{{ $key }}">
                                                    {{-- Add h-75px w-auto to image, sort of "limitation" of the images' frame --}}
                                                    {!! str_replace('src="', 'class="h-75px w-auto" src="', $release->detail_description) !!}
                                                </div>
                                                {{-- Tab images --}}
                                                {{-- <div class="tab-pane fade overflow-ellipsis max-hpx" id="kt_tab_pane_3_3_{{ $key }}"
                                                role="tabpanel" aria-labelledby="kt_tab_pane_3_3_{{ $key }}" style="max-height: 310px">

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

                {{-- Paginator navigation --}}
                {{-- Edit onEachSide() for manipulating the display of number of pages --}}
                <label for="level" class="d-flex justify-content-center"><b>{!! $releases->withQueryString()->onEachSide(2)->links() !!}</b></label>
            </div>
        </div>
        <!-- end:timeline -->
    </div>
@endsection


@section('footer')
    Footer placeholder nhưng mà hình như không có set attribute 'yield' hoặc em 'yield' sai chỗ
@endsection
