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
<form id="ajaxEditForm" class="" action="{{route('admin.jcategory.update')}}" method="POST">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @if($jcategory[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="jcategory_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($jcates[$lang->code] as $jcate)
                                    <option value="{{$jcate->id}}">[{{$jcate->id}}-{{$jcate->assoc_id}}
                                        ] {{$jcate->name}}</option>
                                @endforeach
                            </select>
                            <p id="errjcategory_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input id="injcategory_id" type="hidden" name="jcategory_id_{{$lang->code}}" value="{{$jcategory[$lang->code]->id}}">
                    @endif

                <div class="form-group">
                    <label for="">Name **</label>
                    <input id="inname" type="text" class="form-control" name="name_{{$lang->code}}" value="{{$jcategory[$lang->code]->name}}" placeholder="Enter name">
                    <p id="eerrname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">Status **</label>
                    <select id="instatus" class="form-control ltr" name="status_{{$lang->code}}">
                        <option value="" selected disabled>Select a status</option>
                        <option  @if ($jcategory[$lang->code]->status == 1) selected @endif  value="1">Active</option>
                        <option @if ($jcategory[$lang->code]->status == 0) selected @endif  value="0">Deactive</option>
                    </select>
                    <p id="eerrstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">Serial Number **</label>
                    <input id="inserial_number" type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$jcategory[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                    <p id="eerrserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    <p class="text-warning"><small>The higher the serial number is, the later the job category will be shown.</small></p>
                </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
