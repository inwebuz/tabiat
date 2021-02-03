<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\InstallmentOrder;
use App\Mail\NewOrderAdminMail;
use App\Mail\NewOrderClientMail;
use App\Order;
use App\OrderItem;
use App\Product;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function show(Request $request, Order $order, $check)
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.order'), $order->url), LinkItem::STATUS_INACTIVE);

        // check and udpate installment order
        if ($order->isInstallmentOrder()) {
            if ($order->installmentOrder->vendor_name == 'vendoo') {

                // there was error creating vendor order, try again
                if (!$order->installmentOrder->vendor_order_id) {

                    // try to add vendoo order
                    $this->vendooAdd($order, $order->installmentOrder);
                    // return $this->vendooAdd($order, $order->installmentOrder);
                }

                // update vendoo order status
                /*$response = $this->vendooRequest('GET', 'orders/' . $order->id);
                if ($response && $response->getStatusCode() == 200) {
                    // get vendoo application id
                    $content = $response->getBody()->getContents();
                    $contentJson = json_decode($content);
                    $status = $contentJson->status;

                    $order->installmentOrder->vendor_order_status = $status;

                    $order->installmentOrder->save();
                }*/
            }
        }

        if ($check != md5($order->created_at)) {
            abort(403);
        }

        return view('order', compact('order', 'breadcrumbs'));
    }

    /**
     * Create new order
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function add(Request $request)
    {
        // cart empty error
        if (app('cart')->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $data = $request->validate([
            'name' => 'required|max:191',
            'phone_number' => 'required|regex:/^998\d{9}$/',
            'email' => 'email|max:191',
            'address' => 'required|max:65536',
            'message' => 'max:65536',
            // 'type' => 'required|integer|between:0,1',
            'communication_method' => 'required|integer|between:0,2',
            'payment_method_id' => 'required|integer|between:1,5',
            'public_offer' => 'required',
        ], [
            'public_offer.required' => __('main.you_must_accept_public_offer'),
        ]);

        if (empty($data['address'])) {
            $data['address'] = '';
        }
        if (empty($data['message'])) {
            $data['message'] = '';
        }

        $cart = app('cart');

        $data['user_id'] = auth()->check() ? auth()->user()->id : null;

        $data['subtotal'] = $cart->getSubtotal();
        $data['total'] = $cart->getTotal();

        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->server('HTTP_USER_AGENT');

        unset($data['public_offer']);

        // check and set installment
        $data['type'] = Order::TYPE_BUY_IMMEDIATELY;
        // $orderTypes = Order::types();
        // $data['type'] = (int)$data['type'];
        // if (!array_key_exists($data['type'], $orderTypes)) {
        //     $data['type'] = Order::TYPE_BUY_IMMEDIATELY;
        // }

        $order = Order::create($data);

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.cart'), route('cart.index')));
        foreach ($cart->getContent() as $cartItem) {

            $orderItemData = [
                'order_id' => $order->id,
                'name' => $cartItem->name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'subtotal' => $cartItem->getPriceSum(),
                'total' => $cartItem->getPriceSumWithConditions(),
            ];
            $cartItemId = explode('-', $cartItem->id, 2);
            $orderItemData['product_id'] = $cartItemId[0];
            $orderItemData['product_variant_id'] = $cartItemId[1] ?? null;

            OrderItem::create($orderItemData);
        }

        // clear cart
        app('cart')->clear();

        // load relations
        $order->load('items');

        // send notification to telegram admin
        $telegramMessage = view('telegram.admin.new_order', ['url' => route('voyager.orders.show', $order->id), 'order' => $order])->render();
        // Log::info($telegramMessage);
        Helper::toTelegram($telegramMessage, 'Markdown');

        // send email to admin
        Mail::to(setting('contact.email'))->send(new NewOrderAdminMail($order));

        // send email to client
        if ($order->email) {
            Mail::to($order->email)->send(new NewOrderClientMail($order));
        }

        //  check installment vendoo
        // if ($data['type'] == Order::TYPE_VENDOO_INSTALLMENT) {
        //     // create InstallmentOrder
        //     $installmentOrder = InstallmentOrder::create([
        //         'order_id' => $order->id,
        //         'vendor_name' => 'vendoo',
        //         'vendor_order_id' => null,
        //         'vendor_order_status' => null,
        //     ]);
        //     return $this->vendooAdd($order, $installmentOrder);
        // }

        return redirect()->to($order->url);
    }

    private function vendooAdd(Order $order, InstallmentOrder $installmentOrder)
    {
        $order->load('items.product.category');


        // prepare vendoo request body
        $vendooOrder = $this->prepareVendooOrder($order);


        $response = $this->vendooRequest('POST', 'orders', $vendooOrder);


        if ( $response && ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) ) {

            // get vendoo application id
            $content = $response->getBody()->getContents();

            $contentJson = json_decode($content);
            $applicationId = $contentJson->applicationId;
            // $status = $contentJson->status;

            $installmentOrder->vendor_order_id = $applicationId;
            // $installmentOrder->vendor_order_status = $status;
            $installmentOrder->save();

            // redirect to vendoo scoring
            return redirect()->away(config('services.vendoo.scoring_url') . $applicationId);
        }

        return redirect($order->url);
    }

    private function vendooRequest($method, $url, $array = [])
    {
        $base_uri = config('services.vendoo.env') == 'production' ? config('services.vendoo.api_url') : config('services.vendoo.api_test_url');

        $client = new Client([
            'base_uri' => $base_uri,
            'timeout' => 5,
        ]);

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(config('services.vendoo.login') . ':' . config('services.vendoo.password')),
                'Accept' => 'application/json',
            ],
        ];

        if ($array) {
            $options['json'] = $array;
        }

        try {
            $response = $client->request($method, $url, $options);

            return $response;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;

    }

    private function prepareVendooOrder($order)
    {
        $vendooOrder = [
            'orderId' => (string)$order->id,
            'shopId' => config('services.vendoo.shop_id'),
            'clientName' => $order->name,
            'cart' => [],
        ];
        foreach ($order->items as $orderItem) {
            // each product separate (no quantity field)
            for($i = 0; $i < $orderItem->quantity; $i++) {
                $vendooOrder['cart'][] = [
                    'name' => $orderItem->product->name,
                    'category' => $orderItem->product->category->normal_name ?? $orderItem->product->category->name, //$orderItem->product->category->id,
                    'price' => round($orderItem->price),
                    'sn' => (string)$orderItem->product->unique_code,
                ];
            }
        }
        return $vendooOrder;
    }
}
