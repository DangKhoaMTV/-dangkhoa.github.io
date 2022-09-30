@php
    $links = json_decode($menus, true);
    //  dd($links);
@endphp
<div class="header-navbar">
    <div class="row">
       <div class="col-lg-2 col-6">
          <div class="logo-wrapper">
             <a href="{{route('front.index')}}"><img class="lazy" data-src="{{asset('assets/front/img/'.$bs->logo)}}" alt=""></a>
          </div>
       </div>
       <div class="col-lg-10 col-6 text-left position-static">
          <ul class="main-menu" id="mainMenu">
              <li class="d-block d-md-block d-lg-none text-center logo-mobile">
                  <a href="{{route('front.index')}}"><img class="lazy" src="{{asset('assets/front/img/'.$bs->mobile_logo)}}" alt=""></a>
              </li>
             @foreach ($links as $link)
                 @php
                     $href = getHref($link);
                 @endphp


                 @if (strpos($link["type"], '-megamenu') !==  false)
                    @includeIf('front.default.partials.mega-menu')

                 {{-- if the link is not services OR theme version doesn't have service category --}}
                 @else
                     @if (!array_key_exists("children",$link))

                         {{--- Level1 links which doesn't have dropdown menus ---}}
                         <li class="{{ url()->current() == $href ? 'active':'' }}"><a href="{{$href}}" target="{{$link["target"]}}">{{$link["text"]}}</a></li>

                     @else
                         <li class="dropdown">
                             {{--- Level1 links which has dropdown menus ---}}
                             <a class="dropdown-btn" href="{{$href}}" target="{{$link["target"]}}">{{$link["text"]}}</a>

                             <ul class="dropdown-lists">
                                 {{-- START: 2nd level links --}}
                                 @foreach ($link["children"] as $level2)
                                     @php
                                         $l2Href = getHref($level2);
                                     @endphp

                                     <li @if(array_key_exists("children", $level2)) class="submenus" @endif>
                                         <a  href="{{$l2Href}}" target="{{$level2["target"]}}">{{$level2["text"]}}</a>

                                         {{-- START: 3rd Level links --}}
                                         @php
                                             if (array_key_exists("children", $level2)) {
                                                 create_menu($level2);
                                             }
                                         @endphp
                                         {{-- END: 3rd Level links --}}

                                     </li>
                                 @endforeach
                                 {{-- END: 2nd level links --}}
                             </ul>

                         </li>
                     @endif
                 @endif

             @endforeach

             @if (!empty($currentLang) && count($langs) > 1)
                 <li class="language d-none d-lg-block d-xl-block">
                     <a class="language-btn" href="#">{{convertUtf8($currentLang->code)}}</a>
                     <ul class="language-dropdown">
                         @foreach ($langs as $key => $lang)
                             <li><a class="@if($currentLang->id == $lang->id) active @endif"  href='{{ route('changeLanguage', $lang->code). '?redirect='. request()->route()->getName() . '&slug=' .request()->path() }}'>{{convertUtf8($lang->code)}}</a></li>
                         @endforeach
                     </ul>
                 </li>
             @endif

             @if ($bs->is_quote == 1)
             <li class="request-quote"><a href="#" data-href="{{route('front.quote')}}" class="boxed-btn">{{__('Request A Quote')}}</a></li>
             @endif
              <li class="d-block d-md-block d-lg-none text-center">
                  @if (!empty($currentLang) && count($langs) > 1)
                      <div class="language-mobile">
                      @foreach ($langs as $key => $lang)
                          <a class="@if($currentLang->id == $lang->id) active @endif" href='{{ route('changeLanguage', $lang->code). '?redirect='. request()->route()->getName(). '&slug=' .request()->path() }}'>{{convertUtf8($lang->code)}}</a>
                      @endforeach
                      </div>
                  @endif
              </li>
          </ul>
          <div id="mobileMenu">
          </div>
       </div>
    </div>
 </div>

