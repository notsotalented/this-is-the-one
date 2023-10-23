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

        //Convert from created_at to Time difference
        if (!function_exists('convertTimeToAppropriateFormat')) {
            function convertTimeToAppropriateFormat($time)
            {
                $suffix = ['giây', 'phút', 'giờ', 'ngày', 'tuần', 'tháng', 'năm', 'cái này ở đây để khỏi bị lỗi'];
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
        //Switch display of Time difference <-> Time create
        function toggleDateDisplay(element) {
            diff = document.getElementById("display_diff_" + element);
            date = document.getElementById("display_date_" + element);

            if (date.style.display == "none") {
                date.style.display = "-webkit-inline-box";
                diff.style.display = "none";
            } else {
                date.style.display = "none";
                diff.style.display = "-webkit-inline-box";
            }
        }

        //Lights up
        function lightsUp(element) {
            element.style.brightness = "0.95";
        }

        //Lights down
        function lightsDown(element) {
            element.style.brightness = "1";
        }

        function filterFormParams() {
            // Prevent the form from submitting
            link = window.location.href;
            param = '';
            sortedBy = document.querySelector('select[name="sortedBy"]');
            orderBy = document.querySelector('select[name="orderBy"]');
            paginate = document.querySelector('input[name="paginate"]');
            filter = document.querySelectorAll('input[name="filter[]"]');

            if (paginate && paginate.value != '10')(param == '') ? param += '?paginate=' + paginate.value : param +=
                '&paginate=' +
                paginate.value;
            if (orderBy && orderBy.value != '')(param == '') ? param += '?orderBy=' + orderBy.value : param +=
                '&orderBy=' +
                orderBy.value;
            if (sortedBy && sortedBy.value != '')(param == '') ? param += '?sortedBy=' + sortedBy.value : param += '&sortedBy=' +
                sortedBy.value;


            filter_string = 'id;';
            filter.forEach(element => {
                (element.checked) ? (filter_string == 'id;') ? filter_string += element.value: filter_string +=
                    ';' + element.value: null
            });
            (filter_string != 'id;') ? (param == '') ? param += '?filter=' + filter_string: param += '&filter=' +
                filter_string: null;

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

            if ((paramsObject.filter && paramsObject.filter != '') || (paramsObject.sortedBy && paramsObject.sortedBy != '') ||
                (paramsObject.orderBy && paramsObject.orderBy != '') || (paramsObject.paginate && paramsObject
                    .paginate != '10')) {
                legit = true;
            }

            if (legit) {
                document.getElementById('filter-button').classList.add('pulse', 'pulse-dark');
                document.getElementById('clearFilterButton').removeAttribute("hidden");
            }

            return legit;
        }

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

        //Prevent auto-close dropdown
        $('.dropdown-menu').on('click', function(e) {
            e.stopPropagation();
        });
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
    {{-- Filter & Pagination --}}
    <div class="container dropright">
        {{-- Filter button --}}
        <button id="filter-button" class="btn btn-circle btn-icon btn-light-primary btn-hover-primary" type="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="flaticon2-dashboard text-primary"></i>
            <span class="pulse-ring"></span>
        </button>
        <div class="dropdown-menu dropdown-menu border border-info">
            {{-- Filter input --}}
            <form id="filterForm" class="px-8 py-8">
                <div class="form-group row">
                    <label for="paginate" class="col-4 col-form-label">{{-- Per page: --}}Hiển thị tối đa:</label>
                    <div class="col-8">
                        <input class="form-control" type="number" value="{{ request()->paginate ?? '10' }}" name="paginate"
                            min="1" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="orderBy" class="col-4 col-form-label">{{-- Sorted By: --}}Lọc theo:</label>
                    <div class="col-4">
                        <select class="form-control" name="orderBy">
                            <option value="">...</option>
                            <option value="id" @if (request()->orderBy == 'id') {{ 'selected' }} @endif>ID</option>
                            <option value="title_description" @if (request()->orderBy == 'title_description') {{ 'selected' }} @endif>
                                Tựa đề</option>
                            <option value="detail_description" @if (request()->orderBy == 'detail_description') {{ 'selected' }} @endif>
                                Tóm tắt</option>
                            <option value="created_at" @if (request()->orderBy == 'created_at') {{ 'selected' }} @endif>Ngày
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
                        <div class="checkbox-inline">
                          <label class="checkbox checkbox-outline checkbox-success disabled">
                            <input type="checkbox" value="id"
                                checked disabled />
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
                        <span class="form-text text-muted">Chọn các nội dung cần hiện và ẩn những thứ khác</span>
                    </div>
                </div>

                <button id="clearFilterButton" type="button" class="btn btn-secondary" hidden
                    onclick="window.location.replace(location.pathname)">Xóa bộ lọc <span id="numberOfFilters"
                        class="badge badge-info">
                        {{ count(request()->all()) }}</span></button>

                <button type="reset" class="btn btn-light btn-hover-secondary"">Cài lại
                    <i class="flaticon2-refresh-1"></i></button>
                <button type="button" class="btn btn-primary" onclick="filterFormParams(this)">Áp dụng <i
                        class="flaticon2-check-mark icon-nm"></i></button>
            </form>
        </div>

        <div class="example example-basic bg-white">
            <div class="example-preview">
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

                                <div class="timeline-label">
                                    <span id="display_diff_{{ $release->id }}"
                                        class="text-info label label-inline @if ($key % 2 == 0) {{ 'label-light-success' }}@else{{ 'label-light-danger' }} @endif font-weight-bolder"
                                        style="display: -webkit-inline-box"
                                        onclick="toggleDateDisplay({{ $release->id }})" onmouseup="lightsUp(this)"
                                        onmouseout="lightsDown(this)">
                                        {{-- Display time difference (from create till now) --}}
                                        <i class="far fa-clock icon-nm text-info mr-1"></i>
                                        {{ convertTimeToAppropriateFormat(time() - strtotime($release->created_at)) . ' trước' }}
                                    </span>
                                    <span id="display_date_{{ $release->id }}"
                                        class="text-info label label-inline @if ($key % 2 == 0) {{ 'label-light-success' }}@else{{ 'label-light-danger' }} @endif font-weight-bolder"
                                        style="display: none" onclick="toggleDateDisplay({{ $release->id }})"
                                        onmouseup="lightsUp(this)" onmouseout="lightsDown(this)">
                                        {{-- Display create date --}}
                                        <i class="far fa-calendar-alt icon-nm text-info mr-1"></i>
                                        {{ date('d-m-y H:i:s', strtotime($release->created_at)) }}
                                    </span>
                                </div>

                                {{-- Original: <div class="timeline-content max-h-150px overflow-auto" > --}}
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
                                                            <span class="nav-icon"><i
                                                                    class="flaticon2-information"></i></span>
                                                            <span class="nav-text">Tựa đề</span>
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
                                                <div class="tab-pane fade show active"
                                                    id="kt_tab_pane_1_3_{{ $release->id }}" role="tabpanel"
                                                    aria-labelledby="kt_tab_pane_1_3_{{ $release->id }}">
                                                    {{ $release->title_description }}
                                                </div>
                                                {{-- Tab Detail description --}}
                                                <div class="tab-pane fade" id="kt_tab_pane_2_3_{{ $release->id }}"
                                                    role="tabpanel"
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

        {{-- Paginator navigation --}}
        <label for="level"><b>{{ $releases->withQueryString()->onEachSide(2)->links() }}</b></label>
    </div>
@endsection
