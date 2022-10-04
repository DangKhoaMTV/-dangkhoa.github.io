@extends("front.$version.layout")

@section('pagename')
 -
 {{__('Special offers')}}
@endsection

@section('meta-keywords', "$be->special_offers_meta_keywords")
@section('meta-description', "$be->special_offers_meta_description")

@section('content')

@section('breadcrumb-title', convertUtf8($bs->special_offer_title))
@section('breadcrumb-subtitle', convertUtf8($bs->special_offer_subtitle))
@section('breadcrumb-link', __('Special offers'))


<!--    Special Offer area start   -->
<div class="special-offer-section special-offer-page-section section-pt-40 section-pb-40">
    <div class="container">
        <h3 class="page-title hl-lora-48 text-center">{{__('Special offers')}}</h3>
        <div class="row">
            @foreach($special_offers as $special_offer)
            <div class="col-lg-12 col-sm-12 px-0" id="{{make_slug($special_offer->title)}}">
                <div class="special-page-item row">
                    <div class="col-lg-6 col-sm-12 col-image">
                        <div class="special-offer-left">
                            <div class="special-offer-image">
                                <img src="{{resize_asset('assets/front/img/special_offers/'.$special_offer->image,null,400)}}" title="{{$special_offer->title}}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-content">
                        <div class="special-offer-right">
                            <div class="special-offer-content">
                                <h3 class="h1-lora-36 special-offer-title">{{convertUtf8($special_offer->title)}}</h3>
                                <p class="special-offer-desc">{{strip_tags($special_offer->content)}}</p>
                                <a href="{{$special_offer->btn_url}}" class="btn-amaya-transparent d-inline-block ">{{convertUtf8($special_offer->btn_text)}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!--    Special Offer area end   -->
@endsection
