@extends('front.amaya.layout')

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
    @if(count($features) == 0)
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
        @includeif('front.amaya.partials.static')
    @elseif ($bs->home_version == 'slider')
        @includeif('front.amaya.partials.slider')
    @elseif ($bs->home_version == 'video')
        @includeif('front.amaya.partials.video')
    @elseif ($bs->home_version == 'particles')
        @includeif('front.amaya.partials.particles')
    @elseif ($bs->home_version == 'water')
        @includeif('front.amaya.partials.water')
    @elseif ($bs->home_version == 'parallax')
        @includeif('front.amaya.partials.parallax')
    @endif
    <!--   hero area end    -->
    @if ($bs->intro_section == 1)
    <!--    introduction area start   -->
    <div class="intro-section section-pt-70 section-pb-70">
        <div class="container">
            <div class="row">
                    <div class="col-lg-6 col-sm-12 px-0">
                        <div class="intro-content">
                            <div class="intro-txt">
                                <h2 class="section-title">{{convertUtf8($bs->intro_section_title)}}</h2>
                                <p class="section-summary">{{convertUtf8($bs->intro_section_text)}} </p>
                                @if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text))
                                    <a href="{{$bs->intro_section_button_url}}" class="btn-amaya-transparent">{{convertUtf8($bs->intro_section_button_text)}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 px-0">
                        <div class="intro-image swiper">
                            <div class="swiper-wrapper">
                            @if (!empty($bs->intro_bg))
                                <div class="intro-bg swiper-slide"
                                     style="background-image: url('{{asset('assets/front/img/'.$bs->intro_bg)}}'); background-size: cover;">
                                    @if (!empty($bs->intro_section_video_link))
                                        <a id="play-video" class="video-play-button" href="{{$bs->intro_section_video_link}}">
                                            <span></span>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if (!empty($be->intro_bg2))
                                <div class="intro-bg swiper-slide"
                                     style="background-image: url('{{asset('assets/front/img/'.$be->intro_bg2)}}'); background-size: cover;"></div>
                            @endif
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
    <!--    introduction area end   -->
    @endif

    @if ($bs->special_offer_section == 1)
        <!--    Special Offer area start   -->
        <div class="special-offer-section special-offer-section-bg bot-right-white-navigation section-pt-40 section-pb-40">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 px-0 col-image">
                        <div class="special-offer-left swiper swiper-special-offer-left">
                            <div class="swiper-wrapper">
                                @foreach($special_offers as $special_offer)
                                <div class="special-offer-image swiper-slide">
                                    <img src="{{resize_asset('assets/front/img/special_offers/'.$special_offer->image,618,412)}}" title="{{$special_offer->title}}" />
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
                        <div class="special-offer-right swiper-special-offer-right swiper">
                            <div class="swiper-wrapper">
                                @foreach($special_offers as $special_offer)
                                <div class="swiper-slide">
                                    <div class="special-offer-content">
                                        <h3 class="special-offer-title">{{convertUtf8($special_offer->title)}}</h3>
                                        <p class="special-offer-desc">{{strip_tags($special_offer->content)}}</p>
                                        <a href="{{$special_href}}#{{make_slug($special_offer->title)}}" class="btn-amaya-transparent">{{__('Special button text')}}</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--    Special Offer area end   -->
    @endif


    <!--    Gallery section start   -->
    <section class="home-gallery-section gallery-area-amaya section-pt-70 section-pb-70">
        <div class="wrapper">
            <div class="justify-content-center">
                <div class="text-center mb-15">
                    <h3 class="gallery-title">{{ __('Gallery') }}</h3>
                    <ul class="amaya-tabs nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" href="#{{ make_slug(__('gallery-section-image-text')) }}" data-toggle="tab">{{ __('gallery-section-image-text') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#{{ make_slug(__('gallery-section-video-text')) }}" data-toggle="tab">{{ __('gallery-section-video-text') }}</a></li>
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
                    <div class="tab-pane active" id="{{ make_slug(__('gallery-section-image-text')) }}">
                            <div class="home-galery-column swiper default-navigation">
                                <div class="swiper-wrapper">
                                    @foreach($galleries['image'] as $gallery)
                                    <div class="swiper-slide">
                                        <div class="gallery-item">

                                            <div class="gallery-img"
                                                 style="background-image: url('{{ asset('assets/front/img/gallery/' . $gallery->image) }}'); background-size: cover;"></div>

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
                    <div class="tab-pane" id="{{ make_slug(__('gallery-section-video-text')) }}">
                            <div class="home-galery-column swiper default-navigation">
                                <div class="swiper-wrapper">
                                    @foreach($galleries['video'] as $gallery)
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
            <a href="/home/gallery" class="btn-amaya-transparent btn-188">{{__('see more')}}</a>
        </div>
    </section>
    <!--    Gallery section end   -->

    @if ($bs->testimonial_section == 1)
        <!--   Testimonial section start    -->
        <div class="testimonial-section section-pt-40 section-pb-40">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-6 offset-lg-3">
                        <span class="section-summary">Testimonial</span>
                        <h2 class="section-title">{{convertUtf8($bs->testimonial_title)}}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="testimonial-carousel owl-carousel owl-theme">
                            @foreach ($testimonials as $key => $testimonial)
                                <div class="single-testimonial">
                                    <div class="client-desc">
                                        <p class="comment">{{convertUtf8($testimonial->comment)}}</p>
                                        <h6 class="name">
                                            {{convertUtf8($testimonial->name)}}
                                            <span class="line"></span>
                                            <div class="star-rating" style="width: 90px">
                                                <p style="width:{{$testimonial->rank*100/5}}%;background-image:url({{ asset('assets/front/img/votedstar.svg') }})" alt="star">{{$testimonial->rank}} star(s)</p>
                                            </div>
                                        </h6>
                                        <p><img class="channel" src="{{ asset('assets/front/img/'.$testimonial->channel.'.svg') }}" alt="{{convertUtf8($testimonial->channel)}}"/></p>
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
                <div class="title hl-lora-48 text-center">{{convertUtf8(__('Contact Us'))}}</div>
                @foreach ($addresses as $address)
                    <div class="address"><a><img src="{{ asset('assets/front/img/icon/address.svg')  }}" alt="adress"/>{{convertUtf8($address)}}</a></div>
                @endforeach

                <div class="email">
                    @foreach ($mails as $mail)
                        <a href="mailto:{{$mail}}"><img src="{{ asset('assets/front/img/icon/maill.svg')  }}" alt="mail"/>{{$mail}}</a>
                    @endforeach
                </div>

                <div class="phone">
                    @foreach ($phones as $phone)
                        <a href="tel:{{$phone}}"><img src="{{ asset('assets/front/img/icon/call.svg')  }}" alt="phone"/>{{$phone}}</a>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>
                <div class="website">
                    <a href="/home"><img src="{{ asset('assets/front/img/icon/web.svg')  }}" alt="web"/>{{ $_SERVER['SERVER_NAME'] }}</a>
                </div>
                @if (!empty($socials))
                    @foreach ($socials as $key => $social)
                        @if(!$loop->last)
                            <div class="social-link">
                                <a target="_blank" href="{{$social->url}}"><img src="{{ asset('assets/front/img/icon/'.($loop->first ? 'facebook.svg' : 'instagram.svg')) }}" alt="social-link"/>{{$social->name}}</a>
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
                const amaya = { lat: {{$bex->latitude}}, lng: {{$bex->longitude}} };
                const contentString = document.getElementsByClassName('information-container')[0].innerHTML;
                //console.log(contentString);
                map = new google.maps.Map(document.getElementById("gmap"), {
                    center: amaya,
                    zoom: {{$bex->map_zoom}},
                });
                const infowindow = new google.maps.InfoWindow({
                    content: contentString,
                });

                const marker = new google.maps.Marker({
                    position: amaya,
                    map,
                    title: "Amaya Home",
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
            defer
        ></script>

        <div class="google-maps">
            <a id="google_map" target="_blank" href="https://maps.google.com/maps?q=AMAYA+HOME/@21.2828223,105.7965075,17z/data=!3m1!4b1!4m8!3m7!1s0x3135038b983f2799:0xe3bf7466adaf5b7f!5m2!4m1!1i2!8m2!3d21.2828729!4d105.7986344&hl=vi-VN"><img alt="google maps" src="{{ asset('assets/front/img/bg-home/google-map.svg') }}"></a>
        </div>
    </div>

    @if (!empty($home->html))
        {!! convertHtml(convertUtf8($home->html)) !!}
    @else
        @includeIf('front.partials.pagebuilder-notice')
    @endif

@endsection
