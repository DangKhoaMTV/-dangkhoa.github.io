@extends("front.$version.layout")

@section('pagename')
 - {{__('Product')}} - {{convertUtf8($product->title)}}
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('assets/front/css/slick.css')}}">
@endsection

@section('meta-keywords', "$product->meta_keywords")
@section('meta-description', "$product->meta_description")

@php
    $reviews = App\ProductReview::where('product_id', $product->id)->get();
    $avarage_rating = App\ProductReview::where('product_id',$product->id)->avg('review');
    $avarage_rating =  round($avarage_rating,2);

@endphp

@section('breadcrumb-title', $be->product_details_title)
@section('breadcrumb-subtitle', strlen($product->title) > 40 ? mb_substr($product->title,0,40,'utf-8') . '...' : $product->title)
@section('breadcrumb-link', strlen($product->title) > 40 ? mb_substr($product->title,0,40,'utf-8') . '...' : $product->title)

@section('content')


<!--====== PRODUCT DETAILS PART START ======-->

<div class="product-details-area section-pt-70 section-pb-70">
    <div class="container">
        @includeIf('front.amaya.product.details-modal')
    </div>
</div>

<!--====== PRODUCT DETAILS PART ENDS ======-->

@endsection

@section('scripts')

<script src="{{asset('assets/front/js/slick.min.js')}}"></script>
<script src="{{asset('assets/front/js/product.js')}}"></script>
<script src="{{asset('assets/front/js/cart.js')}}"></script>
<script>
    $('.image-popup').magnificPopup({
        type: 'image',
        gallery:{
            enabled:true
        }
    });

</script>

<script>
    $(document).on('click','.review-value li a',function(){
        $('.review-value li a i').removeClass('text-primary');
        let reviewValue = $(this).attr('data-href');
         parentClass = `review-${reviewValue}`;
        $('.'+parentClass+ ' li a i').addClass('text-primary');
        $('#reviewValue').val(reviewValue);
    })
</script>

@endsection
