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
<form id="ajaxEditForm" action="{{route('admin.service.attribute_update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    <input type="hidden" name="attribute_id_{{$lang->code}}" value="{{$attribute[$lang->code]->id}}">
                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Icon ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img src="{{$attribute[$lang->code]->icon!=''?asset('assets/front/img/service_attribute/' . $attribute[$lang->code]->icon):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                        </div>
                        <br>
                        <br>

                        <input id="fileInput3{{$lang->id}}" type="hidden" name="icon_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>
                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="erricon_{{$lang->code}}"></p>
                    </div>

                    <div class="form-group">
                        <label for="">Name **</label>
                        <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$attribute[$lang->code]->name}}" placeholder="Enter name">
                        <p class="em text-danger mb-0" id="errname_{{$lang->code}}"></p>
                    </div>

                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$attribute[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                        <p class="em text-danger mb-0" id="errserial_number_{{$lang->code}}"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the attribute will be shown.</small></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
