@extends('admin.layout')

@if(!empty($shipping->language) && $shipping->language->rtl == 1)
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
        <h4 class="page-title">Edit Shipping Charge</h4>
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
                <a href="#">Shipping</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Shipping Charge</a>
            </li>
            <li class="nav-item">
                <a href="#">Edit</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">Edit Category</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                       href="{{route('admin.shipping.index') . '?language=' . request()->input('language')}}">
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
                                            <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                               data-toggle="tab"
                                               href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <form id="ajaxEditForm" class="modal-form" action="{{route('admin.shipping.update')}}"
                                  method="POST">
                                @csrf
                                @if (!empty($langs))
                                    <div class="tab-content">
                                        @foreach ($langs as $lang)
                                            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                                                @if($shipping[$lang->code]->id==0)
                                                    <div class="form-group">
                                                        <label class="" for="">Choose association **</label>
                                                        <select class="form-control select2"
                                                                name="shipping_assoc_id_{{$lang->code}}">
                                                            <option value="" selected>Select a blog</option>
                                                            @foreach ($scates[$lang->code] as $scate)
                                                                <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->title}}</option>
                                                            @endforeach
                                                        </select>
                                                        <p id="errshipping_assoc_id_{{$lang->code}}"
                                                           class="mb-0 text-danger em"></p>
                                                    </div>
                                                @else
                                                    <input type="hidden" value="{{$shipping[$lang->code]->id}}"
                                                           name="shipping_id_{{$lang->code}}">
                                                @endif


                                                <div class="form-group">
                                                    <label for="">Title **</label>
                                                    <input type="text" class="form-control" name="title_{{$lang->code}}"
                                                           value="{{$shipping[$lang->code]->title}}"
                                                           placeholder="Enter title">
                                                    <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Sort Text **</label>
                                                    <input type="text" class="form-control" name="text_{{$lang->code}}"
                                                           value="{{$shipping[$lang->code]->text}}"
                                                           placeholder="Enter text">
                                                    <p id="errtext_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Charge ({{$bex->base_currency_text}}) **</label>
                                                    <input type="text" class="form-control ltr"
                                                           name="charge_{{$lang->code}}"
                                                           value="{{$shipping[$lang->code]->charge}}"
                                                           placeholder="Enter charge">
                                                    <p id="errcharge_{{$lang->code}}" class="mb-0 text-danger em"></p>
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
