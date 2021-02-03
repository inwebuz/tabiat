<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\SmsVerification;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function redirectTo()
    {
        return route('home');
    }

    public function showLinkRequestPhoneForm()
    {
        return view('auth.passwords.phone');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function passwordPhone(Request $request)
    {
        $this->validatePhone($request);

        $phone_number = $request->input('phone_number');

        // if user not found or already verified redirect home
        $user = $this->getUserByPhoneNumber($phone_number);
        if (!$user) {
            return back()->withErrors([
                'phone_number' => __('main.user_not_found'),
            ]);
        }

        return redirect()->route('password.phone.verify', ['phone_number' => $user->phone_number]);
    }

    public function showPasswordPhoneVerifyForm(Request $request)
    {
        $phone_number = $request->input('phone_number', '');
        if (!$phone_number) {
            abort(404);
        }

        // if user not found redirect home
        $user = $this->getUserByPhoneNumber($phone_number);
        if (!$user) {
            return $this->cantFindUser();
        }

        // check if verify code was sent
        $smsVerification = $this->getSmsVerification($phone_number);

        // if verify code was not sent or expired, generate code and send
        if (!$smsVerification) {

            // generate verify code
            //$verifyCode = 123456; //mt_rand(100000, 999999); // 123456
            $verifyCode = mt_rand(100000, 999999);

            // save verify code
            $smsVerification = SmsVerification::create([
                'phone_number' => $phone_number,
                'verify_code' => $verifyCode,
                'type' => 'forgot_password',
                'expires_at' => now()->addHour(),
            ]);

            // send SMS code with API
            $appName = config('app.name');
            $messagePrefix = Helper::messagePrefix();
            $text = 'Код верификации на ' . $appName . ' ' . $verifyCode;
            $messageId = $messagePrefix . $smsVerification->id;
            Helper::sendSMS($messageId, $phone_number, $text);
        }

        return view('auth.passwords.phone_verify', compact('phone_number'));
    }

    public function passwordPhoneVerify(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'verify_code' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $phone_number = $request->input('phone_number');
        $verify_code = $request->input('verify_code');
        $password = $request->input('password');

        // if user not found redirect home
        $user = $this->getUserByPhoneNumber($phone_number);
        if (!$user) {
            return $this->cantFindUser();
        }

        // if active verify code not found redirect back
        $smsVerification = $this->getSmsVerification($phone_number);
        if (!$smsVerification) {
            return redirect()->route('password.phone.verify', ['phone_number' => $phone_number]);
        }

        if ($smsVerification->verify_code != $verify_code) {
            return back()->withErrors([
                'verify_code' => __('main.verify_code_is_invalid'),
            ]);
        }

        // update password
        $user->phone_number_verified_at = now();
        $user->password = Hash::make($password);
        $user->save();

        // login user
        auth()->guard()->login($user);

        request()->session()->flash('status', __('main.password_reset_success'));
        return redirect()->route('home');
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validatePhone(Request $request)
    {
        $request->validate(['phone_number' => 'required|regex:/^9989[01345789]\d{7}$/']);
    }

    protected function getSmsVerification($phone_number)
    {
        return SmsVerification::where('phone_number', $phone_number)
            ->where('expires_at', '>', now())
            ->where('type', 'forgot_password')
            ->orderBy('id', 'desc')
            ->first();
    }

    protected function getUserByPhoneNumber($phone_number)
    {
        return User::where('phone_number', $phone_number)->first();
    }

    private function cantFindUser()
    {
        return redirect()->route('home')->with([
            'alert' => __('main.user_not_found'),
            'alertType' => 'info',
        ]);
    }
}
