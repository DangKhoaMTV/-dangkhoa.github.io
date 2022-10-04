@extends('admin.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">Page Headings</h4>
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
            <a href="#">Page Headings</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="card-title">Update Permalinks</div>
                    </div>
            </div>
        </div>

        <div class="card-body pt-5 pb-5">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    @if (!empty($langs))
                        <ul class="nav nav-tabs">
                            @foreach ($langs as $lang)
                                <li class="nav-item">
                                    <a class="nav-link {{$lang->code == $langs[0]->code ? 'active' : ''}}" data-toggle="tab" href="#lang-{{$lang->code}}">{{$lang->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <form class="" action="{{route('admin.permalinks.update')}}" method="post" id="permalinkForm">
                        @csrf
                        @if (!empty($langs))
                            <div class="tab-content">
                                @foreach ($langs as $lang)
                                    <div class="tab-pane container {{$lang->code == $langs[0]->code ? 'active' : ''}}" id="lang-{{$lang->code}}">
                        <div class="row">
                            @foreach ($permalinks[$lang->code] as $pl)

                                @php
                                    $type = $pl->type;
                                    $permalink = $pl->permalink;
                                    $details = $pl->details;
                                @endphp
                                <div class="form-group col-lg-6">
                                    <label class="text-capitalize">{{str_replace("_"," ",$type)}} **</label>
                                    <input class="form-control" name="{{$lang->code}}_{{$type}}" value="{{empty(old($lang->code.'_'."$type")) ? $permalink : old($lang->code.'_'."$type")}}">

                                    @if ($details == 0)
                                        <span class="text-warning"><strong class="text-info">Preview:</strong> {{url("$permalink")}}</span>
                                    @elseif ($details == 1)
                                        @if ($type == 'package_order')
                                            <span class="text-warning"><strong class="text-info">Preview:</strong> {{url("$permalink/{id}")}}</span>
                                        @elseif ($type == 'rss_details')
                                            <span class="text-warning"><strong class="text-info">Preview:</strong> {{url("$permalink/{title-slug}/{id}")}}</span>
                                        @else
                                            <span class="text-warning"><strong class="text-info">Preview:</strong> {{url("$permalink/{title-slug}")}}</span>
                                        @endif
                                    @endif

                                    @if ($errors->has("$type"))
                                    <p class="mb-0 text-danger">{{$errors->first("$type")}}</p>
                                    @endif
                                </div>

                            @endforeach
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
                        <button type="submit" form="permalinkForm" class="btn btn-success">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
