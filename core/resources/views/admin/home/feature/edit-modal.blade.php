@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" action="{{route('admin.feature.update')}}" method="post">
  @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($feature[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="feature_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a feature</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errfeature_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="feature_id_{{$lang->code}}" value="{{$feature[$lang->code]->id}}">
                    @endif

                    <div class="form-group">
                        <label for="">Type **</label>
                        <select name="type_{{$lang->code}}" class="form-control" id="featureType_{{$lang->code}}">
                            <option {{$feature[$lang->code]->type == 'icon' ? 'selected' : ''}} value="icon" >Icon</option>
                            <option {{$feature[$lang->code]->type == 'image' ? 'selected' : ''}} value="image">Image</option>
                        </select>
                        <p id="errtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                    {{-- Image Part --}}
                    <div class="form-group" id="feature_image_{{$lang->code}}" @if($feature[$lang->code]->type == 'icon') style="display: none;" @endif>
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img src="{{ $feature[$lang->code]->image != '' ? asset('assets/front/img/featured/' . $feature[$lang->code]->image) : asset('assets/admin/img/noimage.jpg') }}" alt="User Image"></label>
                        </div>
                        <br>
                        <br>

                        <input id="fileInput3{{$lang->id}}" @if($feature[$lang->code]->type == 'icon') disabled @endif type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>
                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>
                    </div>

                  <div class="form-group" id="feature_icon_{{$lang->code}}"  @if($feature[$lang->code]->type == 'image') style="display: none;" @endif>
                    <label for="">Icon **</label>
                    <div class="btn-group d-block">
                        <button type="button" class="btn btn-primary iconpicker-component"><i class="{{$feature[$lang->code]->icon}}"></i></button>
                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                data-selected="fa-car" data-toggle="dropdown">
                        </button>
                        <div class="dropdown-menu"></div>
                    </div>
                    <input id="inputIcon3{{$lang->code}}"  @if($feature[$lang->code]->type == 'image') disabled @endif type="hidden" name="icon_{{$lang->code}}" value="{{$feature[$lang->code]->icon}}">
                    @if ($errors->has('icon_'.$lang->code))
                      <p class="mb-0 text-danger">{{$errors->first('icon_'.$lang->code)}}</p>
                    @endif
                    <div class="mt-2">
                      <small>NB: click on the dropdown sign to select an icon.</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Title **</label>
                    <input type="text" class="form-control" name="title_{{$lang->code}}" placeholder="Enter title" value="{{$feature[$lang->code]->title}}">
                    @if ($errors->has('title_'.$lang->code))
                      <p class="mb-0 text-danger">{{$errors->first('title_'.$lang->code)}}</p>
                    @endif
                  </div>
                  @if ($be->theme_version != 'car')
                    <div class="form-group">
                        <label>Color **</label>
                        <input type="text" class="jscolor form-control ltr" name="color_{{$lang->code}}" value="{{$feature[$lang->code]->color}}">
                        @if ($errors->has('color_'.$lang->code))
                            <p class="mb-0 text-danger">{{$errors->first('color_'.$lang->code)}}</p>
                        @endif
                    </div>
                  @endif
                  <div class="form-group">
                    <label for="">Serial Number **</label>
                    <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$feature[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                    @if ($errors->has('serial_number_'.$lang->code))
                      <p class="mb-0 text-danger">{{$errors->first('serial_number_'.$lang->code)}}</p>
                    @endif
                    <p class="text-warning"><small>The higher the serial number is, the later the feature will be shown.</small></p>
                  </div>
    </div>
    @endforeach
    </div>
    @endif
</form>

  <script>
      (function () {

          @foreach ($langs as $lang)
          $("#featureType_{{$lang->code}}").on('change', function () {
              let type = $(this).val();
              const curForm = $('#edit-lang-{{$lang->code}}')
              if (type == 'image') {
                  $("#feature_icon_{{$lang->code}} input", curForm).attr('disabled', true);
                  $("#feature_icon_{{$lang->code}}", curForm).hide();
                  $("#feature_image_{{$lang->code}}", curForm).show();
                  $("#feature_image_{{$lang->code}} input", curForm).removeAttr('disabled');
              } else {
                  $("#feature_image_{{$lang->code}} input", curForm).attr('disabled', true);
                  $("#feature_image_{{$lang->code}}", curForm).hide();
                  $("#feature_icon_{{$lang->code}}", curForm).show();
                  $("#feature_icon_{{$lang->code}} input", curForm).removeAttr('disabled');
              }
          });
          @endforeach

          $('.icp').on('iconpickerSelected', function(event){
              let form_group = $(this).closest('.form-group');
              form_group.find("input[id^='inputIcon3']").val($(".iconpicker-component",form_group).find('i').attr('class'));
          });
      })();
  </script>
