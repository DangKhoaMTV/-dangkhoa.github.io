@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" action="{{route('admin.statistics.update')}}" method="post">
        @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($statistic[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="statistic_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a statistic</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errstatistic_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="statisticid_{{$lang->code}}" value="{{$statistic[$lang->code]->id}}">
                    @endif
        <div class="form-group">
          <label for="">Social Icon **</label>
          <div class="btn-group d-block">
              <button type="button" class="btn btn-primary iconpicker-component"><i class="{{$statistic[$lang->code]->icon}}"></i></button>
              <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                      data-selected="fa-car" data-toggle="dropdown">
              </button>
              <div class="dropdown-menu"></div>
          </div>
          <input id="inputIcon3{{$lang->code}}" type="hidden" name="icon_{{$lang->code}}" value="{{$statistic[$lang->code]->icon}}">
          @if ($errors->has('icon_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('icon_'.$lang->code)}}</p>
          @endif
          <div class="mt-2">
            <small>NB: click on the dropdown sign to select an icon.</small>
          </div>
        </div>
        <div class="form-group">
          <label for="">Title **</label>
          <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$statistic[$lang->code]->title}}" placeholder="Enter Title">
          @if ($errors->has('title_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('title_'.$lang->code)}}</p>
          @endif
        </div>
        <div class="form-group">
           <label for="">Quantity **</label>
           <div class="input-group @if(!empty($selLang) && $selLang->rtl == 1) rtl @endif">
              <input type="text" class="form-control" name="quantity_{{$lang->code}}" value="{{$statistic[$lang->code]->quantity}}" placeholder="Enter Quantity" aria-describedby="basic-addon2">
              <div class="input-group-append">
                 <span class="input-group-text" id="basic-addon2">+</span>
              </div>
           </div>
           @if ($errors->has('quantity_'.$lang->code))
             <p class="mb-0 text-danger">{{$errors->first('quantity_'.$lang->code)}}</p>
           @endif
        </div>
        <div class="form-group">
          <label for="">Serial Number **</label>
          <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$statistic[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
          @if ($errors->has('serial_number_'.$lang->code))
            <p class="mb-0 text-danger">{{$errors->first('serial_number_'.$lang->code)}}</p>
          @endif
          <p class="text-warning"><small>The higher the serial number is, the later the statistic will be shown.</small></p>
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
              form_group.find("input[id^='inputIcon3']").val($(".iconpicker-component",form_group).find('i').attr('class'));
          });
      })();
  </script>
