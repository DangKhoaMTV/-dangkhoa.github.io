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
      <title>{{$bs->website_title}} @yield('pagename')</title>
      <!-- favicon -->
      <link rel="shortcut icon" href="{{asset('assets/front/img/'.$bs->favicon)}}" type="image/x-icon">

       <!--Font-->
       <link rel="preconnect" href="https://static.3tc.vn" crossorigin>
       <link type="text/css" href="https://static.3tc.vn/css2?family=Lora:wght@400;500;700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

      <!-- bootstrap css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
      <!-- plugin css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/plugin.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/front/css/ficon.css')}}">

      <!-- main css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/amaya-style.css')}}?v=0527">

     <!-- amaya css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/swiper-bundle.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/front/css/amaya.css')}}?v=0527">

      <!-- common css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/common-style.css')}}?v=0527">
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
      <link rel="stylesheet" href="{{asset('assets/front/css/amaya-responsive.css')}}?v=0527">
      <!-- common base color change -->
      <link href="{{url('/')}}/assets/front/css/common-base-color.php?color={{$bs->base_color}}" rel="stylesheet">
      <!-- base color change -->
      <link href="{{url('/')}}/assets/front/css/amaya-base-color.php?color={{$bs->base_color}}{{$be->theme_version != 'dark' ? "&color1=" . $bs->secondary_base_color : ""}}" rel="stylesheet">

      @if ($be->theme_version == 'dark')
        <!-- dark version css -->
        <link rel="stylesheet" href="{{asset('assets/front/css/dark.css')}}">
        <!-- dark version base color change -->
        <link href="{{url('/')}}/assets/front/css/dark-base-color.php?color={{$bs->base_color}}" rel="stylesheet">
      @endif

      @if ($rtl == 1)
      <!-- RTL css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/rtl.css')}}">
      <link rel="stylesheet" href="{{asset('assets/front/css/pb-rtl.css')}}">
      @endif
      <!-- jquery js -->
      <script src="{{asset('assets/front/js/jquery-3.3.1.min.js')}}"></script>

      @if ($bs->is_appzi == 1)
      <!-- Start of Appzi Feedback Script -->
      <script async src="https://app.appzi.io/bootstrap/bundle.js?token={{$bs->appzi_token}}"></script>
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



   <body @if($rtl == 1) dir="rtl" @endif>

      <!--   header area start   -->
      <div class="header-area header-absolute @yield('no-breadcrumb')">
         <div class="container">
            @includeIf('front.amaya.partials.navbar')
         </div>
      </div>
      <!--   header area end   -->


      @if (!request()->routeIs(app()->getLocale().'.front.index') && !request()->routeIs('vi.front.index')  && !request()->routeIs('en.front.index') && !request()->routeIs('front.packageorder.confirmation') && !request()->routeIs(app()->getLocale().'.front.gallery') && !request()->routeIs(app()->getLocale().'.front.special_offers') && !request()->routeIs(app()->getLocale().'.front.dinning_menu') && !request()->routeIs(app()->getLocale().'.front.services'))
        <!--   breadcrumb area start   -->
        <div class="breadcrumb-area cases lazy" data-bg="{{asset('assets/front/img/' . $bs->breadcrumb)}}" style="background-size:cover;">
            <div class="container">
            <div class="breadcrumb-txt">
                <div class="row">
                    <div class="col-xl-7 col-lg-8 col-sm-10">
                        <span>@yield('breadcrumb-title')</span>
                        <h1>@yield('breadcrumb-subtitle')</h1>
                        <ul class="breadcumb">
                        <li><a href="{{route('front.index')}}">{{__('Home')}}</a></li>
                        <li>@yield('breadcrumb-link')</li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
            <div class="breadcrumb-area-overlay" style="background-color: #{{$be->breadcrumb_overlay_color}};opacity: {{$be->breadcrumb_overlay_opacity}};"></div>
        </div>
        <!--   breadcrumb area end    -->
      @endif


      @if (request()->routeIs(app()->getLocale().'.front.gallery'))
          <!--   breadcrumb area start   -->
          <div class="breadcrumb-area cases lazy breadcrumb-gallery-area" data-bg="{{asset('assets/front/img/gallery/' . $bex->gallery_category_bg)}}" style="background-size:cover;background-repeat: no-repeat;
    background-position: center;">
              <div class="container">
                  <div class="breadcrumb-txt">
                      <div class="row">
                          <div class="col-12 text-center">
                              <h3 class="breadcrumb-title hl-lora-48 text-white">@yield('breadcrumb-link')</h3>
                              <div class="breadcrumb-btn">
                                  <a class="btn-amaya-transparent btn-160 text-white gallery-breadcrumb-btn scroll-section" href="#masonry-gallery">{{__('gallery-section-image-text')}}</a>
                                  <a class="btn-amaya-transparent btn-160 text-white gallery-breadcrumb-btn scroll-section" href="#video-area-section">{{__('gallery-section-video-text')}}</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="breadcrumb-area-overlay" style="background-color: #{{$be->breadcrumb_overlay_color}};opacity: {{$be->breadcrumb_overlay_opacity}};"></div>
          </div>
          <!--   breadcrumb area end    -->
      @endif

      @if (request()->routeIs(app()->getLocale().'.front.services'))
          <!--   breadcrumb area start   -->
          <div class="breadcrumb-area cases lazy breadcrumb-gallery-area" data-bg="{{asset('assets/front/img/services/' . $bex->service_page_bg_image)}}" style="background-size:cover;background-repeat: no-repeat;
    background-position: center;">
              <div class="container">
                  <div class="breadcrumb-txt">
                      <div class="row">
                          <div class="col-12 text-center">
                              <h3 class="breadcrumb-title hl-lora-48 text-white">@yield('breadcrumb-link')</h3>
                              <div class="breadcrumb-btn">
                                  <a class="btn-amaya-transparent btn-160 text-white service-breadcrumb-btn scroll-section" href="#indoor_section">{{__('indoor-service')}}</a>
                                  <a class="btn-amaya-transparent btn-160 text-white service-breadcrumb-btn scroll-section" href="#outdoor_section">{{__('outdoor-service')}}</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="breadcrumb-area-overlay" style="background-color: #{{$be->breadcrumb_overlay_color}};opacity: {{$be->breadcrumb_overlay_opacity}};"></div>
          </div>
          <!--   breadcrumb area end    -->
      @endif

      @if (request()->routeIs(app()->getLocale().'.front.dinning_menu'))
          <!--   breadcrumb area start   -->
          <div class="breadcrumb-dinning-menu-area section-mg-menu">
              <div class="container px-0">
                  <div class="dinning-breadcumb">
                      <a href="{{route('front.dinning')}}">{{__('Dinning')}}</a>
                      <img src="{{asset('assets/front/img/icon/keyboard_arrow_right.svg')}}" alt="breadcumb icon"/>
                      <span>@yield('breadcrumb-link')</span>
                  </div>
              </div>
          </div>
          <!--   breadcrumb area end    -->
      @endif


      @yield('content')


      <!--    footer section start   -->
      <footer class="footer-section section-pt-40">
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
                            @if($ulink->type == 'popup')
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
      </footer>
      <!--    footer section end   -->

      <!-- ULink Modal -->
      @foreach($ulinks as $key => $ulink)
          @if($ulink->type == 'popup')
            <div class="modal fade ulinkModal" id="ulinkModal{{$ulink->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <img src="{{asset('assets/front/img/' . $bex->preloader)}}" alt="">
            </div>
        </div>
        @endif
        <!--====== PRELOADER PART ENDS ======-->

        @if ($bex->is_shop == 1 && $bex->catalog_mode == 0)
            <div id="cartIconWrapper">
                <a class="d-block" id="cartIcon" href="{{route('front.cart')}}">
                    <div class="cart-length">
                        <i class="fas fa-cart-plus"></i>
                        <span class="length">{{cartLength()}} {{__('ITEMS')}}</span>
                    </div>
                    <div class="cart-total">
                        {{$bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : ''}}
                        {{cartTotal()}}
                        {{$bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : ''}}
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
        var mainurl = "{{url('/')}}";
        var vap_pub_key = "{{env('VAPID_PUBLIC_KEY')}}";
        var rtl = {{ $rtl }};
      </script>
      <!-- popper js -->
      <script src="{{asset('assets/front/js/popper.min.js')}}"></script>
      <!-- bootstrap js -->
      <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
      <!-- Plugin js -->
      <script src="{{asset('assets/front/js/plugin.min.js')}}"></script>
      <!-- main js -->
      <script src="{{asset('assets/front/js/main.js')}}"></script>
      <!-- amaya js -->
      <script src="{{asset('assets/front/js/swiper-bundle.min.js')}}"></script>
      <script src="{{asset('assets/front/js/amaya.js')}}"></script>
      <!-- pagebuilder custom js -->
      <script src="{{asset('assets/front/js/common-main.js')}}" defer></script>

      {{-- whatsapp init code --}}
      @if ($bex->is_whatsapp == 1)
        <script type="text/javascript">
            var whatsapp_popup = {{$bex->whatsapp_popup}};
            var whatsappImg = "{{asset('assets/front/img/whatsapp.svg')}}";
            $(function () {
                $('#WAButton').floatingWhatsApp({
                    phone: "{{$bex->whatsapp_number}}", //WhatsApp Business phone number
                    headerTitle: "{{$bex->whatsapp_header_title}}", //Popup Title
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
            toastr["success"]("{{__(session('success'))}}");
        </script>
        @endif

        @if (session()->has('error'))
        <script>
            toastr["error"]("{{__(session('error'))}}");
        </script>
        @endif

      <!--Start of subscribe functionality-->
      <script>
        $(document).ready(function() {

            $('a.scroll-section').click(function(){
                $('html, body').animate({
                    scrollTop: $( $(this).attr('href') ).offset().top - $('.header-area').height()
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
      {{--@if ($bs->is_tawkto == 1)
      {!! $bs->tawk_to_script !!}
      @endif--}}
      <!--End of Tawk.to script-->

      <!--Start of AddThis script-->
      {{--@if ($bs->is_addthis == 1)
      {!! $bs->addthis_script !!}
      @endif--}}
      <!--End of AddThis script-->
   </body>
</html>
