@extends("front.$version.layout")

@section('pagename')
 -
 @if (empty($category))
 {{__('All')}}
 @else
 {{$category->name}}
 @endif
 {{__('Services')}}
@endsection

@section('meta-keywords', "$be->services_meta_keywords")
@section('meta-description', "$be->services_meta_description")

@section('content')

@section('breadcrumb-title', convertUtf8($bs->service_title))
@section('breadcrumb-subtitle', convertUtf8($bs->service_subtitle))
@section('breadcrumb-link', __('Experiences'))


  <!--    services section start   -->
  <div class="service-section">
     <div class="wrapper">
         <!-- section indoor -->
        <div class="section-indoor" id="indoor_section">
            <h2 class="hl-lora-48 text-center section-indoor-title">{{__('indoor-service')}}</h2>
            @if (count($services_indoor) == 0)
              <div class="bg-light py-5">
                <h3 class="text-center">{{__('NO SERVICE FOUND')}}</h3>
              </div>
            @else
                <ul class="nav nav-tabs indoor-tabs">
                    @foreach ($services_indoor as $key => $service_indoor)
                        <li class="nav-item">
                            <a class="nav-link {{$loop->first ? 'active' : ''}}" data-toggle="tab" href="#service-{{$service_indoor->id}}">
                                <div class="link-tab-service text-center">
                                    <div class="link-tab-img">
                                        <img height="100" src="{{asset('assets/front/img/services/'.$service_indoor->main_image)}}" alt="Image"/>
                                    </div>
                                    <div class="link-tab-title">{{$service_indoor->title}}</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
              @foreach ($services_indoor as $key => $service_indoor)
                        <div class="tab-pane container {{$loop->first ? 'active' : ''}}" id="service-{{$service_indoor->id}}">
                            <div class="row row-service">
                                <div class="col-lg-6">
                                    @if($service_indoor->service_attribute_status == 1)
                                        <h3 class="tab-content-title">{{$service_indoor->title}}</h3>
                                        <div id="accordionService">
                                        @foreach ($service_indoor->service_attributes as $service_attribute)
                                        <div class="card">
                                            <div class="card-header" id="heading{{$service_attribute->id}}">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$service_attribute->id}}" aria-expanded="{{$loop->first ? 'true' : 'false'}}" aria-controls="collapse{{$service_attribute->id}}">
                                                        <img width="36" src="{{asset('assets/front/img/service_attribute/'.$service_attribute->attribute->icon)}}" alt="image"/> {{$service_attribute->attribute->name}}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{$service_attribute->id}}" class="collapse {{$loop->first ? 'show' : ''}}" aria-labelledby="heading{{$service_attribute->id}}" data-parent="#accordionService">
                                                <div class="card-body">
                                                    {{$service_attribute->text}}
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                        <h3 class="tab-content-title"><img class="" width="48" src="{{asset('assets/front/img/services/'.$service_indoor->main_image)}}" alt=""> {{$service_indoor->title}}</h3>
                                        <div class="tab-content-text">{{convertUtf8($service_indoor->summary)}}</div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <div class="single-service-img">
                                        <div class="single-service-img-swiper swiper bot-right-white-navigation">
                                            <div class="service-img-wrapper swiper-wrapper">
                                                @foreach ($service_indoor->service_images as $service_image)
                                                <div class="service-img-item swiper-slide">
                                                    <img class="lazy" src="{{resize_asset('assets/front/img/services/sliders/'.$service_image->image,null,390)}}" alt="">
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="swiper-navigation">
                                                <div class="swiper-button attribute-swiper-button-next swiper-button-next"></div>
                                                <div class="swiper-button attribute-swiper-button-prev swiper-button-prev"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
              @endforeach
                </div>
            @endif

        </div>
         <!-- end section indoor -->
     </div>
      <!--   Testimonial section start    -->
      <div class="d-none testimonial-section section-pt-40 section-pb-40">
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

      <!-- section outdoor -->
      <div class="section-outdoor" id="outdoor_section">
          <div class="wrapper">
              <div class="single-service-outdoor default-navigation default-navigation-outdoor">
                  <h2 class="hl-lora-48 text-center section-outdoor-title">{{__('outdoor-service')}}</h2>
                  <div class="single-service-outdoor-swiper swiper">
                      <div class="single-service-outdoor-wrapper swiper-wrapper">
                          @foreach ($services_outdoor as $service_outdoor)
                              <div class="single-service-outdoor-item swiper-slide">
                                  <div class="row row-service-outdoor mx-0">
                                      <div class="col-lg-7 px-0 col-service-outdoor-img">
                                          <div class="single-service-outdoor-item-img">
                                              @foreach($service_outdoor->service_images as $service_outdoor_i)
                                                  @if($loop->first)
                                                        <img class="lazy" data-src="{{asset('assets/front/img/services/sliders/'.$service_outdoor_i->image)}}" alt="">
                                                  @endif
                                              @endforeach
                                          </div>
                                      </div>
                                      <div class="col-lg-5 px-0 col-service-outdoor-text">
                                          <div class="single-service-outdoor-item-text">
                                              <h3 class="single-service-outdoor-item-title h1-lora-36"><img class="" width="48" src="{{asset('assets/front/img/services/'.$service_outdoor->main_image)}}" alt="">{{convertUtf8($service_outdoor->title)}}</h3>
                                              <div class="single-service-outdoor-item-summary">
                                                  {{convertUtf8($service_outdoor->summary)}}
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                      <div class="swiper-navigation">
                          <div class="swiper-button attribute-swiper-button-prev swiper-button-prev"></div>
                          <div class="swiper-button attribute-swiper-button-next swiper-button-next"></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- end section outdoor -->
  </div>
  <!--    services section end   -->
@endsection
