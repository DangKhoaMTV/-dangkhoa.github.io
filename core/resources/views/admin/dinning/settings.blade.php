@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">Settings</h4>
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
        <a href="#">Dinnings</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Settings</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Settings</div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
                @if (!empty($langs))
                    <ul class="nav nav-tabs">
                        @foreach ($langs as $lang)
                            <li class="nav-item">
                                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                                   href="#upload-lang-{{$lang->code}}">{{$lang->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
              <form id="settingsForm" action="{{route('admin.dinning.updateSettings')}}" method="POST">
                @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @foreach ($langs as $lang)
                              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                   id="upload-lang-{{$lang->code}}">
                                  @include('admin.sameContent')

                                  <div class="form-group">
                                      <label for="summary">Summary</label>
                                      <textarea name="dinning_page_summary_{{$lang->code}}" id="summary_{{$lang->code}}" class="form-control" rows="4" placeholder="Enter Dinning Summary">{{$d_bex[$lang->code]->dinning_page_summary}}</textarea>
                                      <p id="errdinning_page_summary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>

                              <div class="form-group">
                                  <label for="">Dinning Page Video URL **</label>
                                  <input type="text" class="form-control" name="dinning_page_video_url_{{$lang->code}}" value="{{$d_bex[$lang->code]->dinning_page_video_url}}" placeholder="Enter Video URL">
                                  <p id="errdinning_page_video_url_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                              {{-- START: Dinning Page Image --}}
                              <div class="form-group">
                                  <label for="">Dinning Page Background Image ** </label>
                                  <br>
                                  <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                                      <label for="chooseImage1{{$lang->id}}"> <img src="{{ $d_bex[$lang->code]->dinning_page_bg_image != '' ? asset('assets/front/img/dinnings/' . $d_bex[$lang->code]->dinning_page_bg_image) : asset('assets/admin/img/noimage.jpg') }}" alt="Feature Image"></label>
                                  </div>
                                  <br>
                                  <br>

                                  <input id="fileInput1{{$lang->id}}" type="hidden" name="dinning_page_bg_image_{{$lang->code}}">
                                  <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                          data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image
                                  </button>

                                  <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                  <p id="errdinning_page_image" class="mb-0 text-danger em"></p>
                                  <!-- Featured Image LFM Modal -->
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

                              {{-- START: slider Part --}}
                              <div class="row">
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label for="">Slider Images ** </label>
                                          <br>
                                          <div class="slider-thumbs" id="sliderThumbs2{{$lang->id}}">

                                          </div>

                                          <input id="fileInput2{{$lang->id}}" type="hidden" name="dinning_page_slider_{{$lang->code}}" value="" />
                                          <button id="chooseImage2{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="true"
                                                  data-toggle="modal" data-target="#lfmModal2{{$lang->id}}">Choose Images</button>


                                          <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                          <p id="errdinning_page_slider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                                          <!-- slider LFM Modal -->
                                          <div class="modal fade lfm-modal" id="lfmModal2{{$lang->id}}" tabindex="-1" role="dialog"
                                               aria-labelledby="lfmModalTitle" aria-hidden="true">
                                              <i class="fas fa-times-circle"></i>
                                              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                  <div class="modal-content">
                                                      <div class="modal-body p-0">
                                                          <iframe id="lfmIframe2{{$lang->id}}"
                                                                  src="{{url('laravel-filemanager')}}?serial=2{{$lang->id}}&dinning={{$lang->id}}"
                                                                  style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              {{-- END: slider Part --}}


                              {{-- START: slider box Part --}}
                              <div class="row">
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label for="">Slider Box Images ** </label>
                                          <br>
                                          <div class="slider-thumbs" id="sliderThumbs21{{$lang->id}}">

                                          </div>

                                          <input id="fileInput21{{$lang->id}}" type="hidden" name="dinning_page_slider_box_{{$lang->code}}" value="" />
                                          <button id="chooseImage21{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="true"
                                                  data-toggle="modal" data-target="#lfmModal21{{$lang->id}}">Choose Images</button>


                                          <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                          <p id="errdinning_page_slider_box_{{$lang->code}}" class="mb-0 text-danger em"></p>

                                          <!-- slider LFM Modal -->
                                          <div class="modal fade lfm-modal" id="lfmModal21{{$lang->id}}" tabindex="-1" role="dialog"
                                               aria-labelledby="lfmModalTitle" aria-hidden="true">
                                              <i class="fas fa-times-circle"></i>
                                              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                  <div class="modal-content">
                                                      <div class="modal-body p-0">
                                                          <iframe id="lfmIframe21{{$lang->id}}"
                                                                  src="{{url('laravel-filemanager')}}?serial=21{{$lang->id}}&dinning_box={{$lang->id}}"
                                                                  style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              {{-- END: slider Part --}}

                              <div class="form-group">
                                  <label for="">Content **</label>
                                  <textarea id="dinningContent" class="form-control summernote" name="dinning_page_content_{{$lang->code}}"
                                            data-height="300"
                                            placeholder="Enter content">{{replaceBaseUrl($d_bex[$lang->code]->dinning_page_content)}}</textarea>
                                  <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                            <div class="form-group">
                                <label>Use Background Image **</label>
                                <div class="selectgroup w-100">
                                      <label class="selectgroup-item">
                                          <input type="radio" name="is_dinning_bg_{{$lang->code}}" value="1" class="selectgroup-input" {{$d_bex[$lang->code]->is_dinning_bg == 1 ? 'checked' : ''}}>
                                          <span class="selectgroup-button">Active</span>
                                      </label>
                                      <label class="selectgroup-item">
                                          <input type="radio" name="is_dinning_bg_{{$lang->code}}" value="0" class="selectgroup-input" {{$d_bex[$lang->code]->is_dinning_bg == 0 ? 'checked' : ''}}>
                                          <span class="selectgroup-button">Deactive</span>
                                      </label>
                                </div>
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
                <button type="submit" form="settingsForm" class="btn btn-success">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

@endsection

