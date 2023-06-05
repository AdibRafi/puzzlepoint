<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorVerification;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Http\Request;
use Session;

class TwoFAController extends Controller
{
    public function index()
    {
        return inertia();
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ], [
            'code.required' => 'Please enter the code'
        ]);

        $codeModal = User::find(auth()->id())
            ->where('2FA_code', '=', $request->input('code'))
            ->where('updated_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($codeModal) {
            Session::put('user_2fa',auth()->id());
            return redirect()->route(RouteServiceProvider::HOME);
        }
        return null;
    }

    public function resend()
    {
        Auth::user()->notify(new TwoFactorVerification());

        return back()->with('alertMessage', 'We sent you code on your mobile number');
    }
}
