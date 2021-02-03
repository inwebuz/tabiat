<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\SmsVerification;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function redirectTo()
    {
        return route('home');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.nav.register'), route('register'), LinkItem::STATUS_INACTIVE));
        return view('auth.register', compact('breadcrumbs'));
    }

    public function showRegistrationVerifyForm(Request $request)
    {
        $phone_number = $request->input('phone_number', '');
        if (!$phone_number) {
            abort(404);
        }

        // if user not found or already verified redirect home
        $user = $this->getUserByPhoneNumber($phone_number);
        if (!$user || $user->isPhoneVerified()) {
            return $this->cantActivateUser();
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
                'type' => 'register',
                'expires_at' => now()->addHour(),
            ]);


            // send SMS code with API
            $appName = config('app.name');
            $messagePrefix = Helper::messagePrefix();
            $text = 'Код верификации на ' . $appName . ' ' . $verifyCode;
            $messageId = $messagePrefix . $smsVerification->id;
            Helper::sendSMS($messageId, $phone_number, $text);
        }

        return view('auth.register_verify', compact('phone_number'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);
        return redirect()->route('home');

        //return redirect()->route('register.verify', ['phone_number' => $user->phone_number]);
    }

    public function registerVerify(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'verify_code' => 'required|digits:6',
        ]);

        $phone_number = $request->input('phone_number');
        $verify_code = $request->input('verify_code');

        // if user not found or already verified redirect home
        $user = $this->getUserByPhoneNumber($phone_number);
        if (!$user || $user->isPhoneVerified()) {
            return $this->cantActivateUser();
        }

        // if active verify code not found redirect back
        $smsVerification = $this->getSmsVerification($phone_number);
        if (!$smsVerification) {
            return redirect()->route('register.verify', ['phone_number' => $phone_number]);
        }

        if ($smsVerification->verify_code != $verify_code) {
            return back()->withErrors([
                'verify_code' => __('main.verify_code_is_invalid'),
            ]);
        }

        // set phone number verified
        $user->phone_number_verified_at = now();
        $user->save();

        request()->session()->flash('alert', __('main.verify_phone_number_success'));
        request()->session()->flash('alertType', 'success');

        // login user
        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            //'phone_number' => ['required', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            //'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    }

    protected function getSmsVerification($phone_number)
    {
        return SmsVerification::where('phone_number', $phone_number)
            ->where('expires_at', '>', now())
            ->where('type', 'register')
            ->orderBy('id', 'desc')
            ->first();
    }

    protected function getUserByPhoneNumber($phone_number)
    {
        return User::where('phone_number', $phone_number)->first();
    }

    private function cantActivateUser()
    {
        return redirect()->route('home')->with([
            'alert' => __('main.user_not_found_or_already_activated'),
            'alertType' => 'info',
        ]);
    }
}
