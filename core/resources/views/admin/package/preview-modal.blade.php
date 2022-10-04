@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            @php
                $id = $package[$lang->code]->id!=''?$package[$lang->code]->id:-$id;
            @endphp
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                   href="#preview-lang-{{$lang->code}}{{$id}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
@if (!empty($langs))
    <div class="tab-content pt-3">
        @foreach ($langs as $lang)
            @php
            $id = $package[$lang->code]->id!=''?$package[$lang->code]->id:-$id;
            @endphp
            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                 id="preview-lang-{{$lang->code}}{{$id}}">
                {!! replaceBaseUrl(convertUtf8($package[$lang->code]->description)) !!}
            </div>
        @endforeach
    </div>
@endif

