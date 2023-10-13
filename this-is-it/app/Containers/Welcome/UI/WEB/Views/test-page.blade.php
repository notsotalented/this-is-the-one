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
        function toggleDateDisplay(element) {
            if ((element.id).includes('difference')) {
                toggleOn = (element.id).replace('difference', 'date');
            } else {
                toggleOn = (element.id).replace('date', 'difference');
            }
            element.style.display = 'none';
            document.getElementById(toggleOn).style.display = 'block';
        }
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

    <div class="example example-basic" style="background-color: ivory">
        <div class="example-preview">
            <div class="timeline timeline-4">
                <div class="timeline-bar"></div>
                <div class="timeline-items">

                    @foreach ($releases as $key => $release)
                        <div
                            class="timeline-item timeline-item-@if ($key % 2 == 0){{ 'left' }}@else{{ 'right' }}@endif"">
                            <!--Style Indicator badge, but can be Icon, Images, ... -->
                            <!--Color code event E.g: Red = Alert, Yellow = Warning, Blue = Information, ...-->
                            <div class="timeline-badge">
                                @if ($release->id % 2 == 0)
                                    <div class="bg-danger"></div>
                                @else
                                    <div class="bg-primary"></div>
                                @endif

                            </div>

                            <div class="timeline-label" id="timeline-label_difference_{{ $release->id }}"
                                style="display: block;" onclick="toggleDateDisplay(this)">
                                <span class="text-info label label-inline label-light-danger font-weight-bolder">
                                    <!--Pick one-->
                                    {{-- {{ $release->created_at->format('d-m-Y H:i:s') }} --}}

                                    <i class="fas fa-hourglass-end fa-sm text-info mr-1"></i>
                                    {{ convertTimeToAppropriateFormat(time() - strtotime($release->created_at)) . ' ago' }}
                                    {{-- {{ $release->created_at->format('H:i A') }} --}}
                                </span>
                            </div>

                            <div class="timeline-label" id="timeline-label_date_{{ $release->id }}" style="display: none;"
                                onclick="toggleDateDisplay(this)">
                                <span class="text-info label label-inline label-light-danger font-weight-bolder">
                                    <!--Pick one-->
                                    {{-- {{ $release->created_at->format('d-m-Y H:i:s') }} --}}

                                    <i class="flaticon2-calendar-9 fa-sm text-info mr-1"></i>
                                    {{ date('H:i A d/m/Y', strtotime($release->created_at)) }}
                                    {{-- {{ $release->created_at->format('H:i A') }} --}}
                                </span>
                            </div>

                            <!-- Original: <div class="timeline-content max-h-150px overflow-auto" > -->
                            <div class="timeline-content max-h-30">
                                <div class="card card-custom card-stretch" id="kt_card_{{ $release->id }}">
                                    <div class="card-header card-header-tabs-line bg-secondary">
                                        <div class="card-title">
                                            <h3 class="card-label">
                                                {{ $release->name }}
                                            </h3>
                                        </div>
                                        <div class="card-toolbar">
                                            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab"
                                                        href="#kt_tab_pane_1_3_{{ $release->id }}">
                                                        <span class="nav-icon"><i class="flaticon2-information"></i></span>
                                                        <span class="nav-text">Tóm tắt</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab"
                                                        href="#kt_tab_pane_2_3_{{ $release->id }}">
                                                        <span class="nav-icon"><i class="flaticon2-list-2"></i></span>
                                                        <span class="nav-text">Chi tiết</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"
                                                        href="#" role="button" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <span class="nav-icon"><i class="flaticon2-indent-dots"></i></span>
                                                        <span class="nav-text">Khác</span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                        <a class="dropdown-item" data-toggle="tab"
                                                            href="#kt_tab_pane_3_3_{{ $release->id }}"><span
                                                                class="nav-text"><i
                                                                    class="flaticon2-photograph mr-2"></i>Ảnh</span></a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('web_releasevuejs_show_detail_release', $release->id) }}"><span
                                                                class="nav-text"><i class="flaticon2-notepad mr-2"></i>Tới
                                                                suộc</span></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content five-lines">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_1_3_{{ $release->id }}"
                                                role="tabpanel" aria-labelledby="kt_tab_pane_1_3_{{ $release->id }}">
                                                {{-- Tab Short description --}}
                                                {{ $release->title_description }}
                                            </div>
                                            <div class="tab-pane fade card-scroll" id="kt_tab_pane_2_3_{{ $release->id }}"
                                                role="tabpanel" aria-labelledby="kt_tab_pane_2_3_{{ $release->id }}">
                                                {{-- Tab Detail description --}}
                                                {!! $release->detail_description !!}
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_3_3_{{ $release->id }}"
                                                role="tabpanel" aria-labelledby="kt_tab_pane_3_3_{{ $release->id }}">
                                                {{-- Tab ...? --}}
                                            </div>
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
@endsection
