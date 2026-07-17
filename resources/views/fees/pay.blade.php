@extends('layouts.star')

@section('title', 'Pay Fee')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">Pay Fee</h3>
    <a href="{{ route('fees.show', $fee->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body text-center">

                <p class="text-muted mb-1">{{ $fee->title }}</p>
                <h1 class="fw-bold mb-4">Rs {{ number_format($fee->amount, 0) }}</h1>

                <p class="text-muted mb-4">Choose a payment method to continue:</p>

                <div class="d-grid gap-3">
                    <a href="{{ route('paypal.checkout', $fee->id) }}"
                       class="btn btn-lg text-white"
                       style="background-color:#0070ba;">
                        <i class="mdi mdi-alpha-p-circle"></i> Pay with PayPal
                    </a>
                    <small class="text-muted">PayPal charges in USD equivalent (~${{ number_format($fee->amount / config('services.currency.pkr_to_usd_rate'), 2) }})</small>

                    <a href="{{ route('stripe.checkout', $fee->id) }}"
                       class="btn btn-lg text-white"
                       style="background-color:#635bff;">
                        <i class="mdi mdi-credit-card-outline"></i> Pay with Stripe (Card)
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
