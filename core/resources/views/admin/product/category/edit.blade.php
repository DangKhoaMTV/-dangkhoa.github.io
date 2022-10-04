@extends('admin.layout')

@if(!empty($data->language) && $data->language->rtl == 1)
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
        <a href="#">Shop Management</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Manage Products</a>
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
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.category.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
              <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-8 offset-lg-2" id="edit_content">
                @if (!empty($langs))
                    <ul class="nav nav-tabs">
                        @foreach ($langs as $lang)
                            <li class="nav-item">
                                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                   data-toggle="tab"
                                   href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
              <form id="ajaxEditForm"  action="{{route('admin.category.update')}}" method="POST">
                @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @foreach ($langs as $lang)
                              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                                  @if($pcategory[$lang->code]->id==0)
                                      <div class="form-group">
                                          <label class="" for="">Choose association **</label>
                                          <select class="form-control select2"
                                                  name="pcategory_assoc_id_{{$lang->code}}">
                                              <option value="" selected>Select a blog</option>
                                              @foreach ($pcates[$lang->code] as $pcate)
                                                  <option value="{{$pcate->id}}">[{{$pcate->id}} - {{$pcate->assoc_id}}] {{$pcate->name}}</option>
                                              @endforeach
                                          </select>
                                          <p id="errpcategory_assoc_id_{{$lang->code}}"
                                             class="mb-0 text-danger em"></p>
                                      </div>
                                  @else
                                      <input type="hidden" name="category_id_{{$lang->code}}" value="{{$pcategory[$lang->code]->id}}">
                                  @endif
                            @if ($be->theme_version == 'ecommerce')
                            {{-- Image Part --}}
                            <div class="form-group">
                              <label for="">Image ** </label>
                              <br>
                              <div class="thumb-preview" id="thumbPreview{{$lang->id}}1">
                                <img src="{{asset('assets/front/img/product/categories/'.$pcategory[$lang->code]->image)}}" alt="User Image">
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

                            <div class="form-group">
                              <label for="">Name **</label>
                              <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$pcategory[$lang->code]->name}}" placeholder="Enter name">
                              <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                            <!--Add Place of display-->
                            
                            <div class="form-group">
                              <label for="">Status **</label>
                              <select class="form-control ltr" name="status_{{$lang->code}}">
                                <option value="" selected disabled>Select a status</option>
                                <option value="1" {{$pcategory[$lang->code]->status ==1 ? 'selected' : ''}}>Active</option>
                                <option value="0" {{$pcategory[$lang->code]->status ==0 ? 'selected' : ''}}>Deactive</option>
                              </select>
                              <p id="errstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                              <label for="category">Menu_parent</label>
                              <select class="form-control parentData" name="parent_id_{{$lang->code}}" id="category">
                                  <option value="" selected disabled>Select a category</option>
                                  @foreach ($categories[$lang->code] as $categroy)
                                      <option data-assoc_id="{{$categroy->assoc_id}}" value="{{$categroy->id}}">{{$categroy->name}}</option>
                                  @endforeach
                              </select>
                              {{-- <p id="errparent_id_{{$lang->code}}" class="mb-0 text-danger em"></p> --}}
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
