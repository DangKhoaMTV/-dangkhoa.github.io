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
<form id="ajaxEditForm" action="{{route('admin.product.attribute_update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    <input type="hidden" name="attribute_id_{{$lang->code}}" value="{{$attribute[$lang->code]->id}}">
                    @include('admin.sameContent')
                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Type **</label>
                        <select name="icon_type_{{$lang->code}}" class="form-control" id="edit_attribute_type_{{$lang->code}}">
                            <option {{$attribute[$lang->code]->icon_type == 'icon' ? 'selected' : ''}} value="icon" >Icon</option>
                            <option {{$attribute[$lang->code]->icon_type == 'image' ? 'selected' : ''}} value="image">Image</option>
                        </select>
                        <p id="erricon_type_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group" id="edit_attribute_image_{{$lang->code}}" @if($attribute[$lang->code]->icon_type == 'icon') style="display: none;" @endif>
                        <label for="">Icon ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img src="{{$attribute[$lang->code]->icon!=''?asset('assets/front/img/product_attribute/' . $attribute[$lang->code]->icon):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                        </div>
                        <br>
                        <br>

                        <input id="fileInput3{{$lang->id}}" type="hidden" name="icon_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>
                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="erricon_{{$lang->code}}"></p>
                    </div>
                    <div class="form-group" id="edit_attribute_icon_{{$lang->code}}"  @if($attribute[$lang->code]->icon_type == 'image') style="display: none;" @endif>
                        <label for="">Icon **</label>
                        <div class="btn-group d-block">
                            <button type="button" class="btn btn-primary iconpicker-component"><i class="{{$attribute[$lang->code]->icon}}"></i></button>
                            <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                    data-selected="fa-car" data-toggle="dropdown">
                            </button>
                            <div class="dropdown-menu"></div>
                        </div>
                        <input id="inputIcon_3{{$lang->code}}"  @if($attribute[$lang->code]->icon_type == 'image') disabled @endif type="hidden" name="icon_{{$lang->code}}" value="{{$attribute[$lang->code]->icon}}">
                        @if ($errors->has('icon_'.$lang->code))
                            <p class="mb-0 text-danger">{{$errors->first('icon_'.$lang->code)}}</p>
                        @endif
                        <div class="mt-2">
                            <small>NB: click on the dropdown sign to select an icon.</small>
                        </div>
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
<script>
    (function () {

        @foreach ($langs as $lang)
        $("#edit_attribute_type_{{$lang->code}}").on('change', function () {
            let type = $(this).val();
            if (type == 'image') {
                $("#edit_attribute_icon_{{$lang->code}} input").attr('disabled', true);
                $("#edit_attribute_icon_{{$lang->code}}").hide();
                $("#edit_attribute_image_{{$lang->code}}").show();
                $("#edit_attribute_image_{{$lang->code}} input").removeAttr('disabled');
            } else {
                $("#edit_attribute_image_{{$lang->code}} input").attr('disabled', true);
                $("#edit_attribute_image_{{$lang->code}}").hide();
                $("#edit_attribute_icon_{{$lang->code}}").show();
                $("#edit_attribute_icon_{{$lang->code}} input").removeAttr('disabled');
            }
        });
        @endforeach

        $('.icp').on('iconpickerSelected', function(event){
            let form_group = $(this).closest('.form-group');
            form_group.find("input[id^='inputIcon']").val($(".iconpicker-component",form_group).find('i').attr('class'));
        });
    })();
</script>
