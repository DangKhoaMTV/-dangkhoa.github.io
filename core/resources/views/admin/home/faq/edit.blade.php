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
<form id="ajaxEditForm" class="" action="{{route('admin.faq.update')}}" method="POST">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($faq[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="faq_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($fcates[$lang->code] as $fcate)
                                    <option value="{{$fcate->id}}">[{{$fcate->id}}-{{$fcate->assoc_id}}
                                        ] {{$fcate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errfaq_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input id="infaq_id" type="hidden" name="faq_id_{{$lang->code}}" value="{{$faq[$lang->code]->id}}">
                    @endif

                    <div class="form-group {{ $categoryInfo->faq_category_status == 0 ? 'd-none' : '' }}">
                        <label for="">Category **</label>
                        <select name="category_id_{{$lang->code}}" id="incategory_id" class="form-control">
                            <option disabled>Select a category</option>
                            @foreach ($categories[$lang->code] as $category)
                                <option value="{{ $category->id }}" {{$category->id == $faq[$lang->code]->category_id ? 'selected' : ''}}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="eerrcategory_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Question **</label>
                        <input id="inquestion" type="text" class="form-control" name="question_{{$lang->code}}"
                               placeholder="Enter question" value="{{$faq[$lang->code]->question}}">
                        <p id="eerrquestion_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Answer **</label>
                        <textarea id="inanswer" class="form-control" name="answer_{{$lang->code}}" rows="5" cols="80"
                                  placeholder="Enter answer">{{$faq[$lang->code]->answer}}</textarea>
                        <p id="eerranswer_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input id="inserial_number" type="number" class="form-control ltr"
                               name="serial_number_{{$lang->code}}" value="{{$faq[$lang->code]->serial_number}}"
                               placeholder="Enter Serial Number">
                        <p id="eerrserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the FAQ will be
                                shown.</small></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
