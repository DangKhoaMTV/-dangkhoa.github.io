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
<form id="ajaxEditForm" action="{{route('admin.ulink.update')}}" method="POST">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($ulink[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="gallery_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($ucates[$lang->code] as $ucate)
                                    <option value="{{$ucate->id}}">[{{$ucate->id}}-{{$ucate->assoc_id}}
                                        ] {{$ucate->title}}</option>
                                @endforeach
                            </select>
                            <p id="eerrgallery_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input id="inulink_id_{{$lang->code}}" type="hidden" name="ulink_id_{{$lang->code}}" value="{{$ulink[$lang->code]->id}}">
                    @endif

                    <div class="form-group">
                        <label for="">Name **</label>
                        <input id="inname_{{$lang->code}}" type="text" class="form-control" name="name_{{$lang->code}}" value="{{$ulink[$lang->code]->name}}" placeholder="Enter name">
                        <p id="eerrname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                        <div class="form-group">
                            <label for="">Type **</label>
                            <select name="type_{{$lang->code}}" class="form-control" id="eulinkType_{{$lang->code}}">
                                <option @if($ulink[$lang->code]->type == 'link') selected @endif value="link">Link</option>
                                <option @if($ulink[$lang->code]->type == 'popup') selected @endif value="popup">Popup</option>
                            </select>
                            <p id="errtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>


                        <div class="form-group" id="edit_url_{{$lang->code}}" @if($ulink[$lang->code]->type == 'popup') style="display: none" @endif>
                            <label for="">URL **</label>
                            <input type="text" id="inurl_{{$lang->code}}" @if($ulink[$lang->code]->type == 'popup') disabled @endif class="form-control ltr" name="url_{{$lang->code}}" placeholder="Enter url" value="{{$ulink[$lang->code]->url}}">
                            <p id="eerrurl_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group" id="edit_content_{{$lang->code}}" @if($ulink[$lang->code]->type == 'link') style="display: none" @endif>
                            <label for="">Content Popup **</label>
                            <textarea @if($ulink[$lang->code]->type == 'link') disabled @endif class="form-control ltr summernote" name="content_{{$lang->code}}" placeholder="Enter url">{{$ulink[$lang->code]->content}}</textarea>
                            <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
<script>
    (function () {
        @foreach ($langs as $lang)
        $("#eulinkType_{{$lang->code}}").on('change', function () {
            let type = $(this).val();
            console.log(type);
            if (type == 'link') {
                $("#edit_url_{{$lang->code}}").show();
                $("#edit_url_{{$lang->code}} input").removeAttr('disabled');

                $("#edit_content_{{$lang->code}}").hide();
                $("#edit_content_{{$lang->code}} textarea").attr('disabled', true);
            } else {
                $("#edit_content_{{$lang->code}}").show();
                $("#edit_content_{{$lang->code}} textarea").removeAttr('disabled');

                $("#edit_url_{{$lang->code}}").hide();
                $("#edit_url_{{$lang->code}} input").attr('disabled', true);
            }
        });
        @endforeach
    })();
</script>
