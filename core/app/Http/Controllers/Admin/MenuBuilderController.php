<?php

namespace App\Http\Controllers\Admin;

use App\BasicSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Language;
use App\Megamenu;
use App\Menu;
use App\Page;
use App\Permalink;
use App\Scategory;
use Illuminate\Support\Facades\Session;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class MenuBuilderController extends Controller
{

    public function index(Request $request) {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;

        // set language
        app()->setLocale($lang->code);

        // get page names of selected language
        $pages = Page::where('language_id', $lang->id)->get();
        $data["pages"] = $pages;

        // get previous menus
        $menu = Menu::where('language_id', $lang->id)->first();
        $data['prevMenu'] = '';
        if (!empty($menu)) {
            $data['prevMenu'] = $menu->menus;
        }

        return view('admin.menu_builder.index', $data);
    }

    public function update(Request $request) {
        // return response()->json(json_decode($request->str, true));
        $menus = json_decode($request->str, true);
        foreach ($menus as $key => $menu) {
            if (strpos($menu['type'], 'megamenu') !== false) {
                if (array_key_exists('children', $menu) && !empty($menu['children'])) {
                    return response()->json(['status' => 'error', 'message' => 'Mega Menu cannot contain children!']);
                }
            }
            if (array_key_exists('children', $menu) && !empty($menu['children'])) {
                $allChildren = json_encode($menu['children']);
                if (strpos($allChildren, '-megamenu') !== false) {
                    return response()->json(['status' => 'error', 'message' => 'Mega Menu cannot be children of a Menu!']);
                }
            }
        }

        Menu::where('language_id', $request->language_id)->delete();

        $menu = new Menu;
        $menu->language_id = $request->language_id;
        $menu->menus = json_encode($menus);
        $menu->save();

        return response()->json(['status' => 'success', 'message' => 'Menu updated successfully!']);
    }

    public function megamenus() {
        return view('admin.menu_builder.megamenus.megamenus');
    }

    public function megaMenuEdit(Request $request) {
        //$lang = Language::where('code', $request->language)->firstOrFail();
        $languages = Language::orderBy('is_default', 'DESC')->get();

        // for 'services' mega menu
        if ($request->type == 'services') {
            if (serviceCategory()) {
                foreach ($languages as $language){
                    $data['cats'][$language->code] = $language->scategories()->where('status', 1)->get();
                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'services')->where('category', 1);
                }
                $catStatus = 1;
            } elseif (!serviceCategory()) {
                foreach ($languages as $language) {
                    $data['items'][$language->code] = $language->services()->get();
                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'services')->where('category', 0);
                }
                $catStatus = 0;
            }
        }

        // for 'products' mega menu
        if ($request->type == 'products') {
            foreach ($languages as $language) {
                $data['cats'][$language->code] = $language->pcategories()->where('status', 1)->get();
                $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'products')->where('category', 1);
            }
            $catStatus = 1;
        }

        // for 'portfolios' mega menu
        if ($request->type == 'portfolios') {
            if (serviceCategory()) {
                foreach ($languages as $language) {
                    $data['cats'][$language->code] = $language->scategories()->where('status', 1)->get();
                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'portfolios')->where('category', 1);
                }
                $catStatus = 1;
            } elseif (!serviceCategory()) {
                foreach ($languages as $language) {
                    $data['items'][$language->code] = $language->portfolios()->get();
                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'portfolios')->where('category', 0);
                }
                $catStatus = 0;
            }
        }

        // for 'courses' mega menu
        if ($request->type == 'courses') {
            foreach ($languages as $language) {
                $data['cats'][$language->code] = $language->course_categories()->get();
                $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'courses')->where('category', 1);
            }
            $catStatus = 1;
        }

        // for 'causes' mega menu
        if ($request->type == 'causes') {
            foreach ($languages as $language) {
                $data['items'][$language->code] = $language->causes()->get();
                $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'causes')->where('category', 0);
            }
            $catStatus = 0;
        }

        // for 'events' mega menu
        if ($request->type == 'events') {
            foreach ($languages as $language) {
                $data['cats'][$language->code] = $language->event_categories()->get();
                $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'events')->where('category', 1);
            }
            $catStatus = 1;
        }

        // for 'blogs' mega menu
        if ($request->type == 'blogs') {
            foreach ($languages as $language){
                $data['cats'][$language->code] = $language->bcategories()->get();
                $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'blogs')->where('category', 1);
            }
            $catStatus = 1;
        }

        //$data['lang'] = $lang;

        foreach ($languages as $language) {
            if ($megamenu[$language->code]->count() == 0) {
                $megamenu[$language->code] = new Megamenu;
                $megamenu[$language->code]->language_id = $language->id;
                $megamenu[$language->code]->type = $request->type;
                $megamenu[$language->code]->menus = json_encode([]);
                $megamenu[$language->code]->category = $catStatus;
                $megamenu[$language->code]->save();
            } else {
                $megamenu[$language->code] = $megamenu[$language->code]->first();
            }
        }

        $data['megamenu'] = $megamenu;
        foreach ($languages as $language) {
            $data['mmenus'][$language->code] = json_decode($megamenu[$language->code]->menus, true);
        }

        return view('admin.menu_builder.megamenus.edit', $data);
    }

    public function megaMenuUpdate(Request $request) {
        $menus = [];
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $language) {
            $items[$language->code] = $request->{'items_'.$language->code};
        }
        //$langid = $request->language_id;
        $type = $request->type;

        if ($type == 'services') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    if (serviceCategory()) {
                        foreach ($items[$language->code] as $key => $item) {
                            $item = json_decode($item, true);
                            $catid = $item[0];
                            $menus[$language->code]["$catid"][] = $item[1];
                        }

                        $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'services')->where('category', 1)->firstOrFail();
                    } elseif (!serviceCategory()) {
                        $menus[$language->code] = $request->{'items_'.$language->code};
                        $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'services')->where('category', 0)->firstOrFail();
                    }
                }
            }
        } elseif ($type == 'products') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    foreach ($items[$language->code] as $key => $item) {
                        $item = json_decode($item, true);
                        $catid = $item[0];
                        $menus[$language->code]["$catid"][] = $item[1];
                    }

                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'products')->where('category', 1)->firstOrFail();
                }
            }
        } elseif ($type == 'portfolios') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    if (serviceCategory()) {
                        foreach ($items[$language->code] as $key => $item) {
                            $item = json_decode($item, true);
                            $catid = $item[0];
                            $menus[$language->code]["$catid"][] = $item[1];
                        }

                        $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'portfolios')->where('category', 1)->firstOrFail();
                    } elseif (!serviceCategory()) {
                        $menus[$language->code] = $request->{'items_' . $language->code};
                        $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'portfolios')->where('category', 0)->firstOrFail();
                    }
                }
            }
        } elseif ($type == 'courses') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    foreach ($items[$language->code] as $key => $item) {
                        $item = json_decode($item, true);
                        $catid = $item[0];
                        $menus[$language->code]["$catid"][] = $item[1];
                    }

                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'courses')->where('category', 1)->firstOrFail();
                }
            }
        } elseif ($type == 'causes') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    $menus[$language->code] = $request->{'items_' . $language->code};
                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'causes')->where('category', 0)->firstOrFail();
                }
            }
        } elseif ($type == 'events') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    foreach ($items[$language->code] as $key => $item) {
                        $item = json_decode($item, true);
                        $catid = $item[0];
                        $menus[$language->code]["$catid"][] = $item[1];
                    }

                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'events')->where('category', 1)->firstOrFail();
                }
            }
        } elseif ($type == 'blogs') {
            foreach ($languages as $language) {
                if (!empty($items[$language->code])) {
                    foreach ($items[$language->code] as $key => $item) {
                        $item = json_decode($item, true);
                        $catid = $item[0];
                        $menus[$language->code]["$catid"][] = $item[1];
                    }

                    $megamenu[$language->code] = Megamenu::where('language_id', $language->id)->where('type', 'blogs')->where('category', 1)->firstOrFail();
                }
            }
        }

        foreach ($languages as $language) {
            $menus[$language->code] = json_encode($menus[$language->code]);
            $megamenu[$language->code]->menus = $menus[$language->code];
            $megamenu[$language->code]->save();
        }

        $request->session()->flash('success', 'Mega Menu updated for ' . $request->type);
        return back();
    }

    public function permalinks() {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        foreach ($languages as $language){
            $permalinks = Permalink::where('language_id',$language->id)->get();
            $data['permalinks'][$language->code] = $permalinks;
        }

        return view('admin.menu_builder.permalink', $data);
    }

    public function permalinksUpdate(Request $request) {
        $requests = $request->except("_token");
        // return $requests;

        $rules = [];

        foreach ($requests as $type_with_code => $permalink) {
            $lang_code = substr($type_with_code,0,2);
            $type = substr($type_with_code,3);
            $lang = Language::where('code',$lang_code)->first();
            $rules["$type_with_code"] = [
                'required',
                'max:50',
                function ($attribute, $value, $fail) use ($type, $lang, $permalink) {
                    // fetch details
                    $details = Permalink::where('type', $type)->where('language_id',$lang->id)->first()->details;

                    // if the 'permalink' matches with same 'details' row, then throw error
                    $permalinks = Permalink::where('details', $details)->where('language_id',$lang->id)->where('type', '<>', $type)->get();
                    foreach ($permalinks as $key => $pl) {
                        if ($pl->permalink == $permalink) {
                            $fail('Must be unique ' . ($details == 1 ? 'Details Page ' : 'Non-Details Page ') . 'link');
                        }
                    }

                }
            ];
        }
        $request->validate($rules);

        foreach ($requests as $key => $val) {
            $lang_code = substr($key,0,2);
            $type = substr($key,3);
            $lang = Language::where('code',$lang_code)->first();
            $pl = Permalink::where('type', $type)->where('language_id',$lang->id)->firstOrFail();
            $pl->permalink = $val;
            $pl->save();
        }
        $request->session()->flash('success', 'Permalinks updated successfully');
        return back();
    }
}
