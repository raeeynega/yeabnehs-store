<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $subtotal = 0;

        foreach ($cart as $slug => $qty) {
            $product = \App\Models\Product::where('slug', $slug)->with('images')->first();
            if ($product) {
                $products[$slug] = ['product' => $product, 'qty' => $qty];
                $subtotal += $product->price * $qty;
            }
        }

        return view('cart', compact('products', 'subtotal'));
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);
        $slug = $request->slug;
        $qty = $request->get('qty', 1);
        $cart[$slug] = ($cart[$slug] ?? 0) + $qty;
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        if ($request->qty <= 0) {
            unset($cart[$request->slug]);
        } else {
            $cart[$request->slug] = $request->qty;
        }
        session()->put('cart', $cart);
        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->slug]);
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Removed from cart.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $subtotal = 0;

        foreach ($cart as $slug => $qty) {
            $product = \App\Models\Product::where('slug', $slug)->with('images')->first();
            if ($product) {
                $products[$slug] = ['product' => $product, 'qty' => $qty];
                $subtotal += $product->price * $qty;
            }
        }

        if (empty($products)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $shipping = $subtotal >= 100 ? 0 : 9.99;
        $user = Auth::user();

        return view('checkout', compact('products', 'subtotal', 'shipping', 'user'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'shipping_address' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        $orderItems = [];

        foreach ($cart as $slug => $qty) {
            $product = \App\Models\Product::where('slug', $slug)->first();
            if ($product) {
                $subtotal += $product->price * $qty;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'price' => $product->price,
                ];
            }
        }

        $shipping = $subtotal >= 100 ? 0 : 9.99;

        $order = Order::create([
            'order_number' => 'YBS-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->get('customer_phone'),
            'shipping_address' => $request->shipping_address,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $subtotal + $shipping,
            'payment_method' => 'pending',
            'notes' => $request->get('notes'),
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        session()->forget('cart');

        return redirect()->route('payment.select', $order->order_number)
            ->with('success', 'Order placed! Now choose your payment method.');
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('order-confirmation', compact('order'));
    }
}
