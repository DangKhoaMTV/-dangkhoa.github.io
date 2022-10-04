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
    <h4 class="page-title">Userful Links</h4>
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
        <a href="#">Footer</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Userful Links</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title d-inline-block">Userful Links</div>
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
                    <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Userful Link</a>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($aulinks) == 0)
                <h3 class="text-center">NO USEFUL LINK FOUND</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">URL</th>
                        <th scope="col">Type</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($aulinks as $key => $aulink)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{convertUtf8($aulink->name)}}</td>
                          <td>{{$aulink->url}}</td>
                          <td>{{$aulink->type}}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm editbtn_url" href="#editModal" data-toggle="modal" data-ulink_id="{{$aulink->id}}" data-name="{{$aulink->name}}" data-url="{{route('admin.ulink.edit', $aulink->id) . '?language=' . request()->input('language')}}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                              Edit
                            </a>
                            <form class="deleteform d-inline-block" action="{{route('admin.ulink.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="ulink_id" value="{{$aulink->id}}">
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


  <!-- Create Userful Link Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add Userful Link</h5>
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
          <form id="ajaxForm" class="modal-form create" action="{{route('admin.ulink.store')}}" method="POST">
            @csrf
              @if (!empty($langs))
                  <div class="tab-content">
                      @foreach ($langs as $lang)
                          <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                               id="create-lang-{{$lang->code}}">
                              @include('admin.sameContent')

                              <div class="form-group">
                              <label for="">Name **</label>
                              <input type="text" class="form-control" name="name_{{$lang->code}}" value="" placeholder="Enter name">
                              <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>

                              <div class="form-group">
                                  <label for="">Type **</label>
                                  <select name="type_{{$lang->code}}" class="form-control" id="ulinkType_{{$lang->code}}">
                                      <option value="link">Link</option>
                                      <option value="popup">Popup</option>
                                  </select>
                                  <p id="errtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                              </div>

                            <div class="form-group" id="create_url_{{$lang->code}}">
                              <label for="">URL **</label>
                              <input type="text" class="form-control ltr" name="url_{{$lang->code}}" placeholder="Enter url">
                              <p id="errurl_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group" id="create_content_{{$lang->code}}" style="display: none">
                              <label for="">Content Popup **</label>
                                <textarea disabled class="form-control ltr summernote" name="content_{{$lang->code}}" placeholder="Enter url"></textarea>
                              <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                          </div>
                    @endforeach
                  </div>
              @endif
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Userful Link Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Userful Link</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Loading...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="updateBtn" type="button" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    @foreach ($langs as $lang)
    $("#ulinkType_{{$lang->code}}").on('change', function () {
        let type = $(this).val();
        console.log(type);
        if (type == 'link') {
            $("#create_url_{{$lang->code}}").show();
            $("#create_url_{{$lang->code}} input").removeAttr('disabled');

            $("#create_content_{{$lang->code}}").hide();
            $("#create_content_{{$lang->code}} textarea").attr('disabled', true);
        } else {
            $("#create_content_{{$lang->code}}").show();
            $("#create_content_{{$lang->code}} textarea").removeAttr('disabled');

            $("#create_url_{{$lang->code}}").hide();
            $("#create_url_{{$lang->code}} input").attr('disabled', true);
        }
    });
    @endforeach

    // make input fields RTL
    $("select[name='language_id']").on('change', function() {
        $(".request-loader").addClass("show");
        let url = "{{url('/')}}/admin/rtlcheck/" + $(this).val();
        console.log(url);
        $.get(url, function(data) {
            $(".request-loader").removeClass("show");
            if (data == 1) {
                $("form.create input").each(function() {
                    if (!$(this).hasClass('ltr')) {
                        $(this).addClass('rtl');
                    }
                });
                $("form.create select").each(function() {
                    if (!$(this).hasClass('ltr')) {
                        $(this).addClass('rtl');
                    }
                });
                $("form.create textarea").each(function() {
                    if (!$(this).hasClass('ltr')) {
                        $(this).addClass('rtl');
                    }
                });
                $("form.create .nicEdit-main").each(function() {
                    $(this).addClass('rtl text-right');
                });

            } else {
                $("form.create input, form.create select, form.create textarea").removeClass('rtl');
                $("form.create .nicEdit-main").removeClass('rtl text-right');
            }
        })
    });
});
</script>
@endsection
