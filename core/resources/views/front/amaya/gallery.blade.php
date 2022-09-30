@extends("front.$version.layout")

@section('pagename')
- {{__('Gallery')}}
@endsection

@section('meta-keywords', "$be->gallery_meta_keywords")
@section('meta-description', "$be->gallery_meta_description")

@section('breadcrumb-title', $bs->gallery_title)
@section('breadcrumb-subtitle', $bs->gallery_subtitle)
@section('breadcrumb-link', __('GALLERY'))

@section('content')
<!--    Gallery section start   -->
<section class="gallery-area-v2 image-section" id="masonry-gallery">
  <div class="container">
      <div class="hl-lora-48 text-center mb-18">{{__('gallery-section-image-text')}}</div>
    <div class="row justify-content-center">
      <div class="col-lg-10">
        @if (count($categories) > 0 && $bex->gallery_category_status == 1)
          <div class="filter-nav text-center mb-15">
            <ul class="filter-btn" id="filters">
              @foreach ($categories as $category)
                @php
                    $filterValue = "." . Str::slug($category->name);
                @endphp
                @if(count($category->galleryImg) > 0)
                    @if($filterValue != '.video' && $filterValue != '.videos')
                        <li class="{{ $loop->first ? 'active' : '' }}" data-filter="{{ $filterValue }}">{{ convertUtf8($category->name) }}</li>
                    @endif
                 @endif
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>

    <div class="masonry-row">
      <div class="row">
        @if (count($galleries) == 0)
          <div class="col">
            <h3 class="text-center">{{ __('No Gallery Image Found!') }}</h3>
          </div>
        @else
          @foreach ($galleries as $gallery)
            @php
              $galleryCategory = $gallery->galleryImgCategory()->first();

              if (!empty($galleryCategory)) {
                $categoryName = Str::slug($galleryCategory->name);
              } else {
                $categoryName = "";
              }
            @endphp

              @if($categoryName != '.video' || $categoryName != '.videos')
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 col-6 galery-column {{ $categoryName }}">
              <div class="gallery-item mb-30">
                <div class="gallery-img" style="background-image: url('{{ asset('assets/front/img/gallery/' . $gallery->image) }}'); background-size: cover;">
                  <a href="{{ asset('assets/front/img/gallery/' . $gallery->image) }}" class="img-popup">
                  </a>
                </div>
              </div>
            </div>
            @endif

          @endforeach
        @endif
      </div>
    </div>
  </div>
</section>
<section id="video-area-section" class="video-area-amaya video-area-section bottom-default-navigation">
  <div class="wrapper">
    <div class="container">
      <div class="hl-lora-48 text-center mb-18">{{__('gallery-section-video-text')}}</div>
      <div class="row">
          <div class="masonry-video-row swiper">
          <div class="swiper-wrapper">
        @if (count($galleries) == 0)
          <div class="col">
            <h3 class="text-center">{{ __('No Gallery Image Found!') }}</h3>
          </div>
        @else
          @foreach ($videos as $video)
                  @php
                      $url = $video->video_url;
                      parse_str( parse_url( $url, PHP_URL_QUERY ), $array_of_vars );
                  @endphp
          <div class="swiper-slide">
            <div class="col-12 video-column px-0">
              <div class="video-item mb-30">
                <div class="video-img" style="background-image: url('{{ asset('assets/front/img/gallery/' . $video->image) }}'); background-size: cover;">
                  <a href="{{$video->video_url}}" class="gallery-video-play-button">
                      <span></span>
                  </a>
                </div>
              </div>
            </div>
        </div>

          @endforeach
        @endif
        </div>
      </div>
    </div>
    </div>
      <div class="swiper-navigation">
          <div class="swiper-button swiper-button-next"></div>
          <div class="swiper-button swiper-button-prev"></div>
      </div>
  </div>
</section>
<!--    Gallery section end   -->
@endsection

@section('scripts')
  <script>
    $('#masonry-gallery').imagesLoaded( function() {

    });

    // items on button click
    $('.filter-btn').on('click', 'li', function () {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({
            filter: filterValue
        });
    });
    // menu active class
    $('.filter-btn li').on('click', function (e) {
        $(this).siblings('.active').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    var $grid = $('.masonry-row').isotope({
        itemSelector: '.galery-column',
        layoutMode: 'masonry',
        percentPosition: false,
    });

    $('#filters li.active').trigger('click');
    //****************************
    // Isotope Load more button
    //****************************
    var initShow = 6; //number of items loaded on init & onclick load more button
    var counter = initShow; //counter for load more button
    var iso = $grid.data('isotope'); // get Isotope instance

    loadMore(initShow); //execute function onload

    function loadMore(toShow) {
        $grid.find(".d-none").removeClass("d-none");

        var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function(item) {
            return item.element;
        });
        $(hiddenElems).addClass('d-none');
        $grid.isotope('layout');

        //when no more to load, hide show more button
        if (hiddenElems.length == 0) {
            jQuery("#load-more").hide();
        } else {
            jQuery("#load-more").show();
        }

    }

    //append load more button
    $grid.after('<div class="text-center"><button id="load-more" class="btn-amaya-transparent btn-188">{{__('see more')}}</button></div>');

    //when load more button clicked
    $("#load-more").click(function() {
        if ($('#filters').data('clicked')) {
            //when filter button clicked, set initial value for counter
            counter = initShow;
            $('#filters').data('clicked', false);
        } else {
            counter = counter;
        }

        counter = counter + initShow;

        loadMore(counter);
    });

  /*  //when filter button clicked
    $("#filters").click(function() {
        $(this).data('clicked', true);
        loadMore(initShow);
    });*/

    //===== Magnific Popup
    $('.img-popup').magnificPopup({
      type: 'image',
      gallery: {
        enabled: true
      }
    });
  </script>
@endsection
