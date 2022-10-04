@extends('admin.layout')

@if(!empty($event->language) && $event->language->rtl == 1)
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
    <h4 class="page-title">Edit Donation</h4>
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
        <a href="#">Donation Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Edit Donation</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Edit Donation</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.donation.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
              <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
          </a>
        </div>
        <div class="card-body pt-5 pb-5" id="edit_content">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
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
              <form id="ajaxEditForm" class="" action="{{route('admin.donation.update')}}" method="post">
                @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @foreach ($langs as $lang)
                              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                   id="edit-lang-{{$lang->code}}">
                                  @if($donation[$lang->code]->id==0)
                                      <div class="form-group">
                                          <label class="" for="">Choose association **</label>
                                          <select class="form-control select2" name="donation_assoc_id_{{$lang->code}}">
                                              <option value="" selected>Select a blog</option>
                                              @foreach ($ccates[$lang->code] as $ccate)
                                                  <option value="{{$ccate->id}}">[{{$ccate->id}}-{{$ccate->assoc_id}}
                                                      ] {{$ccate->name}}</option>
                                              @endforeach
                                          </select>
                                          <p id="errdonation_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                      </div>
                                  @else
                                      <input type="hidden" name="donation_id_{{$lang->code}}" value="{{$donation[$lang->code]->id}}">
                                  @endif
                                    <input type="hidden" name="lang_id_{{$lang->code}}" value="{{$donation[$lang->code]->lang_id}}">

                                {{-- Image Part --}}
                                <div class="form-group">
                                    <label for="">Image ** </label>
                                    <br>
                                    <div class="thumb-preview" id="thumbPreview{{$lang->id}}1">
                                        <label for="chooseImage{{$lang->id}}1"> <img src="{{$donation[$lang->code]->image!=''?asset('assets/front/img/donations/' . $donation[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="Cause Image"></label>
                                    </div>
                                    <br>
                                    <br>


                                    <input id="fileInput{{$lang->id}}1" type="hidden" name="image_{{$lang->code}}">
                                    <button id="chooseImage{{$lang->id}}1" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}1">Choose Image</button>


                                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                                </div>
                                <div class="form-group">
                                  <label for="">Title **</label>
                                  <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$donation[$lang->code]->title}}" placeholder="Enter title">
                                  <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                  <label for="">Content **</label>
                                  <textarea class="form-control summernote" name="content_{{$lang->code}}" data-height="300" placeholder="Enter content">{{convertHtml($donation[$lang->code]->content)}}</textarea>
                                  <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                  <div class="form-group">
                                      <label for="">Goal Amount (in {{$abx->base_currency_text}}) **</label>
                                      <input type="number" class="form-control ltr" name="goal_amount_{{$lang->code}}" value="{{$donation[$lang->code]->goal_amount}}" placeholder="Enter Ticket Cost">
                                      <p id="errgoal_amount_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Minimum Amount (in {{$abx->base_currency_text}}) **</label>
                                      <input type="number" class="form-control ltr" name="min_amount_{{$lang->code}}" value="{{$donation[$lang->code]->min_amount}}" placeholder="Enter Ticket Cost">
                                      <small class="text-warning">Minimum amount for this cause</small>
                                      <p id="errmin_amount_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Custom Amount (in {{$abx->base_currency_text}}) </label>
                                      <input type="text" class="form-control" name="custom_amount_{{$lang->code}}" value="{{$donation[$lang->code]->custom_amount}}" data-role="tagsinput">
                                      <small class="text-warning">Use comma (,) to seperate the amounts.</small><br>
                                      <small class="text-warning">Custom amount must be equal to or greater than minimum amount</small>
                                      <p id="errcustom_amount_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                <div class="form-group">
                                  <label for="">Meta Keywords</label>
                                  <input type="text" class="form-control" name="meta_tags_{{$lang->code}}" value="{{$donation[$lang->code]->meta_tags}}" data-role="tagsinput">
                                  <p id="errmeta_keywords_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                  <label for="">Meta Description</label>
                                  <textarea type="text" class="form-control" name="meta_description_{{$lang->code}}" rows="5">{{$donation[$lang->code]->meta_description}}</textarea>
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
