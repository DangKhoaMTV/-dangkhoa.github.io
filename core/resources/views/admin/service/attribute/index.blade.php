@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Service Attribute</h4>
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
                <a href="#">Basic Settings</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Service Attribute</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">Service Attribute</div>
                        </div>
                        <div class="col-lg-3">
                            @if (!empty($langs))
                                <select name="language" class="form-control"
                                        onchange="window.location = this.value">
                                    <option value="" selected disabled>Select a Language</option>
                                    @foreach ($langs as $lang)
                                        <option
                                            value="{{url()->current() . '?language='. $lang->code }}{{ request()->input('type') ? '&type='.request()->input('type') : '' }}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>
                                            {{$lang->name}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-lg-3 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                               data-target="#createModal"><i class="fas fa-plus"></i> Add Attribute</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($socials) == 0)
                                <h2 class="text-center">NO LINK ADDED</h2>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Icon</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Serial Number</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($attributes as $key => $attribute)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td><img style="
    background: #fff;
" src="{{ asset('assets/front/img/service_attribute/'.$attribute->icon) }}"/></td>
                                                <td>{{$attribute->name}}</td>
                                                <td>{{$attribute->serial_number}}</td>
                                                <td>
                                                    <a class="btn btn-secondary btn-sm editbtn_url"
                                                       href="{{route('admin.service.attribute_edit', $attribute->id) . '?language=' . request()->input('language')}}"
                                                       data-url="{{route('admin.service.attribute_edit', $attribute->id) . '?language=' . request()->input('language')}}"
                                                       data-toggle="modal" data-target="#editModal">
                                <span class="btn-label">
                                    <i class="fas fa-edit"></i>
                                </span>
                                                        Edit
                                                    </a>
                                                    <form class="d-inline-block deleteform"
                                                          action="{{route('admin.service.attribute_delete')}}"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="attribute_id"
                                                               value="{{$attribute->id}}">
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

    <!-- Create Gallery Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Service Attribute</h5>
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
                    <form id="ajaxForm" action="{{route('admin.service.attribute_store')}}" method="post">
                        @csrf
                        @if (!empty($langs))
                            <div class="tab-content">
                                @foreach ($langs as $lang)
                                    <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                         id="create-lang-{{$lang->code}}">
                                        @include('admin.sameContent')

                                        {{-- Image Part --}}
                                        <div class="form-group">
                                            <label for="">Icon ** </label>
                                            <br>
                                            <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                                                <label for="chooseImage1{{$lang->id}}"><img
                                                        src="{{asset('assets/admin/img/noimage.jpg')}}"
                                                        alt="User Image"></label>
                                            </div>
                                            <br>
                                            <br>

                                            <input id="fileInput1{{$lang->id}}" type="hidden" name="icon_{{$lang->code}}">
                                            <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button"
                                                    data-multiple="false"
                                                    data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image
                                            </button>
                                            <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                            <p class="em text-danger mb-0" id="erricon_{{$lang->code}}"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Name **</label>
                                            <input type="text" class="form-control" name="name_{{$lang->code}}" value=""
                                                   placeholder="Enter name">
                                            <p class="em text-danger mb-0" id="errname_{{$lang->code}}"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Serial Number **</label>
                                            <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value=""
                                                   placeholder="Enter Serial Number">
                                            <p class="em text-danger mb-0" id="errserial_number_{{$lang->code}}"></p>
                                            <p class="text-warning"><small>The higher the serial number is, the later the
                                                    service attribute will be shown.</small></p>
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

    <!-- Edit Gallery Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Service Attribute</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="updateBtn" type="button" class="btn btn-primary">Update</button>
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


@section('scripts')
@endsection
