@if($lang->code != request()->input('language'))
    <div class="form-group">
        <label class="control-label" for="same_content_{{$lang->code}}">Same content</label>
        <input id="same_content_{{$lang->code}}"
               data-lang_code="{{$lang->code}}"
               data-lang_id="{{$lang->id}}"
               data-default_lang_code="{{$langs[0]->code}}"
               data-default_lang_id="{{$langs[0]->id}}"
               type="checkbox" value="1"
        >
    </div>
@endif
