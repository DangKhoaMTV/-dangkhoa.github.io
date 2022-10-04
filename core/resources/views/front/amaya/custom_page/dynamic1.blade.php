@extends("front.$version.layout")

@section('pagename')
    - {{convertUtf8($page->name)}}
@endsection

@section('meta-keywords', "$page->meta_keywords")
@section('meta-description', "$page->meta_description")

@section('breadcrumb-title', convertUtf8($page->title))
@section('breadcrumb-subtitle', convertUtf8($page->subtitle))
@section('breadcrumb-link', convertUtf8($page->name))
@section('styles')
    <style>
        .breadcrumb-area {
            display: none!important;
        }
    </style>
@endsection
@section('content')

    <!--   about company section start   -->
    <div class="about-company-section">
        @if($page->video_url)
            @php
                $url = $page->video_url;
                parse_str( parse_url( $url, PHP_URL_QUERY ), $array_of_vars );
            @endphp
            <div class="header">
                <div data-video="{{$array_of_vars['v']}}" class="header__video js-background-video">
                    <div class="header__background">
                        <div id="yt-player"></div>
                    </div>
                </div>
                <div class="header__video-overlay js-video-overlay"
                     style="background-image: url('https://img.youtube.com/vi/{{$array_of_vars['v']}}/maxresdefault.jpg');"></div>
                <h1 class="header__title">{{convertUtf8($page->title)}}</h1>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="custom-page-body">
                        {!! replaceBaseUrl($page->body) !!}
                    </div>
                    @if($page->image)
                        <div class="about-signature">
                            <img class="img-fluid" width="272" src="{{ asset('assets/front/img/Signature/' . $page->image) }}" alt="signature"/>
                            <div class="signature-name h3-lora-24">{{$page->image_name}}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--   about company section end   -->

    <div class="section-map-home map-about-page section-mt-70 section-mb-70">
        @php
            $addresses = explode(PHP_EOL, $bex->contact_addresses);
            $phones = explode(',', $bex->contact_numbers);
            $mails = explode(',', $bex->contact_mails);
        @endphp
        <div id="gmap"></div>
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
        <div class="google-maps">
            <a target="_blank" href="https://maps.google.com/maps?q=AMAYA+HOME/@21.2828223,105.7965075,17z/data=!3m1!4b1!4m8!3m7!1s0x3135038b983f2799:0xe3bf7466adaf5b7f!5m2!4m1!1i2!8m2!3d21.2828729!4d105.7986344&hl=vi-VN"><img alt="google maps" src="{{ asset('assets/front/img/bg-home/google-map.svg') }}"></a>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection
