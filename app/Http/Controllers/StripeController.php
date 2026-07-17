<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeePaidMail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Create a Stripe Checkout session and redirect the user to Stripe.
     */
    public function checkout(Fee $fee)
    {
        if ($fee->isPaid()) {
            return redirect()->route('fees.show', $fee)->with('error', 'This fee has already been paid.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'pkr',
                    'product_data' => [
                        'name' => $fee->title,
                    ],
                    'unit_amount' => (int) round($fee->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('stripe.success', $fee->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('stripe.cancel', $fee->id),
        ]);

        return redirect()->away($session->url);
    }

    /**
     * Handle Stripe's success_url after payment.
     */
    public function success(Request $request, Fee $fee)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->query('session_id'));

        if ($session->payment_status === 'paid') {
            $fee->update([
                'status'         => 'paid',
                'payment_method' => 'stripe',
                'transaction_id' => $session->payment_intent,
                'paid_at'        => now(),
            ]);

            ActivityLogController::log('Fee', 'Payment', 'Fee #' . $fee->id . ' paid via Stripe');

            try {
                Mail::to($fee->student->user->email)->send(new FeePaidMail($fee));
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->route('fees.show', $fee)->with('success', 'Payment successful via Stripe!');
        }

        return redirect()->route('fees.pay', $fee)->with('error', 'Payment could not be verified.');
    }

    /**
     * Handle Stripe's cancel_url.
     */
    public function cancel(Fee $fee)
    {
        return redirect()->route('fees.pay', $fee)->with('error', 'Payment was cancelled.');
    }
}
