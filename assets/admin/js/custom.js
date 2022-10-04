$(function ($) {
  "use strict";

  // Sidebar Search

  $(".sidebar-search").on('input', function() {
    let term = $(this).val().toLowerCase();
    // console.log('Term: ', term);

    if (term.length > 0) {
      $(".sidebar ul li.nav-item").each(function(i) {
        let menuName = $(this).find("p").text().toLowerCase();
        let $mainMenu = $(this);

        // if any main menu is matched
        if (menuName.indexOf(term) > -1) {
          $mainMenu.removeClass('d-none');
          $mainMenu.addClass('d-block');
        } else {
          let matched = 0;
          let count = 0;
          // search sub-items of the current main menu (which is not matched)
          $mainMenu.find('span.sub-item').each(function(i) {
            // if any sub-item is matched  of the current main menu, set the flag
            if ($(this).text().toLowerCase().indexOf(term) > -1) {
              count++;
              matched = 1;
            }
          });
          
          
          // if any sub-item is matched  of the current main menu (which is not matched)
          if (matched == 1) {
            $mainMenu.removeClass('d-none');
            $mainMenu.addClass('d-block');
          } else {
            $mainMenu.removeClass('d-block');
            $mainMenu.addClass('d-none');
          }
        }
      });
    } else {
      $(".sidebar ul li.nav-item").addClass('d-block');
    }
  });


  /* ***************************************************************
  ==========disabling default behave of form submits start==========
  *****************************************************************/
  $("#ajaxEditForm").attr('onsubmit', 'return false');
  $("#ajaxForm").attr('onsubmit', 'return false');
  /* *************************************************************
  ==========disabling default behave of form submits end==========
  ***************************************************************/
  
  /* ***************************************************
  ==========datatables start==========
  ******************************************************/
  $('#basic-datatables').DataTable({stateSave: true,
  });
  /* ***************************************************
  ==========datatables end==========
  ******************************************************/
  
  
  /* ***************************************************
  ==========bootstrap datepicker & timepicker start==========
  ******************************************************/
  $('.datepicker').datepicker({
    autoclose: true
  });
  $('input.timepicker').timepicker({
    timeFormat: 'H:mm p',
    interval: 30
  });
  /* ***************************************************
  ==========bootstrap datepicker & timepicker end==========
  ******************************************************/
  
  
  // select2
  $('.select2').select2();
  
  
  
  /* ***************************************************
  ==========dm uploader single file upload start==========
  ******************************************************/
  
  function ui_single_update_active(element, active) {
    element.find('div.progress').toggleClass('d-none', !active);
    element.find('.progressbar').toggleClass('d-none', active);
    
    element.find('input[type="file"]').prop('disabled', active);
    element.find('.btn').toggleClass('disabled', active);
    
    element.find('.btn i').toggleClass('fa-circle-o-notch fa-spin', active);
    element.find('.btn i').toggleClass('fa-folder-o', !active);
  }
  
  function ui_single_update_progress(element, percent, active) {
    active = (typeof active === 'undefined' ? true : active);
    
    var bar = element.find('div.progress-bar');
    
    bar.width(percent + '%').attr('aria-valuenow', percent);
    bar.toggleClass('progress-bar-striped progress-bar-animated', active);
    
    if (percent === 0) {
      bar.html('');
    } else {
      bar.html(percent + '%');
    }
  }
  
  function ui_single_update_status(element, message, color) {
    color = (typeof color === 'undefined' ? 'muted' : color);
    
    element.find('small.status').prop('class', 'status text-' + color).html(message);
  }
  
  
  
  $('.drag-and-drop-zone').each(function (i) {
    let $this = $(this);
    $this.dmUploader({ //
      url: $this.attr('action'),
      multiple: false,
      allowedTypes: 'image/*',
      extFilter: ['jpg', 'jpeg', 'png'],
      onDragEnter: function () {
        // Happens when dragging something over the DnD area
        this.addClass('active');
      },
      onDragLeave: function () {
        // Happens when dragging something OUT of the DnD area
        this.removeClass('active');
      },
      onInit: function () {
        // Plugin is ready to use
        
        this.find('.progressbar').val('');
      },
      onComplete: function () {
        // All files in the queue are processed (success or error)
      },
      onNewFile: function (id, file) {
        // When a new file is added using the file selector or the DnD area
        
        if (typeof FileReader !== "undefined") {
          var reader = new FileReader();
          var img = this.find('img');
          
          reader.onload = function (e) {
            img.attr('src', e.target.result);
          }
          reader.readAsDataURL(file);
        }
      },
      onBeforeUpload: function (id) {
        // about tho start uploading a file
        ui_single_update_progress(this, 0, true);
        ui_single_update_active(this, true);
        
        ui_single_update_status(this, 'Uploading...');
      },
      onUploadProgress: function (id, percent) {
        // Updating file progress
        ui_single_update_progress(this, percent);
      },
      onUploadSuccess: function (id, data) {
        var response = JSON.stringify(data);
        
        let ems = document.getElementsByClassName('em');
        for (let i = 0; i < ems.length; i++) {
          ems[i].innerHTML = '';
        }
        
        // A file was successfully uploaded
        // console.log(data);
        
        
        // if only the image is being stored
        if (data.status == "success") {
          // console.log(data.method);
          
          bootnotify(data.image + " added successfully!", 'Success!', 'success');
          ui_single_update_active(this, false);
          // You should probably do something with the response data, we just show it
          this.find('.progressbar').val("Uploaded successfully");
          this.find('.form-control[readonly]').attr('style', 'background-color: #28a745 !important; text-alignment: center !important; opacity: 1 !important;border: none !important;');
          ui_single_update_status(this, 'Upload completed.', 'success');
        }
        
        
        // if the image is being stored along with other form fields
        else if (data.status == "session_put") {
          
          $("#image").attr('name', data.image);
          $("#image").val(data.filename);
          ui_single_update_active(this, false);
          
          // You should probably do something with the response data, we just show it
          this.find('.progressbar').val("Uploaded successfully");
          this.find('.form-control[readonly]').attr('style', 'background-color: #28a745 !important; text-alignment: center !important; opacity: 1 !important;border: none !important;');
          ui_single_update_status(this, 'Upload completed.', 'success');
        }
        
        // if you need a reload after image store
        else if (data.status == "reload") {
          ui_single_update_active(this, false);
          // You should probably do something with the response data, we just show it
          this.find('.progressbar').val("Uploaded successfully");
          this.find('.form-control[readonly]').attr('style', 'background-color: #28a745 !important; text-alignment: center !important; opacity: 1 !important;border: none !important;');
          ui_single_update_status(this, 'Upload completed.', 'success');
          location.reload();
        }
        
        // if error is returned while storing image
        else if (typeof data.errors.error != 'undefined') {
          if (typeof data.errors.file != 'undefined') {
            document.getElementById('err' + data.id).innerHTML = data.errors.file[0];
          }
        }
      },
      onUploadError: function (id, xhr, status, message) {
        // Happens when an upload error happens
        ui_single_update_active(this, false);
        ui_single_update_status(this, 'Error: ' + message, 'danger');
      },
      onFallbackMode: function () {
        // When the browser doesn't support this plugin :(
      },
      onFileSizeError: function (file) {
        ui_single_update_status(this, 'File excess the size limit', 'danger');
        
      },
      onFileTypeError: function (file) {
        ui_single_update_status(this, 'File type is not an image', 'danger');
        
      },
      onFileExtError: function (file) {
        ui_single_update_status(this, 'File extension not allowed', 'danger');
        
      }
    });
  })
  
  /* ***************************************************
  ==========dm uploader single file upload end==========
  ******************************************************/
  
  
  /* ***************************************************
  ==========fontawesome icon picker start==========
  ******************************************************/
  $('.icp-dd').iconpicker();
  /* ***************************************************
  ==========fontawesome icon picker upload end==========
  ******************************************************/

  var ImageButton = function(context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<i class="far fa-images"></i>',
      tooltip: 'File Manager',
      click: function() {
        let id = context.$note[0].id;
        $("#lfmModalSummernote").find('iframe').attr('src', "");
        $("#lfmModalSummernote").find('iframe').attr('src', baseurl + "/laravel-filemanager?summernote=" + id);
        $("#lfmModalSummernote").modal('show');
      }
    });
  
    return button.render();
  }

  /* ***************************************************
  ==========Summernote initialization start==========
  ******************************************************/
 function uniqid() {
  return '_' + Math.random().toString(36).substr(2, 9);
 }
 function init_summernote(){
   $(".summernote").each(function (i) {
     let theight;
     let $summernote = $(this);
     $summernote.attr('id', uniqid());
     if ($(this).data('height')) {
       theight = $(this).data('height');
     } else {
       theight = 200;
     }
     $('.summernote').eq(i).summernote({
       height: theight,
       dialogsInBody: true,
       dialogsFade: false,
       toolbar: [
         ['style', ['style']],
         ['font', ['bold', 'underline', 'clear']],
         ['fontname', ['fontname']],
         ['fontsize', ['fontsize']],
         ['height', ['height']],
         ['color', ['color']],
         ['para', ['ul', 'ol', 'paragraph']],
         ['table', ['table']],
         ['insert', ['link', 'image', 'picture', 'video']],
         ['view', ['fullscreen', 'codeview', 'help']],
       ],
       buttons: {
         image: ImageButton,
       },
       popover: {
         image: [
           ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
           ['float', ['floatLeft', 'floatRight', 'floatNone']],
           ['remove', ['removeMedia']]
         ],
         link: [
           ['link', ['linkDialogShow', 'unlink']]
         ],
         table: [
           ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
           ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
         ],
         air: [
           ['color', ['color']],
           ['font', ['bold', 'underline', 'clear']],
           ['para', ['ul', 'paragraph']],
           ['table', ['table']],
           ['insert', ['link', 'picture']]
         ]
       },
       callbacks: {
         onImageUpload: function (files) {
           // console.log(files);
           $(".request-loader").addClass('show');

           let fd = new FormData();
           fd.append('image', files[0]);

           $.ajax({
             url: imgupload,
             method: 'POST',
             data: fd,
             contentType: false,
             processData: false,
             success: function (data) {
               // console.log(data);
               $summernote.summernote('insertImage', data);
               $(".request-loader").removeClass('show');
             }
           });

         }
       }
     });
   });
 }
 
 function init_daterangepicker() {
  if($('.daterange-picker').length){
    $('.daterange-picker').daterangepicker({
      autoUpdateInput: false,
      timePicker: true,
      locale: {
        format: 'MM/DD/YYYY hh:mm A',
        cancelLabel: 'Clear'
      }
    });

    $('input.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY hh:mm A') + ' - ' + picker.endDate.format('MM/DD/YYYY hh:mm A'));
      $(this).closest('.form-group').find("input[id*='start_date']").val(picker.startDate.format('MM/DD/YYYY hh:mm A'));
      $(this).closest('.form-group').find("input[id*='end_date']").val(picker.endDate.format('MM/DD/YYYY hh:mm A'));
    });

    $('input.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
      $(this).closest('.form-group').find("input[id*='start_date']").val('');
      $(this).closest('.form-group').find("input[id*='end_date']").val('');
    });
  }
 }
  //init_daterangepicker();
  init_summernote();
  
  
  $(document).on('click', ".note-video-btn", function () {
    //console.log('clicked');
    
    let i = $(this).index();
    
    if ($(".summernote").eq(i).parents(".modal").length > 0) {
      //console.log("in modal");
      
      setTimeout(() => {
        $("body").addClass('modal-open');
      }, 500);
    }
  });
  
  
  /* ***************************************************
  ==========Summernote initialization end==========
  ******************************************************/
  
  
  /* ***************************************************
  ==========Bootstrap Notify start==========
  ******************************************************/
  function bootnotify(message, title, type) {
    var content = {};
    
    content.message = message;
    content.title = title;
    content.icon = 'fa fa-bell';
    
    $.notify(content, {
      type: type,
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      allow_dismiss: true,
      delay: 4000,
    });
  }
  /* ***************************************************
  ==========Bootstrap Notify end==========
  ******************************************************/
  
  
  
  /* ***************************************************
  ==========Form Submit with AJAX Request Start==========
  ******************************************************/
  $("#submitBtn").on('click', function (e) {
    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");
    let ajaxForm = document.getElementById('ajaxForm');
    let fd = new FormData(ajaxForm);
    let url = $("#ajaxForm").attr('action');
    let method = $("#ajaxForm").attr('method');
    // console.log(url);
    // console.log(method);
    
    if ($("#ajaxForm .summernote").length > 0) {
      $("#ajaxForm .summernote").each(function (i) {
        let content = $(this).summernote('code');
        
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      });
    }
    
    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        //console.log(data);
        
        $(e.target).attr('disabled', false);
        $(".request-loader").removeClass("show");
        
        $(".em").each(function () {
          $(this).html('');
        })
        
        if (data == "success") {
          location.reload();
        }
        
        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            console.log(x);
            if (x == 'error') {
              continue;
            }
            document.getElementById('err' + x).innerHTML = data[x][0];
          }
        }
        
      },
      error: function (error){
        $(".em").each(function () {
          $(this).html('');
        })
        for (let x in error.responseJSON.errors) {
          //console.log('err'+x);
          document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });
  /* ***************************************************
  ==========Form Submit with AJAX Request End==========
  ******************************************************/
  
  
  
  
  /* ***************************************************
  ==========Form Prepopulate After Clicking Edit Button Start==========
  ******************************************************/
  $('.createbtn_url').on('click',function (){
    let url = $(this).data('url');
    $('#createModal .modal-body').load(url ,function (response){
      // focus & blur colorpicker inputs
      setTimeout(() => {
        $('#createModal .icp-dd').iconpicker();
        $("#createModal .jscolor").each(function () {
          new jscolor($(this)[0]);
          $(this).focus();
          $(this).blur();
        });
        init_summernote();
        init_daterangepicker();
        $('input[data-role="tagsinput"]').tagsinput('items');
      }, 300);

      var today = new Date();
      $("#deadline").datepicker({
        autoclose: true,
        endDate : today,
        todayHighlight: true
      });

    });

  });

  $('body').on('click','.editbtn_url',function (){
    let url = $(this).data('url');
    $('#editModal .modal-body').load(url ,function (response){
      // focus & blur colorpicker inputs
      setTimeout(() => {
        $('#editModal .icp-dd').iconpicker();
        $("#editModal .jscolor").each(function () {
          new jscolor($(this)[0]);
          $(this).focus();
          $(this).blur();
        });
        init_summernote();
        init_daterangepicker();
        $('input[data-role="tagsinput"]').tagsinput('items');
      }, 300);

      var today = new Date();
      $("#deadline").datepicker({
        autoclose: true,
        endDate : today,
        todayHighlight: true
      });

    });

  });

  $('body').on('click','.previewbtn_url',function (){
    let url = $(this).data('url');
    let id = $(this).data('id');
    $('#detailsModal'+id+' .modal-body').load(url ,function (response){

    });
  });
  $('body').on('click',".editbtn", function () {
    
    let datas = $(this).data();
    //console.log(datas);
    delete datas['toggle'];
    
    for (let x in datas) {
      if ($("#in" + x).hasClass('summernote')) {
        $("#in" + x).summernote('code', datas[x]);
      } else if ($("#in" + x).data('role') == 'tagsinput') {
        if (datas[x].length > 0) {
          let arr = datas[x].split(" ");
          for (let i = 0; i < arr.length; i++) {
            $("#in" + x).tagsinput('add', arr[i]);
          }
        } else {
          $("#in" + x).tagsinput('removeAll');
        }
      }
      else if ($("input[name='" + x + "']").attr('type') == 'radio') {
        $("input[name='" + x + "']").each(function (i) {
          if ($(this).val() == datas[x]) {
            $(this).prop('checked', true);
          }
        });
      }
      else {
        $("#in" + x).val(datas[x]);
      }
    }
    
    
    // focus & blur colorpicker inputs
    setTimeout(() => {
      $(".jscolor").each(function () {
        $(this).focus();
        $(this).blur();
      });
    }, 300);
  });
  
  
  /* ****************************************************************
  ==========Form Prepopulate After Clicking Edit Button End==========
  ******************************************************************/

  /* ****************************************************
  ==========Form Update with AJAX Request Start==========
  ******************************************************/
  $("#updateBtn").on('click', function (e) {
    
    $(".request-loader").addClass("show");
    
    let ajaxEditForm = document.getElementById('ajaxEditForm');
    let fd = new FormData(ajaxEditForm);
    let url = $("#ajaxEditForm").attr('action');
    let method = $("#ajaxEditForm").attr('method');
    // console.log(url);
    // console.log(method);
    
    if ($("#ajaxEditForm .summernote").length > 0) {
      $("#ajaxEditForm .summernote").each(function (i) {
        let content = $(this).summernote('code');
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      })
    }
    
    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        //console.log(data);
        
        $(".request-loader").removeClass("show");
        
        $(".em").each(function () {
          $(this).html('');
        })
        
        if (data == "success") {
          location.reload();
        }
        
        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            console.log(x);
            if (x == 'error') {
              continue;
            }
            document.getElementById('eerr' + x).innerHTML = data[x][0];
          }
        }
      },
      error: function (error){
        for (let x in error.responseJSON.errors) {
          document.getElementById('eerr' + x).innerHTML = error.responseJSON.errors[x][0];
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });
  /* ***************************************************
  ==========Form Update with AJAX Request End==========
  ******************************************************/
  
  
  
  /* ***************************************************
  ==========Delete Using AJAX Request Start==========
  ******************************************************/
  $('.deletebtn').on('click', function (e) {
    e.preventDefault();
    
    $(".request-loader").addClass("show");
    
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".deleteform").submit();
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/
  
  
  /* ***************************************************
  ==========Close Ticket Using AJAX Request Start==========
  ******************************************************/
  $('.close-ticket').on('click', function (e) {
    e.preventDefault();
    
    $(".request-loader").addClass("show");
    
    swal({
      title: 'Are you sure?',
      text: "You want to close this ticket!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, close it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        swal.close();
        $(".request-loader").removeClass("show");
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End===============
  ******************************************************/
  
  
  /* ***************************************************
  ==========Delete Using AJAX Request Start=============
  ******************************************************/
  $(".bulk-check").on('change', function () {
    let val = $(this).data('val');
    let checked = $(this).prop('checked');
    
    // if selected checkbox is 'all' then check all the checkboxes
    if (val == 'all') {
      if (checked) {
        $(".bulk-check").each(function () {
          $(this).prop('checked', true);
        });
      } else {
        $(".bulk-check").each(function () {
          $(this).prop('checked', false);
        });
      }
    }
    
    
    // if any checkbox is checked then flag = 1, otherwise flag = 0
    let flag = 0;
    $(".bulk-check").each(function () {
      let status = $(this).prop('checked');
      
      if (status) {
        flag = 1;
      }
    });
    
    // if any checkbox is checked then show the delete button
    if (flag == 1) {
      $(".bulk-delete").addClass('d-inline-block');
      $(".bulk-delete").removeClass('d-none');
    }
    // if no checkbox is checked then hide the delete button
    else {
      $(".bulk-delete").removeClass('d-inline-block');
      $(".bulk-delete").addClass('d-none');
    }
  });
  
  $('.bulk-delete').on('click', function () {
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(".request-loader").addClass('show');
        let href = $(this).data('href');
        let ids = [];
        
        // take ids of checked one's
        $(".bulk-check:checked").each(function () {
          if ($(this).data('val') != 'all') {
            ids.push($(this).data('val'));
          }
        });
        
        let fd = new FormData();
        for (let i = 0; i < ids.length; i++) {
          fd.append('ids[]', ids[i]);
        }
        
        $.ajax({
          url: href,
          method: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function (data) {
            //console.log(data);
            
            $(".request-loader").removeClass('show');
            if (data == "success") {
              location.reload();
            }
          }
        });
      } else {
        swal.close();
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/
  
  // LFM scripts START
  window.closeLfmModal = function(serial){
    $('#lfmModal'+serial).modal('hide');
    // if any modal is open, then add 'modal-open' class to body
    if($(".modal.show").length > 0) {
      setTimeout(function() {
        $('body').addClass('modal-open');
      }, 500);
    }
  };
  window.closeLfmModalSummernote = function(){
    $('#lfmModalSummernote').modal('hide');
      // if any modal is open, then add 'modal-open' class to body
      setTimeout(function() {
        if($(".modal.show").length > 0) {
          $('body').addClass('modal-open');
        }
      }, 500);
  };
  $(document).ready(function() {
    $(`.lfm-modal .fas.fa-times-circle`).on('click', function() {
      $(this).parents('.lfm-modal').modal('hide');
      // if any modal is open, then add 'modal-open' class to body
      setTimeout(function() {
        if($(".modal.show", parent.document).length > 0) {
          $('body', parent.document).addClass('modal-open');
        }      
      }, 500);
    });

    $(`.lfm-modal`).on('click', function(e) {
      if (!$(e.target).hasClass('modal-dialog') && !$(e.target).parents('.modal-dialog').length) {
        //console.log('outside modal');
        // if any modal is open, then add 'modal-open' class to body
        setTimeout(function() {
          if($(".modal.show", parent.document).length > 0) {
            $('body', parent.document).addClass('modal-open');
          }    
        }, 500);
      }
    });
    let last_modal_serial = 0;
    $('div[id^="lfmModal"].lfm-modal').on('shown.bs.modal',function (){
      var serial = parseInt($(this).attr('id').substr(8));
      if(last_modal_serial !== 0 && last_modal_serial !== serial){
        document.getElementById('lfmIframe'+serial)?.contentWindow.reloadItems();
      }
      last_modal_serial = serial;
    });

  });
  
  window.insertImage = function(id, items) {
      items.forEach(function(item) {
          $("#" + id).summernote('insertImage', item);
      });
  };  
  // LFM scripts END

  $('body').on('change',"input[id^='same_content_']",function(e) {
    e.preventDefault();

    const default_lang_code = $(this).data('default_lang_code');
    const lang_code = $(this).data('lang_code');
    const default_lang_id = $(this).data('default_lang_id');
    const lang_id = $(this).data('lang_id');

    if(this.checked) {
      //Get content
      let default_form = $(this).closest('.modal, .card').find(`#edit-lang-${default_lang_code}, #create-lang-${default_lang_code}, #upload-lang-${default_lang_code}`);
      let cur_form = $(this).closest('.modal, .card').find(`#edit-lang-${lang_code}, #create-lang-${lang_code}, #upload-lang-${lang_code}`);
      let thumpIndex = '1';
      let thumpIndex2 = '';
      let slideIndex = '2';
      let slideIndex2 = '';

      // 2 slideThumb, 2 thumpImage
      if(typeof $(this).closest('.modal, .card').attr('id') === 'undefined') {
        slideIndex2 = '21';
        thumpIndex2 = '10';
      }

      if($(this).closest('.modal, .card').attr('id') === 'editModal') {
        thumpIndex = '3';
        slideIndex = '4';
      }

      const input_fields = $('input[type=text][name]:not([data-role="tagsinput"]), input[type=url], input[type=email]',default_form);//title + link
      const tag_fields = $('[data-role="tagsinput"]',default_form); //Tags + meta keyword
      const select_fields = $('select[name]:not([name^="product_attribute"],[name^="file_type"])',default_form); //status + category + attribute
      const summernote_fields = $('textarea.summernote',default_form); //description
      const textarea_fields = $('textarea:not(.summernote)',default_form); //summary + meta Description
      const number_fields = $('input[type=number]',default_form); //Current Price +Previous Price
      const radio_checked= $('.selectgroup input[type=radio]:checked',default_form); //Radio

      // Handle Edit Content
      if (radio_checked) {
        const value = radio_checked.val()
        const radio_curForms = $('.selectgroup input[type=radio]', cur_form);

        radio_curForms.each(function (i, item) {
          if ($(item).val() !== value) $(item).removeAttr('checked')
          else $(item).attr('checked',true).trigger('change');
        })
      }

      if (input_fields) {
        $.each(input_fields,function (index,item) {
          if ($(item).val() != "") {
            var valueInput = $(item).val();
            let idInput = $(item).attr('name');
            idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
            $('[name="'+idInput+'"]',cur_form).val(valueInput);
          }
        });
      }

      if (tag_fields) {
        $.each(tag_fields ,function (index,item) {
          if ($(item).val() != "") {
            var valueInput = $(item).tagsinput('items');
            let idInput = $(item).attr('name');
            idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
            $('[name="'+idInput+'"]',cur_form).tagsinput('removeAll');
            valueInput.forEach(function(item){
              $('[name="'+idInput+'"]',cur_form).tagsinput('add',item);
            });
          }
        });
      }

      if (textarea_fields) {
        $.each(textarea_fields,function (index,item) {
          if ($(item).val()!= "") {
            var valueInput = $(item).val();
            let idInput = $(item).attr('name');
            idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
            $('[name="'+idInput+'"]',cur_form).val(valueInput);
          }
        });
      }

      if (summernote_fields) {
        $.each(summernote_fields,function (index,item) {
          if ($(item).val() != "") {
            var valueInput = $(item).summernote('code');
            let idInput = $(item).attr('name');
            idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
            $('[name="'+idInput+'"]',cur_form).summernote('code',valueInput);
          }
        });
      }

      if (number_fields) {
        $.each(number_fields,function (index,item) {
          if ($(item).val() != "") {
            var valueInput = $(item).val();
            let idInput = $(item).attr('name');
            idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
            $('[name="'+idInput+'"]',cur_form).val(valueInput);
          }
        });
      }

      if (select_fields) {
        $.each(select_fields,function (index,item) {
          if ($(item).val() != "") {
            var valueInput = $(item).val();
            var idInput = $(item).attr('name');

            const assocID = $('[name="'+idInput+'"]'+' option[value="'+valueInput+'"]').data('assoc_id');

            if(assocID){
              idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
              var valueAttr=$('[name="'+idInput+'"]'+' option[data-assoc_id="'+assocID+'"]').attr('value');

              $('[name="'+idInput+'"]',cur_form).val(valueAttr).trigger('change');
            }else{
              idInput = idInput.replace(new RegExp(default_lang_code + '$'), lang_code);
              $('[name="'+idInput+'"]',cur_form).val(valueInput).trigger('change');
            }
          }
        });
      }
      // Icon
      const groupIconDefault = $('#inputIcon_'+thumpIndex +default_lang_code, default_form).closest('.form-group');
      const iconDefault = $('.btn-group > .iconpicker-component > i', groupIconDefault).attr('class');

      const inputCur = $('#inputIcon_'+thumpIndex +lang_code, cur_form);
      const groupIconCur = inputCur.closest('.form-group');
      const iconCur = $('.btn-group > .iconpicker-component > i', groupIconCur);
      iconCur.removeAttr('class');
      iconCur.addClass(iconDefault);
      inputCur.val(iconDefault);


      // Set Image
      const thumbPreview = $('#thumbPreview'+thumpIndex +default_lang_id+' img', default_form).attr('src');
      const fileInput = $('#fileInput'+thumpIndex+ +default_lang_id, default_form).val();

      const thumbPreview2 = $('#thumbPreview'+thumpIndex2 +default_lang_id+' img', default_form).attr('src');
      const fileInput_2 = $('#fileInput'+thumpIndex2+ +default_lang_id, default_form).val();

      // const slideThumbElement = $("div[id^='sliderThumbs']", default_form);

      const sliderThumbs = $('#sliderThumbs'+slideIndex+ +default_lang_id, default_form).html();
      const fileInput2 = $('#fileInput'+slideIndex+ +default_lang_id, default_form).val();

      const sliderThumbs2 = $('#sliderThumbs'+slideIndex2+ +default_lang_id, default_form).html();
      const fileInput21 = $('#fileInput'+slideIndex2+ +default_lang_id, default_form).val();

      //Set content
      $('#thumbPreview'+thumpIndex+ +lang_id +' img', cur_form).attr('src', thumbPreview);
      $('#fileInput'+thumpIndex+ +lang_id, cur_form).val(fileInput);

      $('#thumbPreview'+thumpIndex2+ +lang_id +' img', cur_form).attr('src', thumbPreview2);
      $('#fileInput'+thumpIndex2+ +lang_id, cur_form).val(fileInput_2);

      var replace_html = sliderThumbs?.replaceAll('lfmIframe'+slideIndex+ +default_lang_id, 'lfmIframe'+slideIndex+ +lang_id);
      $('#sliderThumbs'+slideIndex+ +lang_id, cur_form).html(replace_html);
      $('#fileInput'+slideIndex+ +lang_id, cur_form).val(fileInput2);

      var replace_html2 = sliderThumbs2?.replaceAll('lfmIframe'+slideIndex2+ +default_lang_id, 'lfmIframe'+slideIndex2+ +lang_id);
      $('#sliderThumbs'+slideIndex2+ +lang_id, cur_form).html(replace_html2);
      $('#fileInput'+slideIndex2+ +lang_id, cur_form).val(fileInput21);

      // Get and set attribute
      const attTextChild = $('[id*=attribute_text_'+default_lang_code+ ']', default_form).children('div');
      if (attTextChild) {
        // add new attribute
        for (let i = 0; i < attTextChild.length; i++) {
          const child = $('[id*=attribute_text_'+lang_code+ ']', cur_form).children('div');
          if (child.length < attTextChild.length) {
            $('[id*=addAttribute_'+lang_code+ ']', cur_form).click();
          }

          if (child.length > attTextChild.length) {
            attIndexRemove.forEach(item => {
              if (child[item]) child[item].remove();
            })
          }
        }

        for (let i = 1; i < attTextChild.length; i++) {
          // set attribute
          let attId = $('[name*="_attribute_'+ default_lang_code +'[' + i + ']' +'[attribute_id]"]', default_form).val();
          let attAssocId = $('[name*="_attribute_'+ default_lang_code +'[' + i + ']' +'[attribute_id]"]' +' option[value="'+attId+'"]', default_form).data('assoc_id');
          let attText = $('[name*="_attribute_'+ default_lang_code +'[' + i + ']' +'[text]"]', default_form).val();

          // set attribute
          let setAttId = $('[name*="_attribute_'+lang_code +'[' + i + ']' +'[attribute_id]"]' +' option[data-assoc_id="'+attAssocId+'"]', cur_form).attr('value');
          $('[name*="_attribute_'+lang_code +'[' + i + ']' +'[attribute_id]"]', cur_form).val(setAttId);
          $('[name*="_attribute_'+lang_code +'[' + i + ']' +'[text]"]', cur_form).val(attText);
        }
      }
    }
  });
});
