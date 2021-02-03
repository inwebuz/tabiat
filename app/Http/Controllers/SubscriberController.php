<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    /**
     * Subscribe email
     *
     * @return json
     */
    public function subscribe(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $checkData = [
            'email' => $validatedData['email'],
        ];
        $updateData = [
            'name' => $validatedData['name'],
            'status' => 1,
            'token' => Str::random(32),
        ];

        $data['status'] = 1;
        $data['token'] = Str::random(32);

        // save to database
        Subscriber::updateOrCreate(
            $checkData,
            $updateData
        );

        // send email
        // Mail::to($data['email'])->send(new ContactMail($contact, $product));

        $data = [
            'message' => __('main.form.subscribe_form_success'),
        ];

        return response()->json($data);
    }

    /**
     * Subscribe email
     *
     * @return json
     */
    public function unsubscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'token' => 'required',
        ]);

        $subscriber = Subscriber::where([['email', $data['email']], ['token', $data['token']]])->firstOrFail();
        $subscriber->status = 0;
        $subscriber->save();

        return __('main.form.you_have_successfully_unsubscribed_from_news');
    }
}
