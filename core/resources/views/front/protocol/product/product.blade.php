@extends("front.$version.layout")

@section('pagename')
 -
 @if (empty($category))
 {{__('All')}}
 @else
 {{convertUtf8($category->name)}}
 @endif
 {{__('Products')}}
@endsection

@section('meta-keywords', "$be->products_meta_keywords")
@section('meta-description', "$be->products_meta_description")


@section('styles')
<link rel="stylesheet" href="{{asset('assets/front/css/jquery-ui.min.css')}}">
<style>
    .breadcrumb-area {
        display: none!important;
    }
</style>
@endsection

@section('breadcrumb-title', convertUtf8($be->product_title))
@section('breadcrumb-subtitle', convertUtf8($be->product_subtitle))
@section('breadcrumb-link', __('Our Product'))
@section('content')

<!--    product section start    -->
<div class="product-area section-pb-70 section-mg-menu">

    @if($bex->product_page_video_url)
        @php
            $url = $bex->product_page_video_url;
            parse_str( parse_url( $url, PHP_URL_QUERY ), $array_of_vars );
        @endphp
        <div class="header header-video">
            <div data-video="{{$array_of_vars['v']}}" class="header__video js-background-video">
                <div class="header__background">
                    <div id="yt-player"></div>
                </div>
            </div>

           @if($bex->product_page_type == 'video_bg')
            <div class="header__video-overlay js-video-overlay" style="background-image: url('https://img.youtube.com/vi/{{$array_of_vars['v']}}/maxresdefault.jpg');"></div>
            @else
            <div class="header__video-overlay js-video-overlay" style="background-image: url({{asset('assets/front/img/' . $bex->product_page_video_bg)}});"></div>
            @endif

            @if($bex->product_page_image)
                <h1 class="header__title"><img class="img-fluid" src="{{asset('assets/front/img/' . $bex->product_page_image)}}" alt="Product page image"/></h1>
            @else
                <h1 class="header__title">{{convertUtf8($be->product_title)}}</h1>
            @endif
        </div>
    @endif
    <div class="products section-pt-70">
        <div class="container">
            <div class="products-title"><h3 class="hl-lora-48 text-center">{{__('Our Rooms')}}</h3></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    @if($products->count() > 0)

                        @foreach ($products as $product)
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="shop-item">
                                    <div class="swiper item-img">
                                        <div class="shop-thumb swiper-wrapper">
                                            <div class="swiper-slide img-thumb">
                                                <a class="ajax-product-detail" data-toggle="modal" data-target="#productDetailModal" href="{{route('front.product.details',$product->slug)}}">
                                                    <img class="lazy" data-src="{{resize_asset('assets/front/img/product/featured/'.$product->feature_image,null,252)}}" alt="">
                                                </a>
                                            </div>

                                            @foreach ($product->product_images as $image)
                                                @if($loop->index < 2)
                                                    <div class="swiper-slide img-thumb">
                                                        <a class="ajax-product-detail" data-toggle="modal" data-target="#productDetailModal"  href="{{route('front.product.details',$product->slug)}}"><img class="lazy" data-src="{{resize_asset('assets/front/img/product/sliders/'.$image->image,null,252)}}" alt=""></a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination swiper-pagination-clickable"></div>
                                    </div>

                                    <div class="shop-content text-left">
                                        <a class="product-title ajax-product-detail" data-toggle="modal" data-target="#productDetailModal"  href="{{route('front.product.details',$product->slug)}}">
                                            {{strlen($product->title) > 40 ? mb_substr($product->title,0,40,'utf-8') . '...' : $product->title}}
                                        </a>
                                        <div class="product-attributes swiper">
                                            <div class="swiper-wrapper">
                                                @foreach($product->product_attributes as $product_attribute)
                                                    @if(($loop->index)%6 == 0)
                                                        <div class="product-attribute swiper-slide">
                                                    @endif
                                                        <div class="attribute-item" title="{{$product_attribute->attribute->name}}">
                                                            @if($product_attribute->attribute->icon_type=='image')
                                                                <img src="{{asset('assets/front/img/product_attribute/'.$product_attribute->attribute->icon)}}" alt="Attribute" />
                                                            @else
                                                                <i class="{{$product_attribute->attribute->icon}}"></i>
                                                            @endif
                                                            {{$product_attribute->text}}
                                                        </div>
                                                    @if(($loop->index+1)%6 == 0 || $loop->index == count($product->product_attributes)-1)
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="swiper-navigation">
                                                <div class="swiper-button attribute-swiper-button-next swiper-button-next"></div>
                                                <div class="swiper-button attribute-swiper-button-prev swiper-button-prev"></div>
                                            </div>
                                        </div>

                                        <div class="shop-content-bottom">
                                            <div class="shop-price">
                                                @if (!empty($product->previous_price))
                                                    <div class="pre-price"> {{ $bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '' }}{{number_format($product->previous_price, 0, '.', '.')}}{{ $bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '' }}</div>
                                                @endif
                                                <div class="current-price"> {{$bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : ''}}{{number_format($product->current_price, 0, '.', '.')}}{{$bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : ''}}</div>
                                                <div class="room-per-night">{{__('Room per Night')}}</div>
                                            </div>
                                            <div class="shop-button">
                                                <a class="btn-protocol-white" href="{{$product->download_link}}">{{__('BOOK NOW')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center py-5 bg-light" style="margin-top: 30px;">
                            <h4 class="text-center">{{__('Product Not Found')}}</h4>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <nav class="pagination-nav {{$products->count() > 18 ? 'mb-4' : ''}}">
                            {{ $products->appends(['minprice' => request()->input('minprice'), 'maxprice' => request()->input('maxprice'), 'category_id' => request()->input('category_id'), 'type' => request()->input('type'), 'tag' => request()->input('tag'), 'review' => request()->input('review')])->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="products-feature-section section-pb-40 section-pt-40">
            <div class="container">
                <div class="section-title">{{__('Our facilities')}}</div>
                    <div class="row">
                        @foreach ($features as $key => $feature)
                            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                                <div class="grid_item text-center">
                                    <div class="grid_inner_item">
                                        <div class="image_icon">
                                            @if($feature->type == 'icon')
                                                <i class="{{$feature->icon}}"></i>
                                            @else
                                                <img src="{{asset('assets/front/img/featured/'.$feature->image)}}" alt="Feature Image"/>
                                            @endif

                                        </div>
                                        <div class="feature_content">
                                            <h3>{{$feature->title}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
        </div>
    </div>
</div>
<!--    product section ends    -->
@php
    $maxprice = App\Product::max('current_price');
    $minprice = 0;
@endphp

<form id="searchForm" class="d-none"  action="{{ route('front.product') }}" method="get">
    <input type="hidden" id="search" name="search" value="{{ !empty(request()->input('search')) ? request()->input('search') : '' }}">

    @if ($bex->catalog_mode == 0)
        <input type="hidden" id="minprice" name="minprice" value="{{ !empty(request()->input('minprice')) ? request()->input('minprice') : $minprice }}">
        <input type="hidden" id="maxprice" name="maxprice" value="{{ !empty(request()->input('maxprice')) ? request()->input('maxprice') : $maxprice }}">
    @endif

    <input type="hidden" name="category_id" id="category_id" value="{{ !empty(request()->input('category_id')) ? request()->input('category_id') : null }}">
    <input type="hidden" name="type" id="type" value="{{ !empty(request()->input('type')) ? request()->input('type') : 'new' }}">
    <input type="hidden" name="tag" id="tag" value="{{ !empty(request()->input('tag')) ? request()->input('tag') : '' }}">

    @if ($bex->product_rating_system == 1 && $bex->catalog_mode == 0)
        <input type="hidden" name="review" id="review" value="{{ !empty(request()->input('review')) ? request()->input('review') : '' }}">
    @endif

    <button id="search-button" type="submit"></button>
</form>


<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1100px;">
        <div class="modal-content">
            <div class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img src="{{asset('assets/front/img/icon/close.svg')}}" alt="close"/></span>
            </div>
            <div class="modal-body">
                <p>Loading...</p>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="{{asset('assets/front/js/jquery.ui.js')}}"></script>

@if ($bex->catalog_mode == 0)
    <script src="{{asset('assets/front/js/cart.js')}}"></script>
    <script>
        var position = "{{$bex->base_currency_symbol_position}}";
        var symbol = "{{$bex->base_currency_symbol}}";

        // console.log(position,symbol);
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: '{{$maxprice }}',
            values: [ '{{ !empty(request()->input('minprice')) ? request()->input('minprice') : $minprice }}', {{ !empty(request()->input('maxprice')) ? request()->input('maxprice') : $maxprice }} ],
            slide: function( event, ui ) {
            $( "#amount" ).val( (position == 'left' ? symbol : '') + ui.values[ 0 ] + (position == 'right' ? symbol : '') + " - " + (position == 'left' ? symbol : '') + ui.values[ 1 ] + (position == 'right' ? symbol : '') );
        }
        });
        $( "#amount" ).val( (position == 'left' ? symbol : '') + $( "#slider-range" ).slider( "values", 0 ) + (position == 'right' ? symbol : '') + " - " + (position == 'left' ? symbol : '') + $( "#slider-range" ).slider( "values", 1 ) + (position == 'right' ? symbol : '') );

    </script>
@endif


<script>

    let maxprice = 0;
    let minprice = 0;
    let typeSort = '';
    let category = '';
    let tag = '';
    let review = '';
    let search = '';


    $(document).on('click','.filter-button',function(){
        let filterval = $('#amount').val();
        filterval = filterval.split('-');
        maxprice = filterval[1].replace('$','');
        minprice = filterval[0].replace('$','');
        maxprice = parseInt(maxprice);
        minprice = parseInt(minprice);
        $('#maxprice').val(maxprice);
        $('#minprice').val(minprice);
        $('#search-button').click();
    });

$(document).on('change','#type_sort',function(){
    typeSort = $(this).val();
    $('#type').val(typeSort);
    $('#search-button').click();
})
$(document).ready(function(){
    typeSort = $('#type_sort').val();
    $('#type').val(typeSort);
})

$(document).on('click','.category-id',function(e){
    e.preventDefault();
    category = '';
    if($(this).attr('data-href') != 0){
        category = $(this).attr('data-href');
    }
    $('#category_id').val(category);
    $('#search-button').click();
})
$(document).on('click','.tag-id',function(){
    tag = '';
    if($(this).attr('data-href') != 0){
        tag = $(this).attr('data-href');
    }
   $('#tag').val(tag);
   $('#search-button').click();
})

$(document).on('click','.review_val',function(){
    review = $(".review_val:checked").val();
    $('#review').val(review);
    $('#search-button').click();
})

$(document).on('keypress','.input-search',function(e){
    var key = e.which;
    if(key == 13)  // the enter key code
    {
        search = $('.input-search').val();
        $('#search').val(search);
        $('#search-button').click();
        return false;
    }

})

</script>
@endsection
