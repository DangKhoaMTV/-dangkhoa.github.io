@extends('admin.layout')

@php
$selLang = \App\Language::where('code', request()->input('language'))->first();
@endphp
@if(!empty($selLang) && $selLang->rtl == 1)
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
    <h4 class="page-title">Page Lists</h4>
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
        <a href="#">Pages</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Page Lists</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title d-inline-block">Page Lists</div>
                </div>
                <div class="col-lg-3">
                    @if (!empty($langs))
                        <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                            <option value="" selected disabled>Select a Language</option>
                            @foreach ($langs as $lang)
                                <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                    <a href="{{route('admin.page.create')}}" data-toggle="modal" data-target="#createModal" class="btn btn-primary float-lg-right float-left btn-sm"><i class="fas fa-plus"></i> Add Page</a>
                    <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.page.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($apages) == 0)
                <h2 class="text-center">NO LINK ADDED</h2>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                            <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($apages as $key => $apage)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{$apage->id}}">
                          </td>
                          <td>{!! convertUtf8($apage->name) !!}</td>
                          <td>
                            @if ($apage->status == 1)
                              <span class="badge badge-success">Active</span>
                            @elseif ($apage->status == 0)
                              <span class="badge badge-danger">Deactive</span>
                            @endif
                          </td>
                          <td>{{$apage->serial_number}}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm editbtn_url" href="{{route('admin.page.edit', $apage->id) . '?language=' . request()->input('language')}}" data-url="{{route('admin.page.edit', $apage->id) . '?language=' . request()->input('language') . ' #edit_content'}}" data-toggle="modal" data-target="#editModal">
                                <span class="btn-label">
                                <i class="fas fa-edit"></i>
                                </span>
                                Edit
                            </a>
                            @if ($bex->custom_page_pagebuilder == 1)

                                <a class="btn btn-secondary btn-sm" href="{{route('admin.pagebuilder.content', ['id' => $apage->id, 'language' => $apage->language->code, 'type' => 'page'])}}" target="_blank">
                                    <span class="btn-label">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    Content
                                </a>
                            @endif
                            <form class="d-inline-block deleteform" action="{{route('admin.page.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="pageid" value="{{$apage->id}}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                Delete
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Page Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
       aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Post Job</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  @if (!empty($langs))
                      <ul class="nav nav-tabs">
                          @foreach ($langs as $lang)
                              <li class="nav-item">
                                  <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                                     href="#create-lang-{{$lang->code}}">{{$lang->name}}</a>
                              </li>
                          @endforeach
                      </ul>
                  @endif
                  <form id="ajaxForm" action="{{route('admin.page.store')}}" method="post">
                      @csrf
                      @if (!empty($langs))
                          <div class="tab-content">
                              @foreach ($langs as $lang)
                                  <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">
                                      @include('admin.sameContent')

                                      <div class="row">
                                          <div class="col-lg-4">
                                              <div class="form-group">
                                                  <label for="">Name **</label>
                                                  <input type="text" name="name_{{$lang->code}}" class="form-control" placeholder="Enter Name" value="">
                                                  <p id="errname_{{$lang->code}}" class="em text-danger mb-0"></p>
                                              </div>
                                          </div>
                                          <div class="col-lg-4">
                                              <div class="form-group">
                                                  <label for="">Breadcrumb Title </label>
                                                  <input type="text" name="breadcrumb_title_{{$lang->code}}" class="form-control" placeholder="Enter Name" value="">
                                                  <p id="errbreadcrumb_title_{{$lang->code}}" class="em text-danger mb-0"></p>
                                              </div>
                                          </div>
                                          <div class="col-lg-4">
                                              <div class="form-group">
                                                  <label for="">Breadcrumb Subtitle </label>
                                                  <input type="text" name="breadcrumb_subtitle_{{$lang->code}}" class="form-control" placeholder="Enter Name" value="">
                                                  <p id="errbreadcrumb_subtitle_{{$lang->code}}" class="em text-danger mb-0"></p>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="row">
                                          <div class="col-lg-6">
                                              <div class="form-group">
                                                  <label for="">Status **</label>
                                                  <select class="form-control ltr" name="status_{{$lang->code}}">
                                                      <option value="1">Active</option>
                                                      <option value="0">Deactive</option>
                                                  </select>
                                                  <p id="errstatus_{{$lang->code}}" class="em text-danger mb-0"></p>
                                              </div>
                                          </div>
                                          <div class="col-lg-6">
                                              <div class="form-group">
                                                  <label for="">Serial Number **</label>
                                                  <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
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
                                                      <textarea id="body" class="form-control summernote" name="body_{{$lang->code}}" data-height="500"></textarea>
                                                      <p id="errbody_{{$lang->code}}" class="em text-danger mb-0"></p>
                                                  </div>
                                              </div>

                                              <div class="col-12">
                                                  <div class="form-group">
                                                      <label for="">Video URL </label>
                                                      <input type="text" name="video_url_{{$lang->code}}" class="form-control" placeholder="Enter Video URL" value="">
                                                      <p id="errvideo_url_{{$lang->code}}" class="em text-danger mb-0"></p>
                                                  </div>
                                              </div>

                                              <div class="col-6">
                                                    {{-- Image Part --}}
                                                    <div class="form-group">
                                                    <label for="">Image </label>
                                                    <br>
                                                    <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                                                        <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                                                    </div>
                                                    <br>
                                                    <br>

                                                    <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                                                    <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                                            data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>
                                                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                                    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>
                                                </div>
                                              </div>

                                              <div class="col-6">
                                                  <div class="form-group">
                                                      <label for="">Image name </label>
                                                      <input type="text" name="image_name_{{$lang->code}}" class="form-control" placeholder="Enter Image name" value="">
                                                      <p id="errimage_name_{{$lang->code}}" class="em text-danger mb-0"></p>
                                                  </div>
                                              </div>

                                          </div>
                                      @endif

                                      <div class="form-group">
                                          <label>Meta Keywords</label>
                                          <input class="form-control" name="meta_keywords_{{$lang->code}}" value="" placeholder="Enter meta keywords" data-role="tagsinput">
                                      </div>
                                      <div class="form-group">
                                          <label>Meta Description</label>
                                          <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5" placeholder="Enter meta description"></textarea>
                                      </div>
                                  </div>
                              @endforeach
                          </div>
                      @endif
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="submitBtn" type="button" class="btn btn-primary">Create</button>
              </div>
          </div>
      </div>
  </div>

  <!-- Edit Page Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
       aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Job</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>loading...</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="updateBtn" type="button" class="btn btn-primary">Save Changes</button>
              </div>
          </div>
      </div>
  </div>

  @foreach ($langs as $lang)
      <!-- Image LFM Modal -->
      <div class="modal fade lfm-modal" id="lfmModal1{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
           aria-hidden="true">
          <i class="fas fa-times-circle"></i>
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body p-0">
                      <iframe src="{{url('laravel-filemanager')}}?serial=1{{$lang->id}}"
                              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                  </div>
              </div>
          </div>
      </div>
      <!-- Image LFM Modal -->
      <div class="modal fade lfm-modal" id="lfmModal3{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
           aria-hidden="true">
          <i class="fas fa-times-circle"></i>
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body p-0">
                      <iframe src="{{url('laravel-filemanager')}}?serial=3{{$lang->id}}"
                              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                  </div>
              </div>
          </div>
      </div>
  @endforeach
@endsection
