<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeePaidMail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    /**
     * Redirect the user to PayPal to approve the payment.
     */
    public function checkout(Fee $fee)
    {
        if ($fee->isPaid()) {
            return redirect()->route('fees.show', $fee)->with('error', 'This fee has already been paid.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        // PayPal's core REST API does not support PKR as a transaction
        // currency, so we convert the PKR fee amount to its USD equivalent.
        $usdAmount = round($fee->amount / config('services.currency.pkr_to_usd_rate'), 2);

        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('paypal.success', $fee->id),
                'cancel_url' => route('paypal.cancel', $fee->id),
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value'         => number_format($usdAmount, 2, '.', ''),
                    ],
                    'description' => $fee->title . ' (Rs ' . number_format($fee->amount, 0) . ' converted to USD)',
                ],
            ],
        ]);

        if (isset($response['id']) && $response['id'] !== null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('fees.pay', $fee)->with('error', 'Could not connect to PayPal. Please try again.');
    }

    /**
     * Handle PayPal's return_url after the user approves the payment.
     */
    public function success(Request $request, Fee $fee)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $response = $provider->capturePaymentOrder($request->query('token'));

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $fee->update([
                'status'         => 'paid',
                'payment_method' => 'paypal',
                'transaction_id' => $response['id'] ?? $request->query('token'),
                'paid_at'        => now(),
            ]);

            ActivityLogController::log('Fee', 'Payment', 'Fee #' . $fee->id . ' paid via PayPal');

            try {
                Mail::to($fee->student->user->email)->send(new FeePaidMail($fee));
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->route('fees.show', $fee)->with('success', 'Payment successful via PayPal!');
        }

        return redirect()->route('fees.pay', $fee)->with('error', 'Payment could not be completed.');
    }

    /**
     * Handle PayPal's cancel_url.
     */
    public function cancel(Fee $fee)
    {
        return redirect()->route('fees.pay', $fee)->with('error', 'Payment was cancelled.');
    }
}
