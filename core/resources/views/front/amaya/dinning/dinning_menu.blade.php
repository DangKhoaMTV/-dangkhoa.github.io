@extends("front.$version.layout")

@section('pagename')
 - {{__('Dinning')}} - {{convertUtf8($dinning->title)}}
@endsection

@section('meta-keywords', "$dinning->meta_keywords")
@section('meta-description', "$dinning->meta_description")

@section('breadcrumb-title', convertUtf8($bs->dinning_details_title))
@section('breadcrumb-subtitle', convertUtf8($dinning->title))
@section('breadcrumb-link', __('Dinning menu'))

@section('content')


  <!--    dinning menu section start   -->
  <div class="dinning-menu-section">
     <div class="container px-0">
         <div class="dinning-menu">
             <iframe src="{{$dinning->pdf_link}}" width="100%" height="1100" allow="autoplay"></iframe>
         </div>
     </div>
  </div>
  <!--    dinning menu section end   -->

@endsection
