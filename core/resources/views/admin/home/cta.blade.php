@extends('admin.layout')

@if(!empty($abs->language) && $abs->language->rtl == 1)
@section('styles')
<style>
    form:not(.modal-form) input,
    form:not(.modal-form) textarea,
    form:not(.modal-form) select,
    select[name='language'] {
        direction: rtl;
    }
    form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Call to Action Section</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Home Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Call to Action Section</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">Update Call to Action Section</div>
                </div>
                <div class="col-lg-2">
                    @if (!empty($langs))
                        <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                            <option value="" selected disabled>Select a Language</option>
                            @foreach ($langs as $lang)
                                <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
                @if (!empty($langs))
                    <ul class="nav nav-tabs">
                        @foreach ($langs as $lang)
                            <li class="nav-item">
                                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#upload-lang-{{$lang->code}}">{{$lang->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
              <form id="ctaForm" action="{{route('admin.cta.update', $lang_id)}}" method="post">
                @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @foreach ($langs as $lang)
                              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="upload-lang-{{$lang->code}}">
                                  @include('admin.sameContent')
                @if ($abe[$lang->code]->theme_version != 'gym' && $abe[$lang->code]->theme_version != 'car')
                {{-- Background Part --}}
                <div class="form-group">
                    <label for="">Background ** </label>
                    <br>
                    <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                        <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/front/img/' . $abs[$lang->code]->cta_bg)}}" alt="Background"></label>
                    </div>
                    <br>
                    <br>


                    <input id="fileInput1{{$lang->id}}" type="hidden" name="background_{{$lang->code}}">
                    <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>


                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                    @if ($errors->has('background_'.$lang->code))
                    <p class="text-danger mb-0">{{$errors->first('background_'.$lang->code)}}</p>
                    @endif

                    <!-- Background LFM Modal -->
                    <div class="modal fade lfm-modal" id="lfmModal1{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
                        <i class="fas fa-times-circle"></i>
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <iframe src="{{url('laravel-filemanager')}}?serial=1{{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                  <label for="">Text **</label>
                  <input type="text" name="cta_section_text_{{$lang->code}}" class="form-control" value="{{$abs[$lang->code]->cta_section_text}}">
                  <p id="errcta_section_text_{{$lang->code}}" class="em text-danger mb-0"></p>
                </div>
                <div class="form-group">
                  <label for="">Button Text **</label>
                  <input type="text" class="form-control" name="cta_section_button_text_{{$lang->code}}" value="{{$abs[$lang->code]->cta_section_button_text}}">
                  <p id="errcta_section_button_text_{{$lang->code}}" class="em text-danger mb-0"></p>
                </div>
                <div class="form-group">
                  <label for="">Button URL **</label>
                  <input type="text" class="form-control ltr" name="cta_section_button_url_{{$lang->code}}" value="{{$abs[$lang->code]->cta_section_button_url}}">
                  <p id="errcta_section_button_url_{{$lang->code}}" class="em text-danger mb-0"></p>
                </div>
                              </div>
                          @endforeach
                      </div>
                  @endif
              </form>

            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" form="ctaForm" class="btn btn-success">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
