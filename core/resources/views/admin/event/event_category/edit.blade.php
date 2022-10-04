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
<form id="ajaxEditForm" class="" action="{{route('admin.event.category.update')}}" method="POST">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @if($category[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="event_category_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($ecates[$lang->code] as $ecate)
                                    <option value="{{$ecate->id}}">[{{$ecate->id}}-{{$ecate->assoc_id}}
                                        ] {{$ecate->name}}</option>
                                @endforeach
                            </select>
                            <p id="errevent_category_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input id="inbcategory_id_{{$lang->code}}" type="hidden" name="event_category_id_{{$lang->code}}" value="{{$category[$lang->code]->id}}">
                    @endif

                <div class="form-group">
                    <label for="">Name **</label>
                    <input id="inname_{{$lang->code}}" type="name" class="form-control" name="name_{{$lang->code}}" value="{{$category[$lang->code]->name}}" placeholder="Enter name">
                    <p id="eerrname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">Status **</label>
                    <select id="instatus_{{$lang->code}}" class="form-control" name="status_{{$lang->code}}">
                        <option value="" disabled>Select a status</option>
                        <option @if ($category[$lang->code]->status == 1) selected @endif value="1">Active</option>
                        <option @if ($category[$lang->code]->status == 0) selected @endif  value="0">Deactive</option>
                    </select>
                    <p id="eerrstatus" class="mb-0 text-danger em"></p>
                </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
