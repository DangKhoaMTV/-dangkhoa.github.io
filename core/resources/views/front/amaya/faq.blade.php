@extends("front.$version.layout")

@section('pagename')
- {{__('FAQ')}}
@endsection

@section('meta-keywords', "$be->faq_meta_keywords")
@section('meta-description', "$be->faq_meta_description")


@section('breadcrumb-title', convertUtf8($bs->faq_title))
@section('breadcrumb-subtitle', convertUtf8($bs->faq_subtitle))
@section('breadcrumb-link', __('FAQS'))
<style>
    .breadcrumb-area {
        display: none!important;
    }
</style>

@section('content')
  <!--   FAQ section start   -->
  <section class="faq-area-v1 section-pt-70 section-mg-menu">
    <div class="container">
      <div class="row">
          <div class="col-12">
              <div class="title hl-lora-48 text-center">{{convertUtf8($bs->faq_title)}}</div>
          </div>
          @if ($bex->faq_category_status == 1)
                @if (count($categories) > 0)
                <div class="col-lg-3">
                    <div class="sidebar-widget-area">
                    <div class="widget categories-widget">
                        <ul class="nav nav-tabs" id="myTab">
                        @foreach ($categories as $category)
                            <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $loop->iteration == 1 ? 'show active' : '' }}" data-toggle="tab" href="{{ '#category' . $category->id }}" role="tab" aria-selected="true">{{ $category->name }}</a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="faq-details-wrapper">
                    <div class="tab-content">
                        @foreach ($categories as $category)
                        <div class="tab-pane {{ $loop->iteration == 1 ? 'show active' : '' }} fade" id="{{ 'category' . $category->id }}">
                            <div class="accordion" id="{{ 'accordion' . $category->id }}">
                            @php
                                $qas = \App\Faq::where('category_id', $category->id)->orderBy('serial_number', 'ASC')->get();
                            @endphp

                            @foreach ($qas as $qa)
                                <div class="card mb-30">
                                <a class="collapsed card-header" id="heading1" href="#" data-toggle="collapse" data-target="{{ '#collapse' . $qa->id }}" aria-expanded="{{ $loop->iteration == 1 ? 'true' : 'false' }}" aria-controls="{{ 'collapse' . $qa->id }}">
                                    {{ $qa->question }}<span class="toggle_btn"></span>
                                </a>
                                <div id="{{ 'collapse' . $qa->id }}" class="collapse {{ $loop->iteration == 1 ? 'show' : '' }}" aria-labelledby="heading1" data-parent="{{ '#accordion' . $category->id }}">
                                    <div class="card-body">
                                    <p>{{ $qa->answer }}</p>
                                    </div>
                                </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </div>
                </div>
                @endif

          @else

            @if (count($faqs) == 0)
            <div class="col-lg-8 offset-lg-2 py-y bg-light">
                <h3 class="text-center">{{ __('No FAQ Found!') }}</h3>
            </div>
            @else
            <div class="col-lg-12">
                <div class="faq-section py-0">
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="accordion" id="accordionExample1">
                              @foreach ($faqs as $faq)
                              <div class="card">
                                 <div class="card-header" id="heading{{$faq->id}}">
                                    <h2 class="mb-0">
                                       <button class="btn btn-link collapsed btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$faq->id}}" aria-expanded="{{$loop->first ? 'true' : 'false'}}" aria-controls="collapse{{$faq->id}}">
                                       <span>{{convertUtf8($faq->question)}}</span>
                                       </button>
                                    </h2>
                                 </div>
                                 <div id="collapse{{$faq->id}}" class="collapse {{$loop->first ? 'show' : ''}}" aria-labelledby="heading{{$faq->id}}" data-parent="#accordionExample1">
                                    <div class="card-body">
                                       {{convertUtf8($faq->answer)}}
                                    </div>
                                 </div>
                              </div>
                              @endforeach
                           </div>
                        </div>
                     </div>
                </div>
            </div>
            @endif

          @endif




      </div>
    </div>
  </section>
  <!--   FAQ section end   -->
@endsection
