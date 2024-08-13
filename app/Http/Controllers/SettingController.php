<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller{
    
    public function __construct(){
        $this->middleware('permission:view_settings')->only('index');
        $this->middleware('permission:edit_settings')->only('store');
    }
    public function index(){
        return view('pages.settings');
    }

    public function store(Request $request){
        $settings = $request->except('_token');

        try {
            DB::beginTransaction(); 
            
            foreach ($settings as $key => $value) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('logos', 'public');
                    Setting::setValue($key, $path);
                } else {
                    Setting::setValue($key, $value);
                }
            }
            
            logActivity(' قام الموظف بتعديل اعدادات الشركة', 'الاعدادات');
            DB::commit();
            toastr()->success('تم تعديل الاعدادات بنجاح');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }

    }
}