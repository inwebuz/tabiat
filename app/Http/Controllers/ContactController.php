<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\LinkItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Contact;
use App\Category;
use App\Page;
use App\Product;
use App\Helpers\Helper;
use App\Mail\ContactMail;
use App\StaticText;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class ContactController extends Controller
{
    /**
     * Show contacts page
     */
    public function index()
    {
        $page = Helper::translation(Page::find(2)); // contacts page
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.nav.contacts'), route('contacts'), LinkItem::STATUS_INACTIVE));

        $address = StaticText::where('key', 'contact_address')->first()->translate()->description;

        return view('contacts', compact('breadcrumbs', 'page', 'address'));
    }

    /**
     * Send contact email
     *
     * @return json
     */
    public function send(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
//            'email' => 'required',
            'phone' => 'required',
            'message' => '',
            'type' => '',
        ]);

        $telegram_chat_id = config('services.telegram.chat_id');

        // save to database
        $contact = Contact::create($data);
        $contact->info = '';

        $category = null;
        $product = null;

        if (isset($request->product_id)) {
            $product = Product::find((int)$request->product_id);
            if ($product) {
                $contact->info = '<a href="' . $product->url . '" target="_blank" >' . $product->title_name . '</a>';
                if ($product->in_stock == 0 /*&& $product->isAvailableFromPartner()*/) {
                    $telegram_chat_id = config('services.telegram.partner_chat_id');
                }
            }
            if (isset($request->product_variant_combination)) {
                $contact->info .= ' (' . __('product.variant') . ': ' . $request->product_variant_combination . ')';
            }
        } elseif (isset($request->category_id)) {
            $category = Category::find((int)$request->category_id);
            if ($category) {
                $contact->info = '<a href="' . $category->url . '" target="_blank" >' . $category->name . '</a>';
            }
        }
        $contact->save();

        // send telegram
        $text = view('telegram.admin.contact', compact('contact', 'product'))->render();
        Helper::toTelegram($text, 'Markdown', $telegram_chat_id);

        // send email
        Mail::to(setting('contact.email'))->send(new ContactMail($contact, $product));

        // return redirect()->route('home', ['#contact-form'])->withSuccess(__('home.contact_message_success'));

        $data = [
            'message' => __('main.form.contact_form_success'),
        ];

        return response()->json($data);
    }

}
