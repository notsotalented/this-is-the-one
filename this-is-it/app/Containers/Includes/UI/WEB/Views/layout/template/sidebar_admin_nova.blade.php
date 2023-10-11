@php
    // use \App\Lib\AdminAsideMenu;
    // $menus = new AdminAsideMenu();
    // $menus = $menus->getMenuData();
@endphp
<!-- Logo -->
<div class="logo-nova">
    <a style="float: left" href="/">
        <svg width="35px" height="35px" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin meet"><path d="M0 222.991C0 241.223 14.779 256 33.009 256H222.99C241.223 256 256 241.221 256 222.991V33.01C256 14.777 241.221 0 222.991 0H33.01C14.777 0 0 14.779 0 33.009V222.99z" fill="#563D7C"/><path d="M106.158 113.238V76.985h31.911c3.04 0 5.97.253 8.792.76 2.822.506 5.319 1.41 7.49 2.713 2.17 1.303 3.907 3.112 5.21 5.427 1.302 2.316 1.954 5.283 1.954 8.9 0 6.513-1.954 11.217-5.862 14.111-3.907 2.895-8.9 4.342-14.979 4.342h-34.516zM72.075 50.5v155h75.112c6.947 0 13.713-.868 20.298-2.605 6.585-1.737 12.446-4.414 17.584-8.032 5.137-3.618 9.226-8.286 12.265-14.002 3.04-5.717 4.559-12.483 4.559-20.298 0-9.697-2.352-17.982-7.055-24.856-4.704-6.875-11.832-11.687-21.384-14.437 6.947-3.328 12.194-7.598 15.74-12.808 3.545-5.21 5.318-11.722 5.318-19.538 0-7.236-1.194-13.314-3.582-18.235-2.388-4.92-5.753-8.864-10.095-11.831-4.341-2.967-9.551-5.102-15.63-6.404-6.078-1.303-12.808-1.954-20.189-1.954H72.075zm34.083 128.515v-42.549h37.121c7.381 0 13.315 1.7 17.802 5.102 4.486 3.401 6.73 9.081 6.73 17.041 0 4.053-.688 7.381-2.063 9.986-1.375 2.605-3.22 4.668-5.536 6.187-2.315 1.52-4.993 2.605-8.032 3.257-3.04.65-6.223.976-9.552.976h-36.47z" fill="#FFF"/></svg>
    </a>
</div>
@php

    $menus = [
        [
            'label' => 'Dashboard',
            'children' => [
                [
                    'label' => 'Dashboard',
                    'url' => '/dashboard',
                ],
            ],
        ],
        [
            'label' => 'Users',
            'children' => [
                [
                    'label' => 'Legacy Table',
                    'url' => '/dashboard?table=1',
                ],
                [
                    'label' => 'Users',
                    'url' => '/dashboard?table=2',
                ],
                [
                    'label' => 'Products WIP',
                    'url' => '/dashboard?table=3',
                ],
            ],
        ],
        [
            'label' => 'Roles',
            'children' => [
                [
                    'label' => 'Manage',
                    'url' => '/create-role-page',
                ],
            ],
        ],
        [
            'label' => 'Permissions',
            'children' => [
                [
                    'label' => 'Attach',
                    'url' => '/role-page/attach',
                ],
                [
                    'label' => 'Detach',
                    'url' => '/role-page/detach',
                ],
            ],
        ],
        [
            'label' => 'Release',
            'children' => [
                [
                    'label' => 'Show Release',
                    'url' => '/releases',
                ],
                [
                    'label' => 'Create Release',
                    'url' => '/releases/new',
                ],
            ],
        ],
    ];

    //Get current url
    //$url = $_SERVER['REQUEST_URI'];
    //$menus = findelement($menus, $url);


@endphp
<!-- End Logo -->
<div class="nova-menu">
    @foreach ($menus as $menu)
        <div class="top-level">
            <h3 class="nova-text">
                <div class="nova-icon">
                    <svg class="img1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="theme-icon">
                    <div class="text-nova">
                        <span>{{ $menu['label'] ?? '' }}</span>
                        <div class="nova1">
                            <div class="nova1">
                                <svg class="box plus" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M13 11h2c.55 0 1 .45 1 1s-.45 1-1 1h-2v2c0 .55-.45 1-1 1s-1-.45-1-1v-2H9c-.55 0-1-.45-1-1s.45-1 1-1h2V9c0-.55.45-1 1-1s1 .45 1 1v2z">
                                    </path>
                                </svg>
                                <svg class="box minus" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M16 12c0 .55-.45 1-1 1H9c-.55 0-1-.45-1-1s.45-1 1-1h6c.55 0 1 .45 1 1z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
            </h3>
            <!-- Danh sách ul -->
            <ul class="nova1-list">
                @foreach ($menu['children'] ?? [] as $child)
                <li class="leading1">
                    <div>
                        <a href="{{ $child['url'] ?? '#' }}" target="_self">
                            <div class="items-center" style="width: 100%">
                                <span class="text" style="width: 20%; display: inline-block">
                                    @if(strpos(request()->fullUrl(), $child['url']) !== false)
                                        {!! '<i class="fa fa-eye" aria-hidden="true"></i>' !!}
                                    @endif
                                </span>
                                <span class="text"
                                    style="width: 80%; display: inline-block; @if(strpos(request()->fullUrl(), $child['url']) !== false) {{ 'font-weight: bold; font-style: italic' }} @endif">
                                    {{ $child['label'] ?? '' }}
                                </span>
                                <i class="fa-solid fa-gear fa-spin-pulse"></i>
                            </div>
                        </a>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
    @endforeach
</div>
