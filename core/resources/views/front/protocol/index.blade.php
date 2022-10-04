@extends('front.protocol.layout')
@section('pagename')
    - {{ __('Gallery') }}
@endsection
@section('meta-keywords', "$be->home_meta_keywords")
@section('meta-description', "$be->home_meta_description")


@section('styles')
    <style>
        .services-area {
            padding: 0px;
        }

        .case-section {
            padding: 0px;
        }

        .faq-section {
            padding: 0px;
        }

        .pricing-tables {
            padding-top: 0px;
        }

        .blog-section {
            padding: 0px;
        }

        .service-categories {
            padding: 0px;
        }

        .approach-section {
            padding: 0;
        }
    </style>
    @if (!empty($home->css))
        <style>
            {!! replaceBaseUrl($home->css) !!}
        </style>
    @endif
    @if (count($features) == 0)
        <style>
            .intro-section {
                margin-top: 0;
            }

            .hero-txt {
                padding: 310px 270px 165px 0px;
                color: #fff;
                position: relative;
                z-index: 100;
            }
        </style>
    @endif
@endsection


@section('content')
    <!--   hero area start   -->
    @if ($bs->home_version == 'static')
        @includeif('front.protocol.partials.static')
    @elseif ($bs->home_version == 'slider')
        @includeif('front.protocol.partials.slider')
    @elseif ($bs->home_version == 'video')
        @includeif('front.protocol.partials.video')
    @elseif ($bs->home_version == 'particles')
        @includeif('front.protocol.partials.particles')
    @elseif ($bs->home_version == 'water')
        @includeif('front.protocol.partials.water')
    @elseif ($bs->home_version == 'parallax')
        @includeif('front.protocol.partials.parallax')
    @endif
    <!--   hero area end    -->
    @if ($bs->intro_section == 1)
        <!--    introduction area start   -->
        {{-- <div class="intro-section section-pt-70 section-pb-70"> --}}
        {{-- <div class="container"> --}}
        {{-- <div class="row"> --}}
        {{-- <div class="col-lg-6 col-sm-12 px-0"> --}}
        {{-- <div class="intro-content"> --}}
        {{-- <div class="intro-txt"> --}}
        {{-- <h2 class="section-title">{{convertUtf8($bs->intro_section_title)}}</h2> --}}
        {{-- <p class="section-summary">{{convertUtf8($bs->intro_section_text)}} </p> --}}
        {{-- @if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text)) --}}
        {{-- <a href="{{$bs->intro_section_button_url}}" class="btn-protocol-transparent">{{convertUtf8($bs->intro_section_button_text)}}</a> --}}
        {{-- @endif --}}
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- <div class="col-lg-6 col-sm-12 px-0"> --}}
        {{-- <div class="intro-image swiper"> --}}
        {{-- <div class="swiper-wrapper"> --}}
        {{-- @if (!empty($bs->intro_bg)) --}}
        {{-- <div class="intro-bg swiper-slide" --}}
        {{-- style="background-image: url('{{asset('assets/front/img/'.$bs->intro_bg)}}'); background-size: cover;"> --}}
        {{-- @if (!empty($bs->intro_section_video_link)) --}}
        {{-- <a id="play-video" class="video-play-button" href="{{$bs->intro_section_video_link}}"> --}}
        {{-- <span></span> --}}
        {{-- </a> --}}
        {{-- @endif --}}
        {{-- </div> --}}
        {{-- @endif --}}

        {{-- @if (!empty($be->intro_bg2)) --}}
        {{-- <div class="intro-bg swiper-slide" --}}
        {{-- style="background-image: url('{{asset('assets/front/img/'.$be->intro_bg2)}}'); background-size: cover;"></div> --}}
        {{-- @endif --}}
        {{-- </div> --}}
        {{-- </div> --}}

        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
        <!--    introduction area end   -->

        <!-- ///////////////bỏ -->
        {{-- <div class="section"> --}}
        {{-- <span class="product_tit">Security that starts from<br>your finger-tip</span> --}}
        {{-- <div class="tab_wrap"> --}}
        {{-- <ul class="tab_tit pc_use"> --}}
        {{-- <li> --}}
        {{-- <a class="on">CENTRALIZED SOLUTION</a> --}}
        {{-- </li> --}}
        {{-- <li> --}}
        {{-- <a>DISTRIBUTED SOLUTION</a> --}}
        {{-- </li> --}}
        {{-- </ul> --}}
        {{-- </div> --}}
        {{-- </div> --}}
    @endif

    {{-- @if ($bs->special_offer_section == 1) --}}
    {{-- <!--    Special Offer area start   --> --}}
    {{-- <div class="special-offer-section special-offer-section-bg bot-right-white-navigation section-pt-40 section-pb-40"> --}}
    {{-- <div class="container"> --}}
    {{-- <div class="row"> --}}
    {{-- <div class="col-lg-6 col-sm-12 px-0 col-image"> --}}
    {{-- <div class="special-offer-left swiper swiper-special-offer-left"> --}}
    {{-- <div class="swiper-wrapper"> --}}
    {{-- @foreach ($special_offers as $special_offer) --}}
    {{-- <div class="special-offer-image swiper-slide"> --}}
    {{-- <img src="{{resize_asset('assets/front/img/special_offers/'.$special_offer->image,618,412)}}" title="{{$special_offer->title}}" /> --}}
    {{-- </div> --}}
    {{-- @endforeach --}}
    {{-- </div> --}}
    {{-- <div class="swiper-navigation"> --}}
    {{-- <div class="swiper-button swiper-button-next"></div> --}}
    {{-- <div class="swiper-button swiper-button-prev"></div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- <div class="col-lg-6 col-sm-12 px-0 col-content"> --}}
    {{-- <div class="special-offer-right swiper-special-offer-right swiper"> --}}
    {{-- <div class="swiper-wrapper"> --}}
    {{-- @foreach ($special_offers as $special_offer) --}}
    {{-- <div class="swiper-slide"> --}}
    {{-- <div class="special-offer-content"> --}}
    {{-- <h3 class="special-offer-title">{{convertUtf8($special_offer->title)}}</h3> --}}
    {{-- <p class="special-offer-desc">{{strip_tags($special_offer->content)}}</p> --}}
    {{-- <a href="{{$special_href}}#{{make_slug($special_offer->title)}}" class="btn-protocol-transparent">{{__('Special button text')}}</a> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- @endforeach --}}
    {{-- </div> --}}

    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- <!--    Special Offer area end   --> --}}
    {{-- @endif --}}



    <!--    Product section start   -->
    <div class="section">
        <span class="product_tit">Security that starts from<br>your finger-tip</span>
    </div>
    <section class="home-gallery-section gallery-area-protocol section-pt-70 section-pb-70 product-area">
        <div class="wrapper">
            <div class="text-center mb-15">
                <ul class="protocol-tabs nav nav-tabs">
                    @foreach ($home_product_tabs as $home_category)
                        <li class="nav-item"><a class="{{ $loop->first ? 'active' : '' }}" href="#{{ $home_category->slug }}"
                                data-toggle="tab">{{ $home_category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="row ">
                <div class="home-tab-panel tab-content">
                    @if ($home_product_tabs->count() > 0)
                        @foreach ($home_product_tabs as $home_category)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $home_category->slug }}">
                                <div class="col-lg-9 order-1 order-lg-2" style="display: flex">

                                    @foreach ($home_category['products'] as $home_product)
                                        <div class="col-lg-4 col-md-4 col-sm-6">
                                            <div class="shop-item">
                                                <div class="shop-thumb">
                                                    <img class="lazy"
                                                        data-src="{{ asset('assets/front/img/product/featured/' . $home_product->feature_image) }}"
                                                        alt="">
                                                    <ul>
                                                        @if ($bex->catalog_mode == 0)
                                                            <li><a href="{{ route('front.product.checkout', $home_product->slug) }}"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="{{ __('Order Now') }}"><i
                                                                        class="far fa-credit-card"></i></a></li>
                                                            <li><a class="cart-link"
                                                                    data-href="{{ route('add.cart', $home_product->id) }}"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="{{ __('Add to Cart') }}"><i
                                                                        class="fas fa-shopping-cart"></i></a></li>
                                                        @endif
                                                        <li><a href="{{ route('front.product.details', $home_product->slug) }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title=""><img
                                                                    src="https://supremainc.com/en/asset/images/main/product_on.png"
                                                                    alt=""></a></li>
                                                    </ul>
                                                </div>
                                                <div class="shop-content text-center">
                                                    @if ($bex->product_rating_system == 1 && $bex->catalog_mode == 0)
                                                        <div class="rate">
                                                            <div class="rating"
                                                                style="width:{{ $home_product->rating * 20 }}%"></div>
                                                        </div>
                                                    @endif
                                                    <a class="{{ $bex->product_rating_system == 0 || $bex->catalog_mode == 1 ? 'mt-3' : '' }}"
                                                        href="{{ route('front.product.details', $home_product->slug) }}">
                                                        {{ strlen($home_product->title) > 40 ? mb_substr($home_product->title, 0, 40, 'utf-8') . '...' : $home_product->title }}
                                                    </a> <br>

                                                    @if ($bex->catalog_mode == 0)
                                                        <span>
                                                            {{ $bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '' }}{{ $home_product->current_price }}{{ $bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '' }}
                                                            @if (!empty($home_product->previous_price))
                                                                <del> <span class="prepice">
                                                                        {{ $bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '' }}{{ $home_product->previous_price }}{{ $bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '' }}</span></del>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--    Product section end   -->

    <!--    Video section03 start   -->
    <div class="section03">
        <section id="video-area-section" class="video-area-protocol video-area-section bottom-default-navigation">
                <div class="video_area">
                    <div class="btn_play">
                        <div class="masonry-video-row swiper">
                            <div class="swiper-wrapper">
                                @foreach ($galleries['video'] as $video)
                                    @php
                                        $url = $video->video_url;
                                        parse_str(parse_url($url, PHP_URL_QUERY), $array_of_vars);
                                    @endphp
                                        <div class="col-12 video-column px-0">
                                            <div class="video-item mb-30">
                                                <div class="video-img"
                                                    style="background-image: url('{{ asset('assets/front/img/gallery/' . $video->image) }}'); background-size: auto;background-repeat: no-repeat; ">
                                                    <a href="{{ $video->video_url }}" class="gallery-video-play-button">
                                                        {{-- <span></span> --}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <span class="product_tit">The best decision to protect<br>your business</span>
    </div>
    <!--    Video section end   -->

    <!--    Solution start   -->
    <div class="solution">
        <div class="swiper-container-solution ">
            <div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide"></div>
            <div class="swiper-button-prev" tabindex="0" role="button" aria-label="Previous slide"></div>
            <div class="swiper-wrapper" style="transform: translate3d(-2224px, 0px, 0px); transition-duration: 300ms;">
                <!-- solution_01 -->
                <div class="swiper-slide" data-swiper-slide-index="0" style="width: 1102px; margin-right: 10px;">
                    <div class="swiper-slide-wrap">
                        <img src="https://supremainc.com/en/asset_m/images/main/m_solution_01.jpg" alt=""
                            class="m_solution_img">
                        <div class="img_zoom">
                            <img src="https://supremainc.com/en/asset/images/main/solution_01.jpg" alt=""
                                class="solution_img">
                            <div class="solution_txt">
                                <h2>Construction</h2>
                                <span>Suprema’s biometric time and attendance solution is tailored to fulfill the dynamic
                                    demands of the construction industry. Powered by the world’s best facial recognition
                                    technology and loads of industry-specific features, Suprema is a renowned expert in the
                                    construction industry and construction site management.</span>
                                <a href="/en/solutions/construction.asp" class="btn_more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- solution_02 -->
                <div class="swiper-slide" data-swiper-slide-index="1" style="width: 1102px; margin-right: 10px;">
                    <div class="swiper-slide-wrap">
                        <img src="https://supremainc.com/en/asset_m/images/main/m_solution_02.jpg" alt=""
                            class="m_solution_img">
                        <div class="img_zoom">
                            <img src="https://supremainc.com/en/asset/images/main/solution_02.jpg" alt=""
                                class="solution_img">
                            <div class="solution_txt">
                                <h2>Data Center</h2>
                                <span>Powered by Suprema’s world-leading biometrics, our distributed access control solution
                                    offers unrivaled reliability, flexibility, and enhanced management from the server racks
                                    to
                                    the lobby.</span>
                                <a href="/en/solutions/data-center.asp" class="btn_more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- solution_03 -->
                <div class="swiper-slide" data-swiper-slide-index="2" style="width: 1102px; margin-right: 10px;">
                    <div class="swiper-slide-wrap">
                        <img src="https://supremainc.com/en/asset_m/images/main/m_solution_03.jpg" alt=""
                            class="m_solution_img">
                        <div class="img_zoom">
                            <img src="https://supremainc.com/en/asset/images/main/solution_03.jpg" alt=""
                                class="solution_img">
                            <div class="solution_txt">
                                <h2>Healthcare</h2>
                                <span>Our solution provides state-of-the-art biometric access control solutions to hospitals
                                    and
                                    healthcare institutions around the world. Based on our expertise in the healthcare
                                    industry, Suprema provides a secure, flexible and advanced solution to meet challenging
                                    needs from the healthcare facilities.</span>
                                <a href="/en/solutions/healthcare.asp" class="btn_more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- solution_05 -->
                <div class="swiper-slide" data-swiper-slide-index="3" style="width: 1102px; margin-right: 10px;">
                    <div class="swiper-slide-wrap">
                        <img src="https://supremainc.com/en/asset_m/images/main/m_solution_05.jpg" alt=""
                            class="m_solution_img">
                        <div class="img_zoom">
                            <img src="https://supremainc.com/en/asset/images/main/solution_05.jpg" alt=""
                                class="solution_img">
                            <div class="solution_txt">
                                <h2>Commercial</h2>
                                <span>Operating a commercial office facility means ensuring the necessary safety processes
                                    and convenience. Suprema provides a comprehensive range of secure, reliable and
                                    convenient access control features to the modern office environment.</span>
                                <a href="/en/solutions/commercial.asp" class="btn_more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- solution_06 -->
                <div class="swiper-slide" data-swiper-slide-index="4" style="width: 1102px; margin-right: 10px;">
                    <div class="swiper-slide-wrap">
                        <img src="https://supremainc.com/en/asset_m/images/main/m_solution_06.jpg" alt=""
                            class="m_solution_img">
                        <div class="img_zoom">
                            <img src="https://supremainc.com/en/asset/images/main/solution_06.jpg" alt=""
                                class="solution_img">
                            <div class="solution_txt">
                                <h2>Infrastructure</h2>
                                <span>With security as the highest priority, enables operators of infrastructure to monitor
                                    all
                                    restricted sectors. The integration of third-party systems, such as video surveillance,
                                    provides additional options to enhance security measures. With our proven expertise in
                                    highly secured property protection, Suprema served major critical infrastructure
                                    facilities
                                    over the world. Our solution goes far beyond access control to address complex layers
                                    of critical infrastructures.</span>
                                <a href="/en/solutions/infrastructure.asp" class="btn_more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- solution_07 -->
                <div class="swiper-slide" data-swiper-slide-index="5" style="width: 1102px; margin-right: 10px;">
                    <div class="swiper-slide-wrap">
                        <img src="https://supremainc.com/en/asset_m/images/main/m_solution_07.jpg" alt=""
                            class="m_solution_img">
                        <div class="img_zoom">
                            <img src="https://supremainc.com/en/asset/images/main/solution_07.jpg">
                            <div class="solution_txt">
                                <h2>Manufacturing</h2>
                                <span>When it comes to manufacturing facilities, operational efficiency, safety, and health
                                    regulatory compliance are the key measures. With our industry-leading biometrics,
                                    mobile credentialing and network security technologies, Suprema provides
                                    highly-secure, scalable and cost-effective access control solutions for modern
                                    manufacturing facilities.</span>
                                <a href="/en/solutions/manufacturing.asp" class="btn_more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination ">
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 4"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 5"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 6"></span>
            </div>
            <div class="swiper-pagination_m ">
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 4"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 5"></span>
                <span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 6"></span>
            </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>
    </div>
    <!--    Solution end   -->

    <!--    Section04 start   -->
    <div class="section04">
        <div class="buy">
            <img src="https://supremainc.com/en/asset/images/main/section04_01_bg.jpg" alt="" class="img_zoom">
            <dl>
                <dt><img src="https://supremainc.com/en/asset/images/main/section04_01_ico.png" alt=""></dt>
                <dd>
                    <span class="tit">WHERE TO BUY</span>
                    <span class="txt">Find authorized Suprema distributors, system integrators or dealers in your
                        location.</span>
                    <a href="/en/wheretobuy/list.asp">Learn More</a>
                </dd>
            </dl>
        </div>
        <div class="support">
            <img src="https://supremainc.com/en/asset/images/main/section04_02_bg.jpg" alt="" class="img_zoom">
            <dl>
                <dt><img src="https://supremainc.com/en/asset/images/main/section04_02_ico.png" alt=""></dt>
                <dd>
                    <span class="tit">Support</span>
                    <span class="txt">Access to our global support network or get the latest downloads.</span>
                    <a href="/en/support/main.asp">Learn More</a>
                </dd>
            </dl>
        </div>
    </div>
    <!--    Solution04 end   -->

    <!--    Solution05 start   -->
    <div class="section05">
        <!-- nesw -->
        <div class="news">

        </div>

        <!-- news_m -->
        <div class="news_m">

        </div>
        <div class="calendar">

            <dl>
                <dt>
                    <span class="month">Jun</span>
                    <span class="num">13</span>
                </dt>
                <dd><a href="/en/about/news-detail.asp?News_Type=Releases&amp;iBOARD_CONT_NO=4604" class="tit">Suprema
                        BioStar 2 Gains Enhanced Stability and Continuity With Automated Failover</a></dd>
                <dd class="date">June 13, 2022</dd>
            </dl>

            <dl>
                <dt>
                    <span class="month">May</span>
                    <span class="num">16</span>
                </dt>
                <dd><a href="/en/about/news-detail.asp?News_Type=Articles&amp;iBOARD_CONT_NO=4587" class="tit">The
                        Future of Access Control Industry Driven by AI</a></dd>
                <dd class="date">May 16, 2022</dd>
            </dl>

            <dl>
                <dt>
                    <span class="month">Apr</span>
                    <span class="num">25</span>
                </dt>
                <dd><a href="/en/about/news-detail.asp?News_Type=Articles&amp;iBOARD_CONT_NO=4569" class="tit">ACaaS —
                        The future of access control systems</a></dd>
                <dd class="date">April 25, 2022</dd>
            </dl>

            <dl>
                <dt>
                    <span class="month">Mar</span>
                    <span class="num">24</span>
                </dt>
                <dd><a href="/en/about/news-detail.asp?News_Type=Releases&amp;iBOARD_CONT_NO=4467" class="tit">Suprema
                        Showcases Industrial Solutions for North America at ISC West 2022</a></dd>
                <dd class="date">March 24, 2022</dd>
            </dl>

        </div>
        <div class="new_video">
            <ul>
                <li>
                    <a href="https://youtu.be/M0FL1Rj7Cek" target="_blank">
                        <div class="sum"><img src="https://supremainc.com/en/asset/images/main/new_video_sum_01.jpg"
                                alt=""></div>
                        <span class="tit">ACU Solution Line up</span>
                    </a>
                </li>
                <li>
                    <a href="https://youtu.be/7EgRpo6sd8U" target="_blank">
                        <div class="sum"><img src="https://supremainc.com/en/asset/images/main/new_video_sum_02.jpg"
                                alt=""></div>
                        <span class="tit">FaceStation F2</span>
                    </a>
                </li>
                <li>
                    <a href="https://youtu.be/b7z2GZW8sFg" target="_blank">
                        <div class="sum"><img src="https://supremainc.com/en/asset/images/main/new_video_sum_03.jpg"
                                alt=""></div>
                        <span class="tit">BioLite N2</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!--    Solution04 end   -->

    {{-- <div class="col-lg-9 order-1 order-lg-2">
        <div class="row">
            @if ($home_product_tabs->count() > 0)
                    <div class="justify-content-center">
                        <div class="text-center mb-15">
                            <ul class="protocol-tabs nav nav-tabs">
                                @foreach ($home_product_tabs as $home_category)
                                    <li class="nav-item"><a class="nav-link active" href="#{{ $home_category->slug }}" data-toggle="tab">CENTRALIZED SOLUTION</a></li>
                                     <li class="nav-item"><a class="nav-link" href="{{ $home_category->slug }}" data-toggle="tab">DISTRIBUTED SOLUTION</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="home-tab-panel tab-content">
                        @foreach ($home_product_tabs as $home_category)
                        <div class="tab-pane active" id="{{$home_category->slug}}">
                            <div class="home-galery-column swiper default-navigation">
                                <div class="swiper-wrapper">
                                    @foreach ($home_category['products'] as $home_product)
                                        <div class="swiper-slide">
                                            <div class="gallery-item">

                                                <div class="gallery-img"
                                                     style="background-image: url('{{ asset('assets/front/img/product/featured/' . $home_product->feature_image) }}'); background-size: cover;"></div>

                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                                <div class="swiper-navigation">
                                    <div class="swiper-button swiper-button-next"></div>
                                    <div class="swiper-button swiper-button-prev"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
            @endif
    </div> --}}
    <!--    Gallery section start   -->
    {{-- <section class="home-gallery-section gallery-area-protocol section-pt-70 section-pb-70">
        <div class="wrapper">
            @if ($home_product_tabs->count() > 0)
            <div class="justify-content-center">
                <div class="section">
                    <span class="product_tit">Security that starts from<br>your finger-tip</span>
                </div>

                    <div class="text-center mb-15">

                        <ul class="protocol-tabs nav nav-tabs">
                            @foreach ($home_product_tabs as $home_category)
                                    <li class="nav-item"><a class="nav-link active" href="#{{ $home_category->slug }}" data-toggle="tab">CENTRALIZED SOLUTION</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#{{ $home_category->slug }}" data-toggle="tab">DISTRIBUTED SOLUTION</a></li>
                            @endforeach
                        </ul>
                    </div>

            </div>

            <div class="home-tab-panel tab-content">

                @if (count($galleries) == 0)
                <div class="row">
                    <div class="col">
                        <h3 class="text-center">{{ __('No Gallery Image Found!') }}</h3>
                    </div>
                </div>
                @else
                    @foreach ($home_product_tabs as $home_category)
                    <div class="tab-pane active" id="{{$home_category->slug }}">
                            <div class="home-galery-column swiper default-navigation">
                                <div class="swiper-wrapper">
                                    @foreach ($home_category['products'] as $home_product)
                                    <div class="swiper-slide">
                                        <div class="gallery-item">

                                            <div class="gallery-img"
                                                 style="background-image: url('{{ asset('assets/front/img/product/featured/' . $home_product->feature_image) }}'); "></div>

                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-navigation">
                                    <div class="swiper-button swiper-button-next"></div>
                                    <div class="swiper-button swiper-button-prev"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="tab-pane" id="{{ make_slug(__('gallery-section-video-text')) }}">
                            <div class="home-galery-column swiper default-navigation">
                                <div class="swiper-wrapper">
                                    @foreach ($galleries['video'] as $gallery)
                                    <div class="swiper-slide">
                                        <div class="gallery-item">
                                                @php
                                                    $url = $gallery->video_url;
                                                    parse_str( parse_url( $url, PHP_URL_QUERY ), $array_of_vars );
                                                @endphp
                                            <div class="gallery-img"
                                                 style="background-image: url('http://i.ytimg.com/vi/{{$array_of_vars['v']}}/maxresdefault.jpg'); background-size: cover;">
                                                    <a class="gallery-video-play-button" href="{{$gallery->video_url}}">
                                                        <span></span>
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-navigation">
                                    <div class="swiper-button swiper-button-next"></div>
                                    <div class="swiper-button swiper-button-prev"></div>
                                </div>
                            </div>
                        </div>
                @endif
            </div>
            <a href="/home/gallery" class="btn-protocol-transparent btn-188">{{__('see more')}}</a>
            @endif
        </div>
    </section> --}}
    <!--    Gallery section end   -->

    {{-- @if ($bs->testimonial_section == 1)
        <!--   Testimonial section start    -->
        <div class="testimonial-section section-pt-40 section-pb-40">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-6 offset-lg-3">
                        <span class="section-summary">Testimonial</span>
                        <h2 class="section-title">{{ convertUtf8($bs->testimonial_title) }}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="testimonial-carousel owl-carousel owl-theme">
                            @foreach ($testimonials as $key => $testimonial)
                                <div class="single-testimonial">
                                    <div class="client-desc">
                                        <p class="comment">{{ convertUtf8($testimonial->comment) }}</p>
                                        <h6 class="name">
                                            {{ convertUtf8($testimonial->name) }}
                                            <span class="line"></span>
                                            <div class="star-rating" style="width: 90px">
                                                <p style="width:{{ ($testimonial->rank * 100) / 5 }}%;background-image:url({{ asset('assets/front/img/votedstar.svg') }})"
                                                    alt="star">{{ $testimonial->rank }} star(s)</p>
                                            </div>
                                        </h6>
                                        <p><img class="channel"
                                                src="{{ asset('assets/front/img/' . $testimonial->channel . '.svg') }}"
                                                alt="{{ convertUtf8($testimonial->channel) }}" /></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--   Testimonial section end    -->
    @endif

    <div class="section-map-home section-mt-70 section-mb-70">
        @php
            $addresses = explode(PHP_EOL, $bex->contact_addresses);
            $phones = explode(',', $bex->contact_numbers);
            $mails = explode(',', $bex->contact_mails);
        @endphp
        <div id="gmap"></div>
        <div class="information-container">
            <div class="information">
                <div class="title hl-lora-48 text-center">{{ convertUtf8(__('Contact Us')) }}</div>
                @foreach ($addresses as $address)
                    <div class="address"><a><img src="{{ asset('assets/front/img/icon/address.svg') }}"
                                alt="adress" />{{ convertUtf8($address) }}</a></div>
                @endforeach

                <div class="email">
                    @foreach ($mails as $mail)
                        <a href="mailto:{{ $mail }}"><img src="{{ asset('assets/front/img/icon/maill.svg') }}"
                                alt="mail" />{{ $mail }}</a>
                    @endforeach
                </div>

                <div class="phone">
                    @foreach ($phones as $phone)
                        <a href="tel:{{ $phone }}"><img src="{{ asset('assets/front/img/icon/call.svg') }}"
                                alt="phone" />{{ $phone }}</a>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>
                <div class="website">
                    <a href="/home"><img src="{{ asset('assets/front/img/icon/web.svg') }}"
                            alt="web" />{{ $_SERVER['SERVER_NAME'] }}</a>
                </div>
                @if (!empty($socials))
                    @foreach ($socials as $key => $social)
                        @if (!$loop->last)
                            <div class="social-link">
                                <a target="_blank" href="{{ $social->url }}"><img
                                        src="{{ asset('assets/front/img/icon/' . ($loop->first ? 'facebook.svg' : 'instagram.svg')) }}"
                                        alt="social-link" />{{ $social->name }}</a>
                            </div>
                        @endif
                    @endforeach
                @endif

            </div>
        </div>
        <script>
            let map;

            function initMap() {
                document.getElementsByClassName('information-container')[0].style.display = "none";
                const protocol = {
                    lat: {{ $bex->latitude }},
                    lng: {{ $bex->longitude }}
                };
                const contentString = document.getElementsByClassName('information-container')[0].innerHTML;
                //console.log(contentString);
                map = new google.maps.Map(document.getElementById("gmap"), {
                    center: protocol,
                    zoom: {{ $bex->map_zoom }},
                });
                const infowindow = new google.maps.InfoWindow({
                    content: contentString,
                });

                const marker = new google.maps.Marker({
                    position: protocol,
                    map,
                    title: "Protocol",
                });
                infowindow.open({
                    anchor: marker,
                    map,
                    shouldFocus: false,
                });
                marker.addListener("click", () => {
                    infowindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: false,
                    });
                });
            }

            window.initMap = initMap;
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-r-3JYFdisSVC3ivh8E4LDJtz_2CIdH0&callback=initMap&v=weekly"
            defer></script>

        <div class="google-maps">
            <a id="google_map" target="_blank"
                href="https://maps.google.com/maps?q=protocol+HOME/@21.2828223,105.7965075,17z/data=!3m1!4b1!4m8!3m7!1s0x3135038b983f2799:0xe3bf7466adaf5b7f!5m2!4m1!1i2!8m2!3d21.2828729!4d105.7986344&hl=vi-VN"><img
                    alt="google maps" src="{{ asset('assets/front/img/bg-home/google-map.svg') }}"></a>
        </div>
    </div> --}}

    @if (!empty($home->html))
        {!! convertHtml(convertUtf8($home->html)) !!}
    @else
        @includeIf('front.partials.pagebuilder-notice')
    @endif

@endsection
