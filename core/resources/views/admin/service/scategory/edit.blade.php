@extends('admin.layout')

@if(!empty($scategory->language) && $scategory->language->rtl == 1)
@section('styles')
<style>
    form input,
    form textarea,
    form select {
        direction: rtl;
    }
    .nicEdit-main {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Edit Category</h4>
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
        <a href="#">Service Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Edit Category</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Edit Category</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.scategory.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
              <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
                @if (!empty($langs))
                    <ul class="nav nav-tabs">
                        @foreach ($langs as $lang)
                            <li class="nav-item">
                                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#lang-{{$lang->code}}">{{$lang->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
              <form id="ajaxForm" class="" action="{{route('admin.scategory.update')}}" method="post">
                @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @foreach ($langs as $lang)
                              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="lang-{{$lang->code}}">
                                  @if($scategory[$lang->code]->id==0)
                                      <div class="form-group">
                                          <label class="" for="">Choose association **</label>
                                          <select class="form-control select2" name="scategory_assoc_id_{{$lang->code}}">
                                              <option value="" selected disabled>Select a category</option>
                                              @foreach ($scates[$lang->code] as $scate)
                                              <option value="{{$scate->id}}">{{$scate->name}}</option>
                                              @endforeach
                                          </select>
                                          <p id="errassoc_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                      </div>
                                  @else
                <input type="hidden" name="scategory_id_{{$lang->code}}" value="{{$scategory[$lang->code]->id}}">
                                  @endif
                {{-- Image Part --}}
                <div class="form-group">
                    <label for="">Image ** </label>
                    <br>
                    <div class="thumb-preview" id="thumbPreview{{$lang->id}}">
                        <label for="chooseImage{{$lang->id}}"><img src="{{$scategory[$lang->code]->image!=''?asset('assets/front/img/service_category_icons/' . $scategory[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                    </div>
                    <br>
                    <br>


                    <input id="fileInput{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                    <button id="chooseImage{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}">Choose Image</button>


                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                    <!-- Image LFM Modal -->
                    <div class="modal fade lfm-modal" id="lfmModal{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
                        <i class="fas fa-times-circle"></i>
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <iframe src="{{url('laravel-filemanager')}}?serial={{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="">Name **</label>
                  <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$scategory[$lang->code]->name}}" placeholder="Enter name">
                  <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">Sort Text **</label>
                  <input type="text" class="form-control" name="short_text_{{$lang->code}}" value="{{$scategory[$lang->code]->short_text}}" placeholder="Enter short text">
                  <p id="errshort_text_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">Status **</label>
                  <select class="form-control ltr" name="status_{{$lang->code}}">
                    <option value="" selected disabled>Select a status</option>
                    <option value="1" {{$scategory[$lang->code]->status == 1 ? 'selected' : ''}}>Active</option>
                    <option value="0" {{$scategory[$lang->code]->status == 0 ? 'selected' : ''}}>Deactive</option>
                  </select>
                  <p id="errstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">Serial Number **</label>
                  <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$scategory[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                  <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                  <p class="text-warning"><small>The higher the serial number is, the later the service category will be shown everywhere.</small></p>
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
                <button type="submit" id="submitBtn" class="btn btn-success">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

@endsection
