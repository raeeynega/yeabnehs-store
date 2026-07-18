<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->paymentAccounts = [
            'cbe' => [
                'bank_name' => 'Commercial Bank of Ethiopia (CBE)',
                'account_name' => 'YeaBneh Store PLC',
                'account_number' => '1000253121800',
                'branch' => 'Addis Ababa Main Branch',
                'swift_code' => 'CBETETAA',
            ],
            'telebirr' => [
                'service_name' => 'Telebirr',
                'merchant_name' => 'YeaBneh Store',
                'phone_number' => '+251911000000',
                'merchant_code' => 'YBS001',
            ],
        ];
    }

    public function selectMethod(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success', $order);
        }

        return view('payment.select', [
            'order' => $order,
            'accounts' => $this->paymentAccounts,
        ]);
    }

    public function showInstructions(Request $request, Order $order)
    {
        $request->validate(['method' => 'required|in:cbe,telebirr']);

        $account = $this->paymentAccounts[$request->method];

        return view('payment.instructions', [
            'order' => $order,
            'method' => $request->method,
            'account' => $account,
        ]);
    }

    public function submitPayment(Request $request, Order $order)
    {
        $request->validate([
            'method' => 'required|in:cbe,telebirr',
            'transaction_ref' => 'required|string|max:100',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
        ]);

        $payment = Payment::create([
            'payment_number' => 'YBS-PAY-' . strtoupper(Str::random(8)),
            'order_id' => $order->id,
            'method' => $request->method,
            'amount' => $order->total,
            'transaction_ref' => $request->transaction_ref,
            'sender_name' => $request->sender_name,
            'sender_phone' => $request->sender_phone,
            'status' => 'pending',
        ]);

        $order->update([
            'payment_method' => $request->method,
            'status' => 'processing',
        ]);

        return redirect()->route('payment.pending', $order);
    }

    public function pending(Order $order)
    {
        $payment = $order->payments()->latest()->first();
        $account = $this->paymentAccounts[$payment->method] ?? null;

        return view('payment.pending', [
            'order' => $order,
            'payment' => $payment,
            'account' => $account,
        ]);
    }

    public function success(Order $order)
    {
        $payment = $order->payments()->latest()->first();

        return view('payment.success', [
            'order' => $order,
            'payment' => $payment,
        ]);
    }
}
