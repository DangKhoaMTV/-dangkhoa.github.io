<!DOCTYPE html>
<html lang="en">

<head>
    <!--Start of Google Analytics script-->
    @if ($bs->is_analytics == 1)
        {!! $bs->google_analytics_script !!}
    @endif
    <!--End of Google Analytics script-->

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="@yield('meta-description')">
    <meta name="keywords" content="@yield('meta-keywords')">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $bs->website_title }} @yield('pagename')</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/front/img/' . $bs->favicon) }}" type="image/x-icon">

    <!--Font-->
    <link rel="preconnect" href="https://static.3tc.vn" crossorigin>
    <link type="text/css"
        href="https://static.3tc.vn/css2?family=Lora:wght@400;500;700&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-material-design-icons/css/material-icons.css') }}">
    <!-- plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/ficon.css') }}">

    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/protocol-style.css') }}?v=0527">

    <!-- protocol css -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/protocol.css') }}?v=0527">

    <!-- common css -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/common-style.css') }}?v=0527">
    @yield('styles')

    @if ($bs->is_tawkto == 1 || $bex->is_whatsapp == 1)
        <style>
            .back-to-top.show {
                right: auto;
                left: 20px;
            }
        </style>
    @endif
    @if (count($langs) == 0)
        <style media="screen">
            .support-bar-area ul.social-links li:last-child {
                margin-right: 0px;
            }

            .support-bar-area ul.social-links::after {
                display: none;
            }
        </style>
    @endif


    <!-- responsive css -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/protocol-responsive.css') }}?v=0527">
    <!-- common base color change -->
    <link href="{{ url('/') }}/assets/front/css/common-base-color.php?color={{ $bs->base_color }}"
        rel="stylesheet">
    <!-- base color change -->
    <link
        href="{{ url('/') }}/assets/front/css/protocol-base-color.php?color={{ $bs->base_color }}{{ $be->theme_version != 'dark' ? '&color1=' . $bs->secondary_base_color : '' }}"
        rel="stylesheet">

    @if ($be->theme_version == 'dark')
        <!-- dark version css -->
        <link rel="stylesheet" href="{{ asset('assets/front/css/dark.css') }}">
        <!-- dark version base color change -->
        <link href="{{ url('/') }}/assets/front/css/dark-base-color.php?color={{ $bs->base_color }}"
            rel="stylesheet">
    @endif

    @if ($rtl == 1)
        <!-- RTL css -->
        <link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/front/css/pb-rtl.css') }}">
    @endif
    <!-- jquery js -->
    <script src="{{ asset('assets/front/js/jquery-3.3.1.min.js') }}"></script>

    @if ($bs->is_appzi == 1)
        <!-- Start of Appzi Feedback Script -->
        <script async src="https://app.appzi.io/bootstrap/bundle.js?token={{ $bs->appzi_token }}"></script>
        <!-- End of Appzi Feedback Script -->
    @endif

    <!-- Start of Facebook Pixel Code -->
    @if ($be->is_facebook_pexel == 1)
        {!! $be->facebook_pexel_script !!}
    @endif
    <!-- End of Facebook Pixel Code -->

    <!--Start of Appzi script-->
    @if ($bs->is_appzi == 1)
        {!! $bs->appzi_script !!}
    @endif
    <!--End of Appzi script-->
</head>



<body @if ($rtl == 1) dir="rtl" @endif>

    <!--   header area start   -->
    <!--   Gọi chạy header (start)  -->
    <div class="header-area header-absolute @yield('no-breadcrumb') transparent_header">
        <div class="container">
            @includeIf('front.protocol.partials.navbar')
        </div>
    </div>
    <!--   Gọi chạy header (end)  -->
    <!--   Code header (start)  -->
    {{-- <header id="header" class="main_header ">
        <a href="{{route('front.index')}}"><img class="lazy" data-src="{{asset('assets/front/img/'.$bs->logo)}}" alt=""></a>
        <p id="logo"><a href="{{ route('front.index') }}" target="_top">SUPREMA</a></p>
        <div id="gnb_container">
            <ul class="gnb">
                <li class="label"><a href="/en/hardware/product.asp"><span>PRODUCTS</span></a>
                    <!-- 2단메뉴 -->
                    <div class="sn_container">
                        <div class="sn_inner gnb_product">
                            <dl class="sn1_1">
                                <dt>PLATFORM</dt>
                                <dd><a href="/en/platform/biostar-2.asp">BioStar 2 Overview</a></dd>
                                <dd><a href="/en/platform/biostar-2-centralized-system.asp">BioStar 2 AC (Centralized
                                        System)</a></dd>
                                <dd><a href="/en/platform/biostar-2-distributed-system.asp">BioStar 2 AC (Distributed
                                        System)</a></dd>
                                <dd><a href="/en/platform/biostar-2-ta.asp">BioStar 2 TA</a></dd> --}}
                                <!--dd><a href="/en/platform/biostar-2-mobile.asp">BioStar 2 Mobile</a></!--dd-->
                                {{-- <dd><a href="/en/platform/suprema-mobile-access.asp">Suprema Mobile Access</a></dd>
                            </dl>
                            <span class="line line01"></span>
                            <dl class="sn1_2">
                                <dt>HARDWARE</dt>

                                <dd class="product_img" style="display: block;"><img
                                        src="/en/asset/images/common/lnb_prod_default.jpg" alt="Suprema Product"></dd>

                                <dd>
                                    <a href="/en/hardware/product_biometric.asp" class="sn_dep2">Biometric Readers</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3">
                                        <ul>
                                            <li><a href="/en/hardware/fusion-multimodal-terminal-facestation-f2.asp">FaceStation
                                                    F2</a></li>
                                            <li><a href="/en/hardware/bio_facestation2.asp">FaceStation 2</a></li>
                                            <li><a
                                                    href="/en/hardware/compact-face-recognition-terminal-facelite.asp">FaceLite</a>
                                            </li>
                                            <li><a href="/en/hardware/bio_bioentry-p2.asp">BioEntry P2</a></li>
                                            <li><a href="/en/hardware/bio_bioentry-r2.asp">BioEntry R2</a></li>
                                            <li><a href="/en/hardware/bio_biolite-n2.asp">BioLite N2</a></li>
                                        </ul>
                                        <ul>
                                            <li><a href="/en/hardware/bio_bioentry-w2.asp">BioEntry W2</a></li>
                                            <li><a href="/en/hardware/bio_biostation-a2.asp">BioStation A2</a></li>
                                            <li><a href="/en/hardware/bio_biostation-2.asp">BioStation 2</a></li>
                                            <li><a href="/en/hardware/bio_biostation-l2.asp">BioStation L2</a></li>
                                            <li><a href="/en/hardware/versatile-intelligent-terminal-xstation2.asp">X-Station
                                                    2</a></li>
                                        </ul>
                                    </div>
                                </dd>
                                <dd>
                                    <a href="/en/hardware/product_rfid.asp" class="sn_dep2">RF/ Mobile Readers</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3">
                                        <ul>
                                            <li><a href="/en/hardware/versatile-intelligent-terminal-xstation2.asp">X-Station
                                                    2</a></li>
                                            <li><a href="/en/hardware/rfid_xpass_2.asp">XPass 2</a></li> --}}
                                            <!-- <li><a href="/en/hardware/rfid_xpass.asp">XPass</a></li> -->
                                            {{-- <li><a href="/en/hardware/rfid_xpass-s2.asp">XPass S2</a></li>
                                            <li><a href="/en/hardware/rfid_xpass-d2.asp">XPass D2</a></li>
                                            <li><a href="/en/hardware/rf-mobile-readers-airfob-patch.asp">Airfob
                                                    Patch</a></li>
                                        </ul>
                                    </div>
                                </dd>
                                <dd>
                                    <a href="/en/hardware/product_controller.asp" class="sn_dep2">Intelligent
                                        Controller</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3">
                                        <ul>
                                            <li><a href="/en/hardware/ic_corestation.asp">CoreStation</a></li>
                                            <li><a href="/en/hardware/ic_corestation-4-door-access-control-kit.asp">4
                                                    Door Kit</a></li>
                                            <li><a href="/en/hardware/pd_encr-10.asp">Enclosure</a></li>
                                        </ul>
                                    </div>
                                </dd> --}}
                                <!--intelligent-controller-->
                                {{-- <dd>
                                    <a href="/en/hardware/product_openplatform.asp" class="sn_dep2">Open Platform</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3">
                                        <ul> --}}
                                            <!-- <li><a href="/en/hardware/open-platform-novus.asp">NOVUS</a></li> delete 22-0511 jwyu -->
                                            {{-- <li><a href="/en/hardware/open-platform-omnis.asp">OMNIS</a></li>
                                        </ul>
                                    </div>
                                </dd><!-- open platform --> --}}

                                {{-- <dd>
                                    <a href="/en/hardware/product_peripheral.asp" class="sn_dep2">Peripherals</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3">
                                        <ul>
                                            <li><a href="/en/hardware/suprema-thermal-camera.asp">Thermal Camera</a>
                                            </li>
                                            <li><a href="/en/hardware/multiple-Input-extension-module.asp">Input
                                                    Module</a></li>
                                            <li><a href="/en/hardware/pd_om-120.asp">Output Module</a></li>
                                            <li><a href="/en/hardware/pd_dm-20.asp">Door Module</a></li>
                                            <li><a href="/en/hardware/pd_secure-io-2.asp">Secure Module</a></li>
                                        </ul>
                                    </div>
                                </dd> --}}
                                <!--peripheral-device-->

                                <!-- 21-10-26 add -->
                                {{-- <dd>
                                    <a href="/embedded-modules/en/main.asp" class="sn_dep2" target="_blank">OEM
                                        Fingerprint Modules</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3 lnb_finger">
                                        <a href="/embedded-modules/en/main.asp" target="_blank"
                                            title="OEM Fingerprint Modules"><span class="btn-primary-line">Learn
                                                More</span></a>
                                    </div>
                                </dd><!-- OEM Fingerprint Modules --> --}}

                                {{-- <dd>
                                    <a href="/en/hardware/eol_notice-list.asp" class="sn_dep2">Discontinued
                                        Products</a> --}}
                                    <!-- 3단메뉴 -->
                                    {{-- <div class="sn_dep3">
                                        <ul>
                                            <li><a href="/en/hardware/eol_biostar-1.asp">BioStar 1</a></li>
                                            <li><a href="/en/hardware/eol_facestation.asp">FaceStation</a></li>
                                            <li><a href="/en/hardware/eol_biostation.asp">BioStation</a></li>
                                            <li><a href="/en/hardware/eol_biostation-t2.asp">BioStation T2</a></li>
                                            <li><a href="/en/hardware/eol_biolite-net.asp">BioLite Net</a></li>
                                            <li><a href="/en/hardware/eol_bioentry-w.asp">BioEntry W</a></li>
                                        </ul>
                                        <ul>
                                            <li><a href="/en/hardware/eol_bioentry-plus.asp">BioEntry Plus</a></li>
                                            <li><a href="/en/hardware/eol_biolite-solo.asp">BioLite Solo</a></li>
                                            <li><a href="/en/hardware/rfid_x-station.asp">X-Station</a></li>
                                            <li><a href="/en/hardware/rfid_xpass.asp">XPass</a></li>
                                            <li><a href="/en/hardware/eol_secure-io.asp">Secure I/O</a></li>
                                            <li><a href="/en/hardware/eol_lift-io.asp">Lift I/O</a></li>
                                        </ul>
                                    </div>
                                </dd> --}}
                                <!--end-of-line-->
                            {{-- </dl> --}}

                            <!-- <span class="line line02"></span> -->

                            {{-- <dl class="sn1_3 main">
                                <dd class="product_img">
                                    <a href="/en/hardware/product_selector.asp" title="Product Selector">
                                        <h5>Product Selector</h5>
                                        <p>Compare products to find your match</p>
                                        <span class="btn-primary-line">More</span>
                                    </a>
                                </dd>
                            </dl>
                        </div> --}}
                        <!-- <a href="/en/hardware/product_selector.asp" class="shorcut"><span>Product Selector&nbsp;&nbsp;<img src="/en/asset/images/common/icon-arrow-right7.png" alt="" /></span></a> -->
                    {{-- </div>
                </li>
                <li class="label"><a href="/en/solutions/main.asp"><span>SOLUTIONS</span></a> --}}
                    <!-- 2단메뉴 -->
                    {{-- <div class="sn_container">
                        <div class="sn_inner sn_inner_solutions">
                            <dl class="sn1_1">
                                <dt>INDUSTRIES</dt>
                                <dd><a href="/en/solutions/construction.asp">Construction</a></dd>
                                <dd><a href="/en/solutions/data-center.asp">Data Center</a></dd>
                                <dd><a href="/en/solutions/healthcare.asp">Healthcare</a></dd>
                                <dd><a href="/en/solutions/commercial.asp">Commercial</a></dd>
                                <dd><a href="/en/solutions/infrastructure.asp">Infrastructure</a></dd>
                                <dd><a href="/en/solutions/manufacturing.asp">Manufacturing</a></dd>
                            </dl>

                            <dl class="sn1_2">
                                <dt>APPLICATIONS</dt>
                                <dd><a href="/en/solutions/access-control.asp">Access Control</a></dd>
                                <dd><a href="/en/solutions/time-attendance.asp">Time Attendance</a></dd>
                                <dd><a href="/en/solutions/facial-recognition.asp">Facial Recognition</a></dd>
                                <dd><a href="/en/solutions/biosign.asp">Mobile Fingerprint Algorithm (BioSign)</a></dd>
                                <dd><a href="/en/solutions/cybersecurity.asp">Cybersecurity</a></dd>
                            </dl>
                            <dl class="sn1_3">
                                <dt>INTEGRATION</dt>
                                <dd><a href="/en/solutions/suprema-integration.asp">Suprema Integration</a></dd>
                                <dd><a href="/en/solutions/all_solution.asp">Integration Partners</a></dd>
                            </dl>
                            <div class="sn1_4">
                                <img src="/en/asset/images/main/menu_solution_03.jpg" alt="">
                                <a href="/en/solutions/case-study.asp">Case Study</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="label"><a href="/en/support/main.asp"><span>SUPPORT</span></a> --}}
                    <!-- 2단메뉴 -->
                    {{-- <div class="sn_container">
                        <div class="sn_inner"> --}}
                            <!-- <dl class="solution_sn">
                                   <dt><a href="/en/about/contact-us.asp">Contact Us</a></dt>
                            </dl> -->
                            {{-- <dl class="solution_sn">
                                <dt>Technical Resources</dt>
                                <dd><a href="/en/support/biostar-2-package.asp" target="_blank">BioStar 2 Package</a>
                                </dd>
                                <dd><a href="/en/support/technical-resources.asp?sKIND_TYPE=CM00301">Firmware</a></dd>
                                <dd><a href="/en/support/technical-resources.asp?sKIND_TYPE=CM00302">Manual</a></dd> --}}
                                <!-- <dd><a href="/en/support/technical-resources.asp?sKIND_TYPE=CM00303">Certificate</a></dd> -->
                            {{-- </dl>
                            <dl class="solution_sn">
                                <dt>Marketing Materials</dt>
                                <dd><a href="/en/support/marketing-materials.asp?sKIND_TYPE=CM00401">Brand
                                        Guidelines</a></dd>
                                <dd><a href="/en/support/marketing-materials.asp?sKIND_TYPE=CM00402">Brochures</a></dd>
                                <dd><a href="/en/support/marketing-materials.asp?sKIND_TYPE=CM00403">Product Images</a>
                                </dd> --}}
                                <!-- <dd><a href="/en/support/marketing-materials.asp?sKIND_TYPE=CM00404">Exhibition Designs</a></dd> -->
                                {{-- <dd><a href="/en/support/marketing-materials.asp?sKIND_TYPE=CM00405">Wallpapers</a>
                                </dd>
                                <dd><a href="/en/support/marketing-materials.asp?sKIND_TYPE=CM00406">Case Study</a>
                                </dd>
                            </dl>
                            <dl class="solution_sn">
                                <dt>Development Tools</dt>
                                <dd><a href="/en/support/development-tools_biostar-2-api.asp">BioStar 2 API</a></dd>
                                <dd><a href="/en/support/development-tools_suprema-g-sdk.asp">Suprema G-SDK</a></dd>
                                <dd><a href="/en/support/development-tools_biostar-2-sdk.asp">BioStar 2 Device SDK</a>
                                </dd>
                                <dd><a href="/en/support/development-tools_svp-android-sdk.asp">SVP Android SDK</a>
                                </dd>
                            </dl>
                            <dl class="solution_sn">
                                <dt>Learning</dt>
                                <dd><a href="https://kb.supremainc.com/home/doku.php?id=en:start"
                                        target="_blank">Knowledge Base</a></dd>
                                <dd><a href="https://support.supremainc.com/" target="_blank">Support Portal</a></dd>
                            </dl>
                        </div>
                    </div>
                </li>
                <li class="label"><a href="/en/about/suprema.asp"><span>ABOUT</span></a> --}}
                    <!-- 2단메뉴 -->
                    {{-- <div class="sn_container">
                        <div class="sn_inner_suprema">
                            <dl class="sn1_1">
                                <dt><a href="/en/about/suprema.asp">ABOUT</a></dt>
                                <dd><a href="/en/about/suprema.asp">Who We Are</a></dd>
                                <dd><a href="/en/about/customer-value.asp">Why Suprema?</a></dd>
                                <dd><a href="/en/about/news-list.asp?News_Type=Releases">Press Releases</a></dd> --}}
                                <!-- <dd><a href="/en/about/news-list.asp?News_Type=Articles">Blogs/Articles</a></dd> -->
                                <!-- <dd><a href="/en/about/event-list.asp">Events</a></dd> -->
                                {{-- <dd><a href="/en/about/contact-us.asp">Contact Us</a></dd>
                                <dd><a href="/en/about/global-office.asp">Global Offices</a></dd>
                            </dl>
                            <img src="https://supremainc.com/ko/asset/images/main/bg_suprema.jpg?v210122"
                                alt="Global Leading Provider of Security Solutions" class="suprema_img"> --}}
                            <!-- <span class="line"></span>
                            <dl class="sn1_2">
                                <dt>IR</dt>
                                <dd class="product_img"><a href="#n"><img src="/en/asset/images/common/sn_img_02.jpg" alt="" /></a></dd>
                                <dd><a href="#n">IR 자료</a></dd>
                                <dd><a href="#n">공시</a></dd>
                                <dd><a href="#n">공고</a></dd>
                                <dd><a href="#n">재무정보</a></dd>
                            </dl> -->
                        {{-- </div> --}}
                        <!-- <a href="#nl" class="shorcut"><span>Product Selector</span></a> -->
                    {{-- </div>
                </li>
                <li class="label"><a href="/en/hub/hub.asp"><span class="ico-hub"><i
                                class="material-icons">device_hub</i>HUB</span></a> --}}
                    <!-- 2단메뉴 -->
                    {{-- <div class="sn_container">
                        <div class="sn_inner_suprema">
                            <dl class="sn1_1">
                                <dt><a href="/en/hub/hub.asp">HUB</a></dt>
                                <dd><a href="/en/hub/insights-main.asp">Insights</a></dd>
                                <dd><a href="/en/hub/news-list.asp?News_Type=Articles">Blogs &amp; Articles</a></dd>
                                <dd><a href="/connect2020/html/main.asp" target="_blank">Suprema Connect</a></dd>
                                <dd><a href="/en/hub/case-study.asp">Customer Stories</a></dd>
                                <dd><a href="https://kb.supremainc.com/home/doku.php?id=en:start"
                                        target="_blank">Knowledge Base</a></dd>
                                <dd><a href="https://support.supremainc.com" target="_blank">Support Portal</a></dd>
                            </dl>
                            <a href="/en/hub/hub.asp"><img
                                    src="https://supremainc.com/en/asset/images/06_hub/hub-banner.jpg" alt=""
                                    class="suprema_img"></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div id="top_odd">
            <a href="/en/wheretobuy/list.asp">WHERE TO BUY&nbsp;&nbsp;&nbsp;<img
                    src="/en/asset/images/common/header_bar.png" alt=""></a>
            <div class="selectBox">
                <span class="txt"><span class="material-icons">language</span>EN</span>
                <label for="search" class="screen_out">검색분류선택</label>
                <ul id="search" class="select">
                    <li><a href="/en/main.asp?">Global/English</a></li>
                    <li><a href="/ko/main.asp?">Korea/한국어</a></li>
                    <li><a href="/de/main.asp?">German/Deutsch</a></li>
                    <li><a href="/fr/main.asp?">France/français</a></li>
                    <li><a href="/es/main.asp?">Latam/español</a></li>
                    <li><a href="/jp/main.asp?">Japan/日本語</a></li>

                </ul>
            </div>
        </div>
        <div id="side_menu_container">
            <form name="frmTopSearch" action="/en/util/search.asp" method="post" style="margin:0px;">
                <div class="all_search_container">
                    <input type="text" name="sKeyword" value="" placeholder="Search here"
                        style="width: 420px; padding: 0px 25px;">
                    <a href="#n" class="btn_all_search"></a>
                    <a href="#n" onclick="document.frmTopSearch.submit();" class="on"></a>
                    <a href="#n" class="btn_search_close"></a>
                </div>
            </form>
            <a href="/en/about/contact-us.asp" class="btn_em_mode">CONTACT US</a>
            <a href="/en/about/contact-us.asp" class="btn_em_mode_t">CONTACT US</a>
        </div>
    </header> --}}
    <!--   Code header (end)  -->
    <!--   header area end   -->


    @if (!request()->routeIs(app()->getLocale() . '.front.index') &&
        !request()->routeIs('vi.front.index') &&
        !request()->routeIs('en.front.index') &&
        !request()->routeIs('front.packageorder.confirmation') &&
        !request()->routeIs(app()->getLocale() . '.front.gallery') &&
        !request()->routeIs(app()->getLocale() . '.front.special_offers') &&
        !request()->routeIs(app()->getLocale() . '.front.dinning_menu') &&
        !request()->routeIs(app()->getLocale() . '.front.services'))
        <!--   breadcrumb area start   -->
        <div class="breadcrumb-area cases lazy" data-bg="{{ asset('assets/front/img/' . $bs->breadcrumb) }}"
            style="background-size:cover;">
            <div class="container">
                <div class="breadcrumb-txt">
                    <div class="row">
                        <div class="col-xl-7 col-lg-8 col-sm-10">
                            <span>@yield('breadcrumb-title')</span>
                            <h1>@yield('breadcrumb-subtitle')</h1>
                            <ul class="breadcumb">
                                <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                                <li>@yield('breadcrumb-link')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area-overlay"
                style="background-color: #{{ $be->breadcrumb_overlay_color }};opacity: {{ $be->breadcrumb_overlay_opacity }};">
            </div>
        </div>
        <!--   breadcrumb area end    -->
    @endif


    @if (request()->routeIs(app()->getLocale() . '.front.gallery'))
        <!--   breadcrumb area start   -->
        <div class="breadcrumb-area cases lazy breadcrumb-gallery-area"
            data-bg="{{ asset('assets/front/img/gallery/' . $bex->gallery_category_bg) }}"
            style="background-size:cover;background-repeat: no-repeat;
    background-position: center;">
            <div class="container">
                <div class="breadcrumb-txt">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="breadcrumb-title hl-lora-48 text-white">@yield('breadcrumb-link')</h3>
                            <div class="breadcrumb-btn">
                                <a class="btn-protocol-transparent btn-160 text-white gallery-breadcrumb-btn scroll-section"
                                    href="#masonry-gallery">{{ __('gallery-section-image-text') }}</a>
                                <a class="btn-protocol-transparent btn-160 text-white gallery-breadcrumb-btn scroll-section"
                                    href="#video-area-section">{{ __('gallery-section-video-text') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area-overlay"
                style="background-color: #{{ $be->breadcrumb_overlay_color }};opacity: {{ $be->breadcrumb_overlay_opacity }};">
            </div>
        </div>
        <!--   breadcrumb area end    -->
    @endif

    @if (request()->routeIs(app()->getLocale() . '.front.services'))
        <!--   breadcrumb area start   -->
        <div class="breadcrumb-area cases lazy breadcrumb-gallery-area"
            data-bg="{{ asset('assets/front/img/services/' . $bex->service_page_bg_image) }}"
            style="background-size:cover;background-repeat: no-repeat;
    background-position: center;">
            <div class="container">
                <div class="breadcrumb-txt">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="breadcrumb-title hl-lora-48 text-white">@yield('breadcrumb-link')</h3>
                            <div class="breadcrumb-btn">
                                <a class="btn-protocol-transparent btn-160 text-white service-breadcrumb-btn scroll-section"
                                    href="#indoor_section">{{ __('indoor-service') }}</a>
                                <a class="btn-protocol-transparent btn-160 text-white service-breadcrumb-btn scroll-section"
                                    href="#outdoor_section">{{ __('outdoor-service') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area-overlay"
                style="background-color: #{{ $be->breadcrumb_overlay_color }};opacity: {{ $be->breadcrumb_overlay_opacity }};">
            </div>
        </div>
        <!--   breadcrumb area end    -->
    @endif

    @if (request()->routeIs(app()->getLocale() . '.front.dinning_menu'))
        <!--   breadcrumb area start   -->
        <div class="breadcrumb-dinning-menu-area section-mg-menu">
            <div class="container px-0">
                <div class="dinning-breadcumb">
                    <a href="{{ route('front.dinning') }}">{{ __('Dinning') }}</a>
                    <img src="{{ asset('assets/front/img/icon/keyboard_arrow_right.svg') }}" alt="breadcumb icon" />
                    <span>@yield('breadcrumb-link')</span>
                </div>
            </div>
        </div>
        <!--   breadcrumb area end    -->
    @endif


    @yield('content')


    <!--    footer section start   -->
    {{-- <footer class="footer-section section-pt-40">
         <div class="container">
            @if (!($bex->home_page_pagebuilder == 0 && $bs->top_footer_section == 0))
            <div class="top-footer-section">
                <div class="footer-logo-wrapper">
                    <a href="{{route('front.index')}}">
                        <img class="lazy" data-src="{{asset('assets/front/img/'.$bs->footer_logo)}}" alt="">
                    </a>
                    <div class="footer-logo-line"></div>
                </div>
                <div class="footer-links">
                    <ul class="footer-link">
                        @foreach ($ulinks as $key => $ulink)
                            @if ($ulink->type == 'popup')
                                <li><a data-toggle="modal" href="#ulinkModal{{$ulink->id}}">{{convertUtf8($ulink->name)}}</a></li>
                            @else
                                <li><a href="{{$ulink->url}}">{{convertUtf8($ulink->name)}}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="footer-contact-info">
                    <ul>
                        <li>
                            @php
                                $addresses = explode(PHP_EOL, $bex->contact_addresses);
                            @endphp
                            @foreach ($addresses as $address)
                                {{$address}}
                                @if (!$loop->last)
                                    |
                                @endif
                            @endforeach
                        </li>
                        <li>
                            @php
                                $mails = explode(',', $bex->contact_mails);
                            @endphp

                            @foreach ($mails as $mail)
                                <a href="mailto:{{$mail}}">{{$mail}}</a>
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </li>
                        <li>
                            @php
                                $phones = explode(',', $bex->contact_numbers);
                            @endphp

                            @foreach ($phones as $phone)
                                <a href="tel:{{$phone}}">{{$phone}}</a>
                                @if (!$loop->last)
                                    /
                                @endif
                            @endforeach

                        </li>
                    </ul>
                </div>

                <div class="footer-social-links">
                    @if (!empty($socials))
                        <ul class="social-link">
                            @foreach ($socials as $key => $social)
                                <li><a target="_blank" href="{{$social->url}}"><i class="{{$social->icon}}"></i></a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            @endif

            @if (!($bex->home_page_pagebuilder == 0 && $bs->copyright_section == 0))
            <div class="copyright-section">
               <div class="row">
                  <div class="col-sm-12 text-center">
                     {!! replaceBaseUrl(convertUtf8($bs->copyright_text)) !!}
                  </div>
               </div>
            </div>
            @endif
         </div>
      </footer> --}}
    <footer id="footer">
        <div class="w_foot">
            <div class="gnb_map">
                <div class="gnb">
                    <span class="tit">PRODUCTS</span>
                    <span class="tit_s">PLATFORM</span>
                    <ul>
                        <li><a href="/en/platform/biostar-2.asp">BioStar 2 Overview</a></li>
                        <li><a href="/en/platform/biostar-2-centralized-system.asp">BioStar 2 AC (Centralized
                                System)</a></li>
                        <li><a href="/en/platform/biostar-2-distributed-system.asp">BioStar 2 AC (Distributed
                                System)</a></li>
                        <li><a href="/en/platform/biostar-2-ta.asp">BioStar 2 TA</a></li>
                        <!--li><a href="/en/platform/biostar-2-mobile.asp">BioStar 2 Mobile</a></!--li-->
                        <li><a href="/en/platform/suprema-mobile-credential.asp">Suprema Mobile Access</a></li>
                    </ul>
                    <span class="tit_s">HARDWARE</span>
                    <ul>
                        <li><a href="/en/hardware/product.asp?iCTG_No=1">Biometric Readers</a></li>
                        <li><a href="/en/hardware/product.asp?iCTG_No=2">RF/ Mobile Readers</a></li>
                        <li><a href="/en/hardware/product.asp?iCTG_No=3">Intelligent Controller</a></li>
                        <li><a href="/en/hardware/product.asp?iCTG_No=4">Open Platform</a></li>
                        <li><a href="/en/hardware/product.asp?iCTG_No=5">Peripherals</a></li>
                        <li><a href="/embedded-modules/en/main.asp" target="_blank">OEM Fingerprint Modules</a></li>
                        <li><a href="/en/hardware/eol_notice-list.asp">Discontinued Products</a></li>
                    </ul>
                </div>
                <div class="gnb">
                    <span class="tit">SOLUTIONS</span>
                    <span class="tit_s">INDUSTRIES</span>
                    <ul>
                        <li><a href="/en/solutions/construction.asp">Construction</a></li>
                        <li><a href="/en/solutions/data-center.asp">Data Center</a></li>
                        <li><a href="/en/solutions/healthcare.asp">Healthcare</a></li>
                        <li><a href="/en/solutions/commercial.asp">Commercial</a></li>
                        <li><a href="/en/solutions/infrastructure.asp">Infrastructure</a></li>
                        <li><a href="/en/solutions/manufacturing.asp">Manufacturing</a></li>
                    </ul>
                    <span class="tit_s">APPLICATIONS</span>
                    <ul>
                        <li><a href="/en/solutions/access-control.asp">Access Control</a></li>
                        <li><a href="/en/solutions/time-attendance.asp">Time &amp; Attendance</a></li>
                        <!-- <li><a href="/en/solutions/mobile-credential.asp">Mobile Credential</a></li> -->
                        <li><a href="/en/solutions/facial-recognition.asp">Facial Recognition</a></li>
                        <li><a href="/en/solutions/biosign.asp">Mobile Fingerprint Algorithm (BioSign)</a></li>
                        <li><a href="/en/solutions/privacy-protection.asp">Cybersecurity</a></li>
                    </ul>
                </div>
                <div class="gnb">
                    <span class="tit">SUPPORT</span>
                    <ul>
                        <li><a href="/en/support/technical-resources.asp">Technical Resources</a></li>
                        <li><a href="/en/support/marketing-materials.asp">Marketing Materials</a></li>
                        <li><a href="/en/support/development-tools_biostar-2-api.asp">Development Tools</a></li>
                        <li><a href="http://kb.supremainc.com/home/doku.php?id=ko:start">Learning</a></li>
                    </ul>
                </div>
                <div class="gnb">
                    <span class="tit">ABOUT</span>
                    <ul>
                        <li><a href="/en/about/suprema.asp">Who We Are</a></li>
                        <li><a href="/en/about/customer-value.asp">Why Suprema?</a></li>
                        <li><a href="/en/about/news-list.asp?News_Type=Releases">Press Releases</a></li>
                        <!-- <li><a href="/en/about/event-list.asp">Events</a></li> -->
                        <li><a href="/en/about/contact-us.asp">Contact Us</a></li>
                        <li><a href="/en/about/global-office.asp">Global Offices</a></li>
                    </ul>
                </div>
                <div class="gnb">
                    <span class="tit">HUB</span>
                    <ul>
                        <li><a href="/en/hub/insights-main.asp">Insights</a></li>
                        <li><a href="/en/hub/news-list.asp?News_Type=Articles">Blogs &amp; Articles</a></li>

                        <li><a href="/connect/index.asp" target="_blank">Suprema Connect</a></li>
                        <li><a href="/en/hub/case-study.asp">Customer Stories</a></li>

                        <li><a href="https://kb.supremainc.com/home/doku.php?id=en:start" target="_blank">Knowledge
                                Base</a></li>
                        <li><a href="https://support.supremainc.com/en/support/home" target="_blank">Support
                                Portal</a></li>
                    </ul>
                </div>
                <div class="gnb">
                    <span class="tit">WHERE TO BUY</span>
                    <ul>
                        <li><a href="/en/wheretobuy/list.asp">Find a Channel Partner</a></li>
                        <li><a href="/en/wheretobuy/become-a-channel-partner-gate.asp">Become a Channel Partner</a>
                        </li>
                        <li><a href="/en/wheretobuy/become-a-technical-partner-gate.asp">Become a Technical Partner</a>
                        </li>
                        <li><a href="/embedded-modules/en/util/contact-us.asp">SFM Customer</a></li>
                    </ul>
                </div>
            </div>
            <div class="foot_odd">
                <div class="terms">
                    <ul>
                        <li><a href="/en/util/privacy-policy.asp">Privacy Policy</a></li>
                        <li><a href="/en/util/email-reject.asp">Cookie Policy</a></li>
                        <li><a href="/en/util/legal-notice.asp">Legal</a></li>
                        <li><a href="/en/util/code-of-conduct.asp">Code of Conduct</a></li>
                    </ul>
                    <div class="f_site">
                        <span class="txt">Family Site</span>
                        <label for="search" class="screen_out">검색분류선택</label>
                        <ul id="search" class="select">
                            <li><a href="/embedded-modules/en/main.asp" target="_blank">OEM Fingerprint Modules</a>
                            </li>
                            <li><a href="https://www.suprema-id.com" target="_blank">SUPREMA ID</a></li>
                            <li><a href="https://mocainc.com/" target="_blank">MOCA system</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copy_area">
                <div class="copy">
                    <img src="https://supremainc.com/en/asset/images/common/foot_logo.png" alt="">
                    <span>Copyright © Suprema Inc.
                        All rights reserved.</span>
                </div>
                <div class="sns">
                    <ul>
                        <li><a href="https://www.linkedin.com/company/suprema-inc-/" target="_blank"><img
                                    src="/en/asset/images/common/foot_sns_01.png" alt=""></a></li>
                        <li><a href="https://www.facebook.com/supremaglobal" target="_blank"><img
                                    src="/en/asset/images/common/foot_sns_02.png" alt=""></a></li>
                        <li><a href="https://www.youtube.com/channel/UCAKt69FsAZFHEZWBWcM6c5g" target="_blank"><img
                                    src="/en/asset/images/common/foot_sns_04.png" alt=""></a></li>
                    </ul>
                </div>
            </div>
            <a href="#n" class="btn_top" style="display: block;"><img
                    src="/en/asset_m/images/common/m_btn_top.png" alt=""></a>
        </div>
        <!-- m_footer -->
        <div class="m_foot">
            <ul>
                <li><a href="/en/util/privacy-policy.asp">Privacy Policy</a></li>
                <li><a href="/en/util/email-reject.asp">Cookie Policy</a></li>
                <li><a href="/en/util/legal-notice.asp">Legal</a></li>
                <li><a href="/en/util/code-of-conduct.asp">Code of Conduct</a></li>
            </ul>
            <img src="/en/asset_m/images/common/m_foot_logo.png" alt="" class="m_foot_logo">
            <span>Copyright © Suprema Inc. All rights reserved.</span>
            <!-- top 이동버튼  <a href="#n" class="m_btn_top"><img src="/en/asset_m/images/common/m_btn_top.png" alt="" /></a>  -->
        </div>
    </footer>
    <!--    footer section end   -->

    <!-- ULink Modal -->
    @foreach ($ulinks as $key => $ulink)
        @if ($ulink->type == 'popup')
            <div class="modal fade ulinkModal" id="ulinkModal{{ $ulink->id }}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {!! replaceBaseUrl(convertUtf8($ulink->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- WhatsApp Chat Button --}}
    <div id="WAButton"></div>

    <!--====== PRELOADER PART START ======-->
    @if ($bex->preloader_status == 1)
        <div id="preloader">
            <div class="loader revolve">
                <img src="{{ asset('assets/front/img/' . $bex->preloader) }}" alt="">
            </div>
        </div>
    @endif
    <!--====== PRELOADER PART ENDS ======-->

    @if ($bex->is_shop == 1 && $bex->catalog_mode == 0)
        <div id="cartIconWrapper">
            <a class="d-block" id="cartIcon" href="{{ route('front.cart') }}">
                <div class="cart-length">
                    <i class="fas fa-cart-plus"></i>
                    <span class="length">{{ cartLength() }} {{ __('ITEMS') }}</span>
                </div>
                <div class="cart-total">
                    {{ $bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '' }}
                    {{ cartTotal() }}
                    {{ $bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '' }}
                </div>
            </a>
        </div>
    @endif

    <!-- back to top area start -->
    <div class="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </div>
    <!-- back to top area end -->


    {{-- Cookie alert dialog start --}}
    @if ($be->cookie_alert_status == 1)
        @include('cookieConsent::index')
    @endif
    {{-- Cookie alert dialog end --}}

    {{-- Popups start --}}
    @includeIf('front.partials.popups')
    {{-- Popups end --}}

    @php
        $mainbs = [];
        $mainbs = json_encode($mainbs);
    @endphp
    <script>
        var mainbs = {!! $mainbs !!};
        var mainurl = "{{ url('/') }}";
        var vap_pub_key = "{{ env('VAPID_PUBLIC_KEY') }}";
        var rtl = {{ $rtl }};
    </script>
    <!-- popper js -->
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <!-- Plugin js -->
    <script src="{{ asset('assets/front/js/plugin.min.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('assets/front/js/main.js') }}"></script>
    <!-- protocol js -->
    <script src="{{ asset('assets/front/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/protocol.js') }}"></script>
    <!-- pagebuilder custom js -->
    <script src="{{ asset('assets/front/js/common-main.js') }}" defer></script>

    {{-- whatsapp init code --}}
    @if ($bex->is_whatsapp == 1)
        <script type="text/javascript">
            var whatsapp_popup = {{ $bex->whatsapp_popup }};
            var whatsappImg = "{{ asset('assets/front/img/whatsapp.svg') }}";
            $(function() {
                $('#WAButton').floatingWhatsApp({
                    phone: "{{ $bex->whatsapp_number }}", //WhatsApp Business phone number
                    headerTitle: "{{ $bex->whatsapp_header_title }}", //Popup Title
                    popupMessage: `{!! nl2br($bex->whatsapp_popup_message) !!}`, //Popup Message
                    showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
                    buttonImage: '<img src="' + whatsappImg + '" />', //Button Image
                    position: "right" //Position: left | right

                });
            });
        </script>
    @endif
    @yield('scripts')
    @stack('event-js')

    @if (session()->has('success'))
        <script>
            toastr["success"]("{{ __(session('success')) }}");
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            toastr["error"]("{{ __(session('error')) }}");
        </script>
    @endif

    <!--Start of subscribe functionality-->
    <script>
        $(document).ready(function() {

            $('a.scroll-section').click(function() {
                $('html, body').animate({
                    scrollTop: $($(this).attr('href')).offset().top - $('.header-area').height()
                }, 500);
                return false;
            });

            $("#subscribeForm, #footerSubscribeForm").on('submit', function(e) {
                // console.log($(this).attr('id'));

                e.preventDefault();

                let formId = $(this).attr('id');
                let fd = new FormData(document.getElementById(formId));
                let $this = $(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);
                        if ((data.errors)) {
                            $this.find(".err-email").html(data.errors.email[0]);
                        } else {
                            toastr["success"]("You are subscribed successfully!");
                            $this.trigger('reset');
                            $this.find(".err-email").html('');
                        }
                    }
                });
            });


        });
    </script>
    <!--End of subscribe functionality-->

    <!--Start of Tawk.to script-->
    {{-- @if ($bs->is_tawkto == 1)
      {!! $bs->tawk_to_script !!}
      @endif --}}
    <!--End of Tawk.to script-->

    <!--Start of AddThis script-->
    {{-- @if ($bs->is_addthis == 1)
      {!! $bs->addthis_script !!}
      @endif --}}
    <!--End of AddThis script-->
</body>

</html>
