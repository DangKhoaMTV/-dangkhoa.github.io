@extends('admin.layout')

@if(!empty($package->language) && $package->language->rtl == 1)
@section('styles')
<style>
  form input,
  form textarea,
  form select {
    direction: rtl;
  }

  form .note-editor.note-frame .note-editing-area .note-editable {
    direction: rtl;
    text-align: right;
  }
</style>
@endsection
@endif

@section('content')
<div class="page-header">
  <h4 class="page-title">Edit Package</h4>
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
      <a href="#">Package Page</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">Edit Package</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">Edit Package</div>
        <a class="btn btn-info btn-sm float-right d-inline-block"
          href="{{route('admin.package.index') . '?language=' . request()->input('language')}}">
          <span class="btn-label">
            <i class="fas fa-backward" style="font-size: 12px;"></i>
          </span>
          Back
        </a>
      </div>

      <div id="edit_content" class="card-body pt-5 pb-5">
        <div class="row">
          <div class="col-lg-10 offset-lg-1">
              @if (!empty($langs))
                  <ul class="nav nav-tabs">
                      @foreach ($langs as $lang)
                          <li class="nav-item">
                              <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                                 href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
                          </li>
                      @endforeach
                  </ul>
              @endif
            <form id="ajaxEditForm" class="modal-form" action="{{route('admin.package.update')}}" method="POST">
              @csrf
                @if (!empty($langs))
                    <div class="tab-content">
                        @foreach ($langs as $lang)
                            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                                @include('admin.sameContent')

                                @if($package[$lang->code]->id==0)
                                    <div class="form-group">
                                        <label class="" for="">Choose association **</label>
                                        <select class="form-control select2" name="package_assoc_id_{{$lang->code}}">
                                            <option value="" selected>Select a blog</option>
                                            @foreach ($pcates[$lang->code] as $pcate)
                                                <option value="{{$pcate->id}}">[{{$pcate->id}}-{{$pcate->assoc_id}}
                                                    ] {{$pcate->title}}</option>
                                            @endforeach
                                        </select>
                                        <p id="errpackage_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                    </div>
                                @else
                                    <input type="hidden" name="package_id_{{$lang->code}}" value="{{$package[$lang->code]->id}}">
                                @endif


                              @if ($abe->theme_version == 'lawyer')

                              {{-- Image Part --}}
                              <div class="form-group">
                                <label for="">Image ** </label>
                                <br>
                                <div class="thumb-preview" id="thumbPreview{{$lang->id}}1">
                                  <img src="{{$package[$lang->code]->image!=''?asset('assets/front/img/packages/' . $package[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image">
                                </div>
                                <br>
                                <br>


                                <input id="fileInput{{$lang->id}}1" type="hidden" name="image_{{$lang->code}}">
                                <button id="chooseImage{{$lang->id}}1" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                  data-toggle="modal" data-target="#lfmModal{{$lang->id}}1">Choose Image</button>


                                <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                              </div>
                              @endif

                              <div class="form-group {{ $categoryInfo->package_category_status == 0 ? 'd-none' : '' }}">
                                <label for="">Category **</label>
                                <select name="category_id_{{$lang->code}}" class="form-control">
                                  <option disabled selected>Select a category</option>
                                  @foreach ($categories[$lang->code] as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $package[$lang->code]->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                  @endforeach
                                </select>
                                <p id="errcategory_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                              <div class="form-group">
                                <label for="">Title **</label>
                                <input type="text" class="form-control" name="title_{{$lang->code}}" placeholder="Enter title"
                                  value="{{$package[$lang->code]->title}}">
                                <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                              @if ($bex->recurring_billing == 1)
                              <div class="form-group">
                                <label>Duration **</label>
                                <div class="selectgroup w-100">
                                  <label class="selectgroup-item">
                                    <input type="radio" name="duration_{{$lang->code}}" value="monthly" class="selectgroup-input"
                                      {{$package[$lang->code]->duration == 'monthly' ? 'checked' : ''}}>
                                    <span class="selectgroup-button">Monthly</span>
                                  </label>
                                  <label class="selectgroup-item">
                                    <input type="radio" name="duration_{{$lang->code}}" value="yearly" class="selectgroup-input"
                                      {{$package[$lang->code]->duration == 'yearly' ? 'checked' : ''}}>
                                    <span class="selectgroup-button">Yearly</span>
                                  </label>
                                </div>
                                <p id="errduration_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>
                              @endif

                              @if ($be->theme_version == 'cleaning')
                              <div class="form-group">
                                <label for="">Color **</label>
                                <input id="incolor" type="text" class="form-control jscolor" name="color_{{$lang->code}}" placeholder="Enter color"
                                  value="{{!empty($package[$lang->code]->color) ? $package[$lang->code]->color : 'e4e8f9'}}">
                                <p id="eerrcolor_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>
                              @endif
                              <div class="form-group">
                                <label for="">Price (in {{$abx->base_currency_text}}) **</label>
                                <input type="text" class="form-control" name="price_{{$lang->code}}" placeholder="Enter price"
                                  value="{{$package[$lang->code]->price}}">
                                <p id="errprice_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                              <div class="form-group">
                                <label for="">Description **</label>
                                <textarea class="form-control summernote" name="description_{{$lang->code}}" rows="8" cols="80"
                                  placeholder="Enter description" data-height="300">{{replaceBaseUrl($package[$lang->code]->description)}}</textarea>
                                <p id="errdescription_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                              @if ($bex->recurring_billing == 0)
                              <div class="form-group">
                                <label>Order Option **</label>
                                <div class="selectgroup w-100">
                                  <label class="selectgroup-item">
                                    <input type="radio" name="order_status_{{$lang->code}}" value="1" class="selectgroup-input"
                                      {{$package[$lang->code]->order_status == 1 ? 'checked' : ''}} onchange="toggleLink({{$package[$lang->code]->id}}, 1)">
                                    <span class="selectgroup-button">Active</span>
                                  </label>
                                  <label class="selectgroup-item">
                                    <input type="radio" name="order_status_{{$lang->code}}" value="0" class="selectgroup-input"
                                      {{$package[$lang->code]->order_status == 0 ? 'checked' : ''}} onchange="toggleLink({{$package[$lang->code]->id}}, 0)">
                                    <span class="selectgroup-button">Deactive</span>
                                  </label>
                                  <label class="selectgroup-item">
                                    <input type="radio" name="order_status_{{$lang->code}}" value="2" class="selectgroup-input"
                                      {{$package[$lang->code]->order_status == 2 ? 'checked' : ''}} onchange="toggleLink({{$package[$lang->code]->id}}, 2)">
                                    <span class="selectgroup-button">Link</span>
                                  </label>
                                </div>
                                <p id="errorder_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>
                              @endif

                              <div class="form-group" id="externalLink{{$package[$lang->code]->id}}" @if ($package[$lang->code]->order_status != 2)
                                style="display: none;" @endif>
                                <label for="">External Link **</label>
                                <input name="link_{{$lang->code}}" type="text" class="form-control" value="{{$package[$lang->code]->link}}">
                                <p id="errlink_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                              <div class="form-group">
                                <label for="">Serial Number **</label>
                                <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$package[$lang->code]->serial_number}}"
                                  placeholder="Enter Serial Number">
                                <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                <p class="text-warning"><small>The higher the serial number is, the later the package will be shown
                                    everywhere.</small></p>
                              </div>
                              <div class="form-group">
                                <label>Meta Keywords</label>
                                <input class="form-control" name="meta_keywords_{{$lang->code}}" value="{{$package[$lang->code]->meta_keywords}}"
                                  placeholder="Enter meta keywords" data-role="tagsinput">
                                <p id="errmeta_keywords_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>
                              <div class="form-group">
                                <label>Meta Description</label>
                                <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5"
                                  placeholder="Enter meta description">{{$package[$lang->code]->meta_description}}</textarea>
                                <p id="errmeta_description_{{$lang->code}}" class="mb-0 text-danger em"></p>
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

@section('scripts')
<script>
  function toggleLink(pid, status) {
    if (status == 2) {
      $("#externalLink"+pid).show();
    } else {
      $("#externalLink"+pid).hide();
    }
  }
</script>
@endsection
