@extends('admin.layout')

@if(!empty($page->language) && $page->language->rtl == 1)
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
    <h4 class="page-title">Pages</h4>
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
        <a href="#">Edit Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Pages</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Edit Page</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.page.index') . '?language=' . request()->input('language')}}">
						<span class="btn-label">
							<i class="fas fa-backward" style="font-size: 12px;"></i>
						</span>
						Back
					</a>
        </div>
        <div id="edit_content" class="card-body pt-5 pb-4">

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
          <form id="ajaxEditForm" action="{{route('admin.page.update')}}" method="post">
            @csrf
              @if (!empty($langs))
                  <div class="tab-content">
                      @foreach ($langs as $lang)
                          <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                              @include('admin.sameContent')

                              @if($page[$lang->code]->id==0)
                                  <div class="form-group">
                                      <label class="" for="">Choose association **</label>
                                      <select class="form-control select2" name="page_assoc_id_{{$lang->code}}">
                                          <option value="" selected>Select a blog</option>
                                          @foreach ($pcates[$lang->code] as $pcate)
                                              <option value="{{$pcate->id}}">[{{$pcate->id}}-{{$pcate->assoc_id}}
                                                  ] {{$pcate->name}}</option>
                                          @endforeach
                                      </select>
                                      <p id="errpage_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                              @else
                                  <input type="hidden" name="pageid_{{$lang->code}}" value="{{$page[$lang->code]->id}}">
                              @endif

                            <div class="row">
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">Name **</label>
                                  <input type="text" name="name_{{$lang->code}}" class="form-control" placeholder="Enter Name" value="{{$page[$lang->code]->name}}">
                                  <p id="errname_{{$lang->code}}" class="em text-danger mb-0"></p>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Breadcrumb Title </label>
                                        <input type="text" name="breadcrumb_title_{{$lang->code}}" class="form-control" placeholder="Enter Name" value="{{$page[$lang->code]->title}}">
                                        <p id="errbreadcrumb_title_{{$lang->code}}" class="em text-danger mb-0"></p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Breadcrumb Subtitle </label>
                                        <input type="text" name="breadcrumb_subtitle_{{$lang->code}}" class="form-control" placeholder="Enter Name" value="{{$page[$lang->code]->subtitle}}">
                                        <p id="errbreadcrumb_subtitle_{{$lang->code}}" class="em text-danger mb-0"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Status **</label>
                                        <select class="form-control ltr" name="status_{{$lang->code}}">
                                        <option value="1" {{$page[$lang->code]->status == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="0" {{$page[$lang->code]->status == 0 ? 'selected' : ''}}>Deactive</option>
                                        </select>
                                        <p id="errstatus_{{$lang->code}}" class="em text-danger mb-0"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Serial Number **</label>
                                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$page[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                                        <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        <p class="text-warning mb-0"><small>The higher the serial number is, the later the page will be shown in menu.</small></p>
                                    </div>
                                </div>
                            </div>
                            @if ($bex->custom_page_pagebuilder == 0)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">Body **</label>
                                            <textarea id="body" class="form-control summernote" name="body_{{$lang->code}}" data-height="500">{{replaceBaseUrl($page[$lang->code]->body)}}</textarea>
                                            <p id="errbody_{{$lang->code}}" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">Video URL </label>
                                            <input type="text" name="video_url_{{$lang->code}}" class="form-control" placeholder="Enter Video URL" value="{{$page[$lang->code]->video_url}}">
                                            <p id="errvideo_url_{{$lang->code}}" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        {{-- Image Part --}}
                                        <div class="form-group">
                                            <label for="">Image </label>
                                            <br>
                                            <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                                                <label for="chooseImage3{{$lang->id}}"><img src="{{$page[$lang->code]->image!=''?asset('assets/front/img/Signature/' . $page[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                                            </div>
                                            <br>
                                            <br>

                                            <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                                            <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                                    data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>
                                            <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                            <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Image name </label>
                                            <input type="text" name="image_name_{{$lang->code}}" class="form-control" placeholder="Enter Image name" value="{{$page[$lang->code]->image_name}}">
                                            <p id="errimage_name_{{$lang->code}}" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>

                                </div>
                            @endif
                            <div class="form-group">
                               <label>Meta Keywords</label>
                               <input class="form-control" name="meta_keywords_{{$lang->code}}" value="{{$page[$lang->code]->meta_keywords}}" placeholder="Enter meta keywords" data-role="tagsinput">
                            </div>
                            <div class="form-group">
                               <label>Meta Description</label>
                               <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5" placeholder="Enter meta description">{{$page[$lang->code]->meta_description}}</textarea>
                            </div>
                          </div>
                       @endforeach
                  </div>
              @endif
          </form>

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
