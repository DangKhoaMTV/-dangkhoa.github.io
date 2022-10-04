<div class="row product-item-row mx-0">
    <div class="col-lg-8 px-0">
        <div class="product-item">
            <div class="swiper item-img default-navigation default-navigation-container">
                <div class="shop-thumb swiper-wrapper">
                    <div class="swiper-slide img-thumb">
                        <img class="lazy" data-src="{{resize_asset('assets/front/img/product/featured/'.$product->feature_image,null,550)}}" alt="">
                    </div>

                    @foreach ($product->product_images as $image)
                        <div class="swiper-slide img-thumb">
                            <img class="lazy" data-src="{{asset('assets/front/img/product/sliders/'.$image->image)}}" alt="">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-navigation">
                    <div class="swiper-button swiper-button-next"></div>
                    <div class="swiper-button swiper-button-prev"></div>
                </div>
                <div class="swiper-pagination swiper-pagination-clickable"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 px-0">
        <div class="product-details-content text-left">

            <h3 class="product-title">
                {{convertUtf8($product->title)}}
            </h3>
            <div class="product-attributes">
                <div class="product-attribute">
                    @foreach($product->product_attributes as $product_attribute)
                        <div class="attribute-item" title="{{$product_attribute->attribute->name}}">
                            @if($product_attribute->attribute->icon_type=='image')
                            <img src="{{asset('assets/front/img/product_attribute/'.$product_attribute->attribute->icon)}}" alt="Attribute" />
                            @else
                                <i class="{{$product_attribute->attribute->icon}}"></i>
                            @endif
                            {{$product_attribute->text}}
                        </div>
                    @endforeach
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
                    <a class="btn-amaya-default" href="{{$product->download_link}}">{{__('BOOK NOW')}}</a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    init_product_swiper();
    new LazyLoad();
</script>
