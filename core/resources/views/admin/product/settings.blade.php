@extends('admin.layout')
@section('content')
@php
    $type = request()->input('type');
@endphp
<div class="page-header">
   <h4 class="page-title">Settings</h4>
   <ul class="breadcrumbs">
      <li class="nav-home">
         <a href="#">
         <i class="flaticon-home"></i>
         </a>
      </li>
      <li class="separator">
         <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
         <a href="#">Shop Management</a>
      </li>
      <li class="separator">
         <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
         <a href="#">Settings</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header">
            <div class="card-title d-inline-block">Settings</div>
            <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.product.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
            <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
            </a>
         </div>
         <div class="card-body pt-5 pb-5">
            <div class="row">
               <div class="col-lg-6 offset-lg-3">
                  <form id="settingsForm" class="" action="{{route('admin.product.settings')}}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group">
                        <label>Shop **</label>
                        <div class="selectgroup w-100">
                              <label class="selectgroup-item">
                                  <input type="radio" name="is_shop" value="1" class="selectgroup-input" {{$abex->is_shop == 1 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Active</span>
                              </label>
                              <label class="selectgroup-item">
                                  <input type="radio" name="is_shop" value="0" class="selectgroup-input" {{$abex->is_shop == 0 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Deactive</span>
                              </label>
                        </div>
                        <p class="text-warning mb-0">By enabling / disabling, you can completely enable / disable the relevant pages of your shop in this system.</p>
                     </div>
                     <div class="form-group">
                        <label>Catalog Mode **</label>
                        <div class="selectgroup w-100">
                              <label class="selectgroup-item">
                                  <input type="radio" name="catalog_mode" value="1" class="selectgroup-input" {{$abex->catalog_mode == 1 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Active</span>
                              </label>
                              <label class="selectgroup-item">
                                  <input type="radio" name="catalog_mode" value="0" class="selectgroup-input" {{$abex->catalog_mode == 0 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Deactive</span>
                              </label>
                        </div>
                        <p class="text-warning mb-0">If you enable catalog mode, then pricing, cart, checkout option of products will be removed. But product & product details page will remain.</p>
                     </div>
                     <div class="form-group">
                        <label>Rating System **</label>
                        <div class="selectgroup w-100">
                              <label class="selectgroup-item">
                                  <input type="radio" name="product_rating_system" value="1" class="selectgroup-input" {{$abex->product_rating_system == 1 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Active</span>
                              </label>
                              <label class="selectgroup-item">
                                  <input type="radio" name="product_rating_system" value="0" class="selectgroup-input" {{$abex->product_rating_system == 0 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Deactive</span>
                              </label>
                        </div>
                     </div>
                     <div class="form-group">
                        <label>Guest Checkout **</label>
                        <div class="selectgroup w-100">
                              <label class="selectgroup-item">
                                  <input type="radio" name="product_guest_checkout" value="1" class="selectgroup-input" {{$abex->product_guest_checkout == 1 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Active</span>
                              </label>
                              <label class="selectgroup-item">
                                  <input type="radio" name="product_guest_checkout" value="0" class="selectgroup-input" {{$abex->product_guest_checkout == 0 ? 'checked' : ''}}>
                                  <span class="selectgroup-button">Deactive</span>
                              </label>
                        </div>
                        <p class="text-warning mb-0">If you enable 'guest checkout', then customers can checkout without login</p>
                     </div>
                     <div class="form-group">
                        <label for="">Tax **</label>
                        <input type="text" class="form-control" name="tax" value="{{$abex->tax}}" placeholder="Enter tax">
                        <p id="errtax" class="mb-0 text-danger em"></p>
                     </div>


                      <div class="form-group">
                          <label for="">Product Page Video URL **</label>
                          <input type="text" class="form-control" name="product_page_video_url" value="{{$abex->product_page_video_url}}" placeholder="Enter Video URL">
                          <p id="errproduct_page_video_url" class="mb-0 text-danger em"></p>
                      </div>
                    <div class="row">
                        <div class="col-lg-6">
                            {{-- START: Product Page Image --}}
                            <div class="form-group">
                                <label for="">Product Page Image ** </label>
                                <br>
                                <div class="thumb-preview" id="thumbPreview1">
                                    <label for="chooseImage1"> <img src="{{ $abex->product_page_image != '' ? asset('assets/front/img/' . $abex->product_page_image) : asset('assets/admin/img/noimage.jpg') }}" alt="Feature Image"></label>
                                </div>
                                <br>
                                <br>

                                <input id="fileInput1" type="hidden" name="product_page_image">
                                <button id="chooseImage1" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                        data-toggle="modal" data-target="#lfmModal1">Choose Image
                                </button>

                                <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                <p id="errproduct_page_image" class="mb-0 text-danger em"></p>
                                <!-- Featured Image LFM Modal -->
                                <div class="modal fade lfm-modal" id="lfmModal1" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
                                    <i class="fas fa-times-circle"></i>
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <iframe src="{{url('laravel-filemanager')}}?serial=1" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{-- START: Product Page BG Image --}}
                            <div class="form-group">
                                <label for="">Product Page Background ** </label>
                                <br>
                                <div class="thumb-preview" id="thumbPreview2">
                                    <label for="chooseImage2"> <img src="{{ $abex->product_page_video_bg != '' ? asset('assets/front/img/' . $abex->product_page_video_bg) : asset('assets/admin/img/noimage.jpg') }}" alt="Feature Image"></label>
                                </div>
                                <br>
                                <br>

                                <input id="fileInput2" type="hidden" name="product_page_video_bg">
                                <button id="chooseImage2" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                        data-toggle="modal" data-target="#lfmModal2">Choose Image
                                </button>

                                <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                <p id="errproduct_page_image" class="mb-0 text-danger em"></p>
                                <!-- Featured Image LFM Modal -->
                                <div class="modal fade lfm-modal" id="lfmModal2" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
                                    <i class="fas fa-times-circle"></i>
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <iframe src="{{url('laravel-filemanager')}}?serial=2" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Product Page Type Video Background **</label>
                                <select class="form-control" name="product_page_type">
                                    <option {{$abex->product_page_type == 'video_bg' ? 'selected' : ''}} value="video_bg">Get Video background</option>
                                    <option {{$abex->product_page_type == 'image_bg' ? 'selected' : ''}} value="image_bg">Image Setting background</option>
                                </select>
                                <p id="errproduct_page_type" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                  </form>
               </div>
            </div>
         </div>
         <div class="card-footer">
            <div class="form">
               <div class="form-group from-show-notify row">
                  <div class="col-12 text-center">
                     <button type="submit" form="settingsForm" class="btn btn-success">Submit</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
