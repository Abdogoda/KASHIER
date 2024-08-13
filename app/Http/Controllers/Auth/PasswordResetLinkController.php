<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller{

    public function create(): View{
        return view('auth.forgot-password');
    }

    public function store(Request $request){
        $request->validate([
            'email' => ['required', 'email', 'exists:employees,email'],
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->where('email', $request->email)->delete();

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        Mail::send('mails.forgot-password', ['token' => $token], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('تغيير كلمة المرور');
        });

        toastr()->success('لقد أرسلنا رابط تغيير كلمة المرور الي البريد الالكتروني الخاص بك, تأكد من ذلك لكي تستطيع تغير كلمة مرورك');
        return redirect()->back();

        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // if($status == Password::RESET_LINK_SENT){
        //     toastr()->success('لقد أرسلنا رابط تغيير كلمة المرور الي البريد الالكتروني الخاص بك, تأكد من ذلك لكي تستطيع تغير كلمة مرورك');
        //     return redirect()->back();
        // }else{
        //     toastr()->error($status);
        //     return back()->withInput($request->only('email'));
        // }
    }
}