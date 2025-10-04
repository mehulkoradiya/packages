<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\Invoice;
use Vendor\NurseryManagementSystem\Models\Payment;

class PaymentController extends BaseController
{
    public function store(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'provider' => 'nullable|string',
            'provider_ref' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $payment = new Payment($data);
        $payment->currency = $invoice->currency;
        $invoice->payments()->save($payment);

        $paid = $invoice->payments()->sum('amount');
        $invoice->status = $paid >= $invoice->total ? 'paid' : 'partially_paid';
        $invoice->save();

        return back()->with('success', 'Payment recorded');
    }

    public function webhook(Request $request, string $provider)
    {
        // Stub: handle provider webhooks
        return response()->json(['ok' => true]);
    }
}
