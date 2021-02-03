<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Rules\CurrentPassword;
use App\Shop;
use App\User;
use App\UserApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show'), LinkItem::STATUS_INACTIVE));
        return view('profile.show', compact('breadcrumbs', 'user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.edit'), route('profile.edit'), LinkItem::STATUS_INACTIVE));
        return view('profile.edit', compact('breadcrumbs', 'user'));
    }

    public function update(Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:190'],
            'address' => ['max:190'],
        ]);
        Auth::user()->update($data);
        Session::flash('message', __('main.profile_saved'));
        return redirect()->back();
    }

    public function password(Request $request)
    {
        $data = $this->validate($request, [
            'current_password' => ['required', new CurrentPassword],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        Auth::user()->update([
            'password' => Hash::make($data['password']),
        ]);
        Session::flash('pmessage', __('main.password_saved'));
        return redirect()->back();
    }

    public function orders()
    {
        $user = Auth::user();
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.orders'), route('profile.orders'), LinkItem::STATUS_INACTIVE));
        $orders = $user->orders()->latest()->paginate(20);
        return view('profile.orders', compact('breadcrumbs', 'user', 'orders'));
    }

    public function requestSellerStatus()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.become_a_seller'), route('profile.request-seller-status'), LinkItem::STATUS_INACTIVE));

        $user = Auth::user();
        $userApplication = false;
        $errorText = __('main.you_cannot_become_a_seller');
        if ($user->role->name == 'user' || $user->role->name == 'seller') {
            $userApplication = UserApplication::firstOrCreate([
                'type' => UserApplication::TYPE_BECOME_SELLER,
                'user_id' => $user->id,
            ], [
                'status' => UserApplication::STATUS_PENDING,
            ]);
            if ($userApplication->wasRecentlyCreated) {
                Session::flash('message', __('main.request_accepted'));
            }
        }

        Helper::toTelegram('Новая заявка - Стать продавцом');

        return view('profile.request_seller_status', compact('userApplication', 'errorText'));
    }

    public function shopEdit(Request $request)
    {
        $shop = auth()->user()->shops()->first();
        if (!$shop) {
            $shop = new Shop();
        }
        return view('profile.shop.edit', compact('shop'));
    }

    public function shopUpdate(Request $request)
    {
        $shop = auth()->user()->shops()->first();
        if (!$shop) {
            abort(404);
        }
        $data = $this->validatedShopData($request);
        $data['status'] = Shop::STATUS_PENDING;
        $shop->update($data);

        Helper::storeImage($shop, 'image', 'shops', Shop::$imgSizes);

        Session::flash('message', __('main.shop_updated') . '. ' . __('main.pending_moderator_review'));
        return redirect()->route('profile.shop.edit');
    }

    protected function validatedShopData(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'image' => ['sometimes', 'image', 'max:1000'],
            'description' => ['max:1000'],
            'phone_number' => ['required'],
            'email' => ['required', 'email', 'max:190'],
            'address' => ['max:1000'],
        ]);
        return $data;
    }

    public function products()
    {
        $user = Auth::user();
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products'), LinkItem::STATUS_INACTIVE));
        $shop = $user->shops()->first();
        if (!$shop) {
            $shop = new Shop();
        }
        $products = $shop->products()->latest()->paginate(20);
        return view('profile.products', compact('breadcrumbs', 'user', 'products'));
    }
}
