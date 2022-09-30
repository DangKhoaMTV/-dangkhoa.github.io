@extends("front.$version.layout")

@section('pagename')
 -
 {{__('Dinning')}}
@endsection

@section('meta-keywords', "$be->dinnings_meta_keywords")
@section('meta-description', "$be->dinnings_meta_description")
@section('styles')
<style>
    .breadcrumb-area {
        display: none!important;
    }
</style>
@endsection

@section('content')

@section('breadcrumb-title', convertUtf8($bs->dinning_title))
@section('breadcrumb-subtitle', convertUtf8($bs->dinning_subtitle))
@section('breadcrumb-link', __('Dinning'))

<div class="dinning-page-wrapper section-mg-menu">
    @php
        $url = $bex->dinning_page_video_url;
        parse_str( parse_url( $url, PHP_URL_QUERY ), $array_of_vars );
    @endphp
    <div class="header">
        <div data-video="{{$array_of_vars['v']}}" class="header__video js-background-video">
            <div class="header__background">
                <div id="yt-player"></div>
            </div>
        </div>

        @if($bex->is_dinning_bg == 0)
            <div class="header__video-overlay js-video-overlay" style="background-image: url('https://img.youtube.com/vi/{{$array_of_vars['v']}}/maxresdefault.jpg');"></div>
        @else
            <div class="header__video-overlay js-video-overlay" style="background-image: url({{asset('assets/front/img/dinnings/' . $bex->dinning_page_bg_image)}});"></div>
        @endif

        @if($bex->product_page_image)
            <h1 class="header__title"><img class="img-fluid" src="{{asset('assets/front/img/' . $bex->product_page_image)}}" alt="Product page image"/></h1>
        @else
            <h1 class="header__title">{{convertUtf8($bs->dinning_title)}}</h1>
        @endif
    </div>

    <!--    Dinning quote area start   -->
    <div class="dinning-section-quote section-mt-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="dinning-quote-content">
                        {{strip_tags($bex->dinning_page_summary)}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    End Dinning quote area start   -->

    <!--    Dinning area start   -->
    <div class="dinning-section dinning-page-section dinning-section-bg bot-right-white-navigation section-space-p-70-48">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-12 px-0 col-image">
                    <div class="dinning-left swiper swiper-dinning-left">
                        <div class="swiper-wrapper">
                            @foreach($sliders as $slider)
                                <div class="dinning-image swiper-slide">
                                    <img src="{{$slider}}" alt="Dinning slide" />
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-navigation">
                            <div class="swiper-button swiper-button-next"></div>
                            <div class="swiper-button swiper-button-prev"></div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 px-0 col-content">
                    <div class="dinning-right">
                        <div class="dinning-content">
                            <h3 class="dinning-title text-center">{{convertUtf8($bs->dinning_title)}}</h3>
                            <p class="dinning-desc">{{strip_tags($bex->dinning_page_content)}}</p>
                            <a href="#" class="btn-amaya-transparent">{{__('View menu button')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    Dinning area end   -->

    <!--    Dinning slider area start   -->
    <div class="dinning-section-slider">
        <div class="wrapper">
                <div class="dinning-sliders">
                    <div class="dinning-items swiper">
                        <div class="swiper-wrapper">
                            @foreach($dinnings as $dinning)
                                <div class="dinning-item swiper-slide">
                                        <img class="dinning-item-img" src="{{resize_asset('assets/front/img/dinnings/'.$dinning->main_image,null,362)}}" alt="{{convertUtf8($dinning->title)}}"/>
                                        <h3 class="dinning-item-title">{{convertUtf8($dinning->title)}}</h3>
                                    <div class="dinning-menu-overlay">
                                        <div class="dinning-menu">{!! replaceBaseUrl(convertUtf8($dinning->content)) !!}</div>
                                        <div class="dinning-view-menu">
                                            <a class="btn-amaya-transparent btn-white" href="{{route('front.dinning_menu',$dinning->slug)}}">{{__('View menu button')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!--    End Dinning slider area start   -->

    <!--    Dinning slider box area start   -->
    <div class="dinning-section-slider-box">
        <div class="wrapper">
                <div class="dinning-sliders-box">
                    <div class="dinning-items-box swiper default-navigation default-navigation-container">
                        <div class="swiper-wrapper">
                            @foreach($sliders_box as $slider_box)
                                <div class="dinning-item-box swiper-slide">
                                    <div class="dinning-item-box-img" style="background-image: url('{{$slider_box}}'); background-size: cover;">
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
        </div>
    </div>
    <!--    End Dinning slider box area start   -->

</div>
@endsection
