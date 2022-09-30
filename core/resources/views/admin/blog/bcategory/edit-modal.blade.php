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
<form id="ajaxEditForm" class="" action="{{route('admin.bcategory.update')}}" method="post">
    @csrf

    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($bcategory[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="bcategory_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($bcates[$lang->code] as $bcate)
                                    <option value="{{$bcate->id}}">[{{$bcate->id}}-{{$bcate->assoc_id}}
                                        ] {{$bcate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errbcategory_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="bcategory_id_{{$lang->code}}"
                               value="{{$bcategory[$lang->code]->id}}">
                    @endif
                        <div class="form-group">
                            <label for="">Name **</label>
                            <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$bcategory[$lang->code]->name}}" placeholder="Enter name">
                            <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">Status **</label>
                            <select class="form-control ltr" name="status_{{$lang->code}}">
                                <option value="" selected disabled>Select a status</option>
                                <option @if ($bcategory[$lang->code]->status == 1) selected @endif value="1">Active</option>
                                <option @if ($bcategory[$lang->code]->status == 0) selected @endif  value="0">Deactive</option>
                            </select>
                            <p id="errstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">Serial Number **</label>
                            <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$bcategory[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                            <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            <p class="text-warning"><small>The higher the serial number is, the later the blog category will be shown.</small></p>
                        </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
