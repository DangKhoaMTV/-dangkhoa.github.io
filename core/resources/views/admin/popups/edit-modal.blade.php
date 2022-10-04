
<form id="ajaxEditForm" class="modal-form" action="{{route('admin.popup.update')}}" method="post">
    @csrf
    <input type="hidden" name="popup_id" value="{{$popup->id}}">
    <input type="hidden" name="type" value="{{$type}}">

    @if ($type == 1 || $type == 4 || $type == 5 || $type == 7)

        {{-- Image Part --}}
        <div class="form-group">
            <label for="">Image ** </label>
            <br>
            <div class="thumb-preview" id="thumbPreview1">
                <img src="{{$popup->image!=''?asset('assets/front/img/popups/' . $popup->image):asset('assets/admin/img/noimage.jpg')}}" alt="Image">
            </div>
            <br>
            <br>


            <input id="fileInput1" type="hidden" name="image">
            <button id="chooseImage1" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal1">Choose Image</button>


            <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
            <p class="em text-danger mb-0" id="errimage"></p>

        </div>
    @endif

    @if ($type == 2 || $type == 3 || $type == 6)
    {{-- Background Image Part --}}
    <div class="form-group">
        <label for="">Background Image ** </label>
        <br>
        <div class="thumb-preview" id="thumbPreview2">
            <img src="{{$popup->background_image!=''?asset('assets/front/img/popups/' . $popup->background_image):asset('assets/admin/img/noimage.jpg')}}" alt="Background Image">
        </div>
        <br>
        <br>


        <input id="fileInput2" type="hidden" name="background_image">
        <button id="chooseImage2" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal2">Choose Image</button>


        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
        <p class="em text-danger mb-0" id="errbackground_image"></p>

    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="">Popup Name **</label>
                <input type="text" class="form-control" name="name" value="{{$popup->name}}" placeholder="Enter Name">
                <p class="text-warning mb-0">This will not be shown in the popup in Website, it will help you to indentify the popup in Admin Panel.</p>
                <p id="errname" class="mb-0 text-danger em"></p>
            </div>
        </div>
    </div>


    @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="">Title </label>
                <input type="text" class="form-control" name="title" value="{{$popup->title}}" placeholder="Enter Title">
                <p id="errtitle" class="mb-0 text-danger em"></p>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="">Text </label>
                <textarea class="form-control" name="text" cols="30" rows="3" placeholder="Enter Text">{{$popup->text}}</textarea>
                <p id="errtext" class="mb-0 text-danger em"></p>
            </div>
        </div>
    </div>
    @endif

    @if ($type == 6 || $type == 7)
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">End Date **</label>
                <input type="text" class="form-control ltr datepicker" name="end_date" value="{{$popup->end_date}}" placeholder="Enter End Date" autocomplete="off">
                <p id="errend_date" class="mb-0 text-danger em"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">End Time **</label>
                <input type="text" class="form-control ltr timepicker" name="end_time" value="{{$popup->end_time}}" placeholder="Enter End Time" autocomplete="off">
                <p id="errend_time" class="mb-0 text-danger em"></p>
            </div>
        </div>
    </div>
    @endif

    @if ($type == 2 || $type == 3)
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Background Color Code **</label>
                <input class="jscolor form-control ltr" name="background_color" value="{{$popup->background_color}}">
                <p class="em text-danger mb-0" id="errbackground_color"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Background Color Opacity **</label>
                <input type="number" class="form-control ltr" name="background_opacity" value="{{$popup->background_opacity}}" placeholder="Enter Opacity Value">
                <p id="errbackground_opacity" class="mb-0 text-danger em"></p>
                <ul class="mb-0">
                    <li class="text-warning mb-0">Value must be between 0 to 1</li>
                    <li class="text-warning mb-0">The more the opacity value is, the less the trnsparency level will be.</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    @if ($type == 7)
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>Background Color Code **</label>
                <input class="jscolor form-control ltr" name="background_color" value="{{$popup->background_color}}">
                <p class="em text-danger mb-0" id="errbackground_color"></p>
            </div>
        </div>
    </div>
    @endif

    @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Button Text </label>
                <input type="text" class="form-control" name="button_text" value="{{$popup->button_text}}" placeholder="Enter Button Text">
                <p id="errbutton_text" class="mb-0 text-danger em"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Button Color </label>
                <input type="text" class="form-control jscolor ltr" name="button_color" value="{{$popup->button_color}}" placeholder="Enter Button Color">
                <p id="errbutton_color" class="mb-0 text-danger em"></p>
            </div>
        </div>
    </div>
    @endif

    @if ($type == 2 || $type == 4 || $type == 6 || $type == 7)
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Button URL </label>
                    <input type="text" class="form-control ltr" name="button_url" value="{{$popup->button_url}}" placeholder="Enter Button URL">
                    <p id="errbutton_url" class="mb-0 text-danger em"></p>
                </div>
            </div>
        </div>
    @endif

    <div class="form-group">
        <label for="">Delay (miliseconds) **</label>
        <input type="number" class="form-control ltr" name="delay" value="{{$popup->delay}}" placeholder="Enter Serial Number">
        <p id="errdelay" class="mb-0 text-danger em"></p>
        <p class="text-warning mb-0">This will decide the delay time to show the popup</p>
    </div>
    <div class="form-group">
        <label for="">Serial Number **</label>
        <input type="number" class="form-control ltr" name="serial_number" value="{{$popup->serial_number}}" placeholder="Enter Serial Number">
        <p id="errserial_number" class="mb-0 text-danger em"></p>
        <ul>
            <li class="text-warning mb-0">If there are <strong class="text-info">Multiple Active Popups</strong>, then the popups will be shown in the website according to <strong class="text-info">Serial Number</strong></li>
            <li class="text-warning">The higher the serial number, the later the popups will be visible in Website</li>
        </ul>
    </div>
</form>

