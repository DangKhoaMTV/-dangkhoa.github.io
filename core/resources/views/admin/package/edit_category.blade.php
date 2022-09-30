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
<form id="ajaxEditForm" class="modal-form" action="{{ route('admin.package.update_category') }}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @if($pcategory[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="pcategory_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($pcates[$lang->code] as $pcate)
                                    <option value="{{$pcate->id}}">[{{$pcate->id}}-{{$pcate->assoc_id}}
                                        ] {{$pcate->name}}</option>
                                @endforeach
                            </select>
                            <p id="errpcategory_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="categoryId_{{$lang->code}}" id="inid_{{$lang->code}}" value="{{$pcategory[$lang->code]->id}}">
                    @endif


                    <div class="form-group">
                        <label for="">Category Name*</label>
                        <input type="text" id="inname_{{$lang->code}}" class="form-control" name="name_{{$lang->code}}" placeholder="Enter Category Name" value="{{$pcategory[$lang->code]->name}}">
                        <p id="eerrname_{{$lang->code}}" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">Category Status*</label>
                        <select name="status_{{$lang->code}}" id="instatus_{{$lang->code}}" class="form-control ltr">
                            <option disabled>Select a Status</option>
                            <option @if ($pcategory[$lang->code]->status == 1) selected @endif value="1">Active</option>
                            <option @if ($pcategory[$lang->code]->status == 0) selected @endif value="0">Deactive</option>
                        </select>
                        <p id="eerrstatus_{{$lang->code}}" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">Category Serial Number*</label>
                        <input type="number" id="inserial_number_{{$lang->code}}" class="form-control ltr" name="serial_number_{{$lang->code}}" placeholder="Enter Category Serial Number" value="{{$pcategory[$lang->code]->serial_number}}">
                        <p id="eerrserial_numbe_{{$lang->code}}r" class="mt-1 mb-0 text-danger em"></p>
                        <p class="text-warning mt-2">
                            <small>The higher the serial number is, the later the category will be shown.</small>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
