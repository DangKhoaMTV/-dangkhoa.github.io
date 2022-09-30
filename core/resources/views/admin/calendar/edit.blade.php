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
<form id="ajaxEditForm" class="" action="{{route('admin.calendar.update')}}" method="POST">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @if($calendar[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="calendar_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($ccates[$lang->code] as $ccate)
                                    <option value="{{$ccate->id}}">[{{$ccate->id}}-{{$ccate->assoc_id}}
                                        ] {{$ccate->name}}</option>
                                @endforeach
                            </select>
                            <p id="errcalendar_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input id="inevent_id_{{$lang->code}}" type="hidden" name="event_id_{{$lang->code}}" value="{{$calendar[$lang->code]->id}}">
                    @endif

                <div class="form-group">
                    <label for="">Title **</label>
                    <input id="intitle" name="title_{{$lang->code}}" class="form-control" placeholder="Enter Title" type="text" value="{{$calendar[$lang->code]->title}}">
                    <p id="eerrtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">Event Period **</label>
                    <input type="text" name="edatetimes_{{$lang->code}}" class="form-control ltr daterange-picker" placeholder="Enter Event Period" value="{{$calendar[$lang->code]->start_date}} - {{$calendar[$lang->code]->end_date}}"/>
                    <input type="hidden" id="instart_date_{{$lang->code}}" name="start_date_{{$lang->code}}" value="{{$calendar[$lang->code]->start_date}}">
                    <input type="hidden" id="inend_date_{{$lang->code}}" name="end_date_{{$lang->code}}" value="{{$calendar[$lang->code]->end_date}}">
                    <p id="eerrstart_date_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    <p id="eerrend_date_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                </div>
        @endforeach
        </div>
        @endif
</form>
<script type="text/javascript">

    (function () {
        init_daterange();
    })();
</script>
