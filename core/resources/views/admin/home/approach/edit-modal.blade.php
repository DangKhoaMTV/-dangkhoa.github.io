@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" action="{{route('admin.approach.point.update')}}" method="post" >

        @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @if($point[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="point_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a feature</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errpoint_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="pointid_{{$lang->code}}" value="{{$point[$lang->code]->id}}">
                    @endif

        <div class="form-group">
          <label for="">Social Icon **</label>
          <div class="btn-group d-block">
              <button type="button" class="btn btn-primary iconpicker-component"><i class="{{$point[$lang->code]->icon}}"></i></button>
              <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                      data-selected="fa-car" data-toggle="dropdown">
              </button>
              <div class="dropdown-menu"></div>
          </div>
          <input id="inputIcon{{$lang->code}}" type="hidden" name="icon_{{$lang->code}}" value="{{$point[$lang->code]->icon}}">
          @if ($errors->has('icon_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('icon_'.$lang->code)}}</p>
          @endif
          <div class="mt-2">
            <small>NB: click on the dropdown sign to select an icon.</small>
          </div>
        </div>

        @if ($be->theme_version == 'cleaning')
            <div class="form-group">
                <label for="">Color **</label>
                <input type="text" class="form-control jscolor" name="color_{{$lang->code}}" value="{{$point[$lang->code]->color}}">
                @if ($errors->has('color_'.$lang->code))
                  <p class="mb-0 text-danger">{{$errors->first('color_'.$lang->code)}}</p>
                @endif
            </div>
        @endif

        <div class="form-group">
          <label for="">Title **</label>
          <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$point[$lang->code]->title}}" placeholder="Enter Title">
          @if ($errors->has('title_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('title_'.$lang->code)}}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="">Short Text **</label>
          <input type="text" class="form-control" name="short_text_{{$lang->code}}" value="{{$point[$lang->code]->short_text}}" placeholder="Enter Short Text">
          @if ($errors->has('short_text_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('short_text_'.$lang->code)}}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="">Serial Number **</label>
          <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$point[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
          @if ($errors->has('serial_number_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('serial_number_'.$lang->code)}}</p>
          @endif
          <p class="text-warning"><small>The higher the serial number is, the later the point will be shown in approach section.</small></p>
        </div>
                </div>
            @endforeach
        </div>
    @endif
</form>

  <script>
      (function () {
          $('.icp').on('iconpickerSelected', function(event){
              let form_group = $(this).closest('.form-group');
              form_group.find("input[id^='inputIcon']").val($(".iconpicker-component",form_group).find('i').attr('class'));
          });
      })();
  </script>
