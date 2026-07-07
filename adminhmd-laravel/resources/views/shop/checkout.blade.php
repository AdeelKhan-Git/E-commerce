@extends('layouts.shop')
@section('title', 'Checkout')

@section('content')
<div class="container" style="padding:40px 0;">
    <h2 class="section-title">Check<span class="accent">out</span></h2>
    <div id="checkout-message"></div>
    @if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="row g-4">
        {{-- Left: Billing Form --}}
        <div class="col-lg-7">
            <div class="checkout-card">
                <h5 style="font-weight:700;margin-bottom:20px;color:var(--stormy-teal);">
                    <i class="bi bi-person-lines-fill me-2"></i>Billing Details
                </h5>
                <div id="billing-fields">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-dark">Full Name *</label>
                            <input type="text" id="full_name" class="form-control-dark" value="{{ $user->username }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dark">Email *</label>
                            <input type="text" id="email" class="form-control-dark" value="{{ $user->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dark">Phone *</label>
                            <input type="text" id="phone" class="form-control-dark" value="{{ $user->phone_number }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-dark">Street Address *</label>
                            <input type="text" id="address" class="form-control-dark" value="{{ $user->address }}" placeholder="House number and street name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dark">City *</label>
                            <input type="text" id="city" class="form-control-dark" value="{{ $user->city }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dark">Postcode</label>
                            <input type="text" id="postcode" class="form-control-dark" value="{{ $user->postcode }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-dark">Order Notes</label>
                            <textarea id="notes" class="form-control-dark" rows="3" placeholder="Special instructions for delivery..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Order Summary + Payment --}}
        <div class="col-lg-5">
            {{-- Order Summary --}}
            <div class="checkout-card mb-4">
                <h5 style="font-weight:700;margin-bottom:20px;color:var(--stormy-teal);">
                    <i class="bi bi-bag-check me-2"></i>Your Order
                </h5>
                <table style="width:100%;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:8px 0;color:var(--text-muted);font-size:13px;">Product</td>
                            <td style="padding:8px 0;color:var(--text-muted);font-size:13px;text-align:right;">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart_items as $item)
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:10px 0;font-size:14px;">
                                {{ $item->product->product_name }}
                                <span style="color:var(--text-muted);">× {{ $item->quantity }}</span>
                            </td>
                            <td style="padding:10px 0;text-align:right;color:var(--stormy-teal);">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="padding:12px 0;font-weight:700;font-size:16px;">Order Total</td>
                            <td style="padding:12px 0;text-align:right;color:var(--stormy-teal);font-weight:900;font-size:20px;">${{ number_format($grand_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Payment Method --}}
            <div class="checkout-card">
                <h5 style="font-weight:700;margin-bottom:20px;color:var(--stormy-teal);">
                    <i class="bi bi-credit-card me-2"></i>Payment Method
                </h5>
                <div class="d-flex gap-3 mb-4">
                    <div class="pay-tab active flex-fill" onclick="selectPay('cod', this)">
                        <i class="bi bi-cash-stack d-block mb-1" style="font-size:24px;"></i>
                        Cash on Delivery
                    </div>
                    <div class="pay-tab flex-fill" onclick="selectPay('stripe', this)">
                        <i class="bi bi-credit-card d-block mb-1" style="font-size:24px;"></i>
                        Pay with Card
                    </div>
                </div>

                {{-- COD --}}
                <div class="pay-section active" id="section-cod">
                    <div style="background:var(--alice-blue);border:1px solid var(--border);border-radius:8px;padding:15px;margin-bottom:15px;">
                        <p style="color:var(--text-muted);margin:0;font-size:14px;">
                            <i class="bi bi-info-circle me-2" style="color:var(--stormy-teal);"></i>
                            Pay with cash when your order arrives at your door.
                        </p>
                    </div>
                    <button
                        type="button"
                        class="btn-primary-custom w-100"
                        style="font-size:16px;padding:14px;"
                        id="cod-btn"
                        onclick="submitCOD()"
                    >
                        <span id="cod-btn-text">
                            Place Order — Cash on Delivery
                        </span>

                        <span id="cod-spinner" style="display:none;">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Processing...
                        </span>
                    </button>
                </div>

                {{-- Stripe --}}
                <div class="pay-section" id="section-stripe">
                    <div id="card-element" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:15px;margin-bottom:10px;"></div>
                    <div id="card-errors" style="color:#dc3545;font-size:13px;min-height:20px;margin-bottom:10px;"></div>
                    <div style="color:var(--text-muted);font-size:12px;margin-bottom:15px;">
                        <i class="bi bi-lock me-1"></i>Secured by Stripe
                    </div>
                    <button type="button" class="btn-primary-custom w-100" style="font-size:16px;padding:14px;" id="stripe-btn" onclick="submitStripe()">
                        <span id="stripe-btn-text">Pay ${{ number_format($grand_total, 2) }}</span>
                        <span id="stripe-spinner" style="display:none;"><i class="bi bi-arrow-repeat"></i> Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden COD Form --}}
<form method="POST" action="{{ route('checkout.store') }}" id="cod-form" style="display:none;">
    @csrf
    <input type="hidden" name="full_name" id="h_full_name">
    <input type="hidden" name="email"     id="h_email">
    <input type="hidden" name="phone"     id="h_phone">
    <input type="hidden" name="address"   id="h_address">
    <input type="hidden" name="city"      id="h_city">
    <input type="hidden" name="postcode"  id="h_postcode">
    <input type="hidden" name="notes"     id="h_notes">
</form>

@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>

<script>
const stripe   = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();

const card = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#1a2e30',
            iconColor: '#006d77',
            fontFamily: '"Segoe UI", sans-serif',
            '::placeholder': {
                color: '#6c757d'
            }
        },
        invalid: {
            color: '#dc3545',
            iconColor: '#dc3545'
        }
    }
});

card.mount('#card-element');

card.on('change', function (event) {
    document.getElementById('card-errors').textContent =
        event.error ? event.error.message : '';
});

function selectPay(method, el) {
    document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.pay-section').forEach(s => s.classList.remove('active'));

    el.classList.add('active');
    document.getElementById('section-' + method).classList.add('active');
}

function getBilling() {
    return {
        full_name: document.getElementById('full_name').value.trim(),
        email:     document.getElementById('email').value.trim(),
        phone:     document.getElementById('phone').value.trim(),
        address:   document.getElementById('address').value.trim(),
        city:      document.getElementById('city').value.trim(),
        postcode:  document.getElementById('postcode').value.trim(),
        notes:     document.getElementById('notes').value.trim(),
    };
}

function validate(d) {
    if (!d.full_name) { showMessage('Full name is required'); return false; }
    if (!d.email)     { showMessage('Email is required'); return false; }
    if (!d.phone)     { showMessage('Phone is required'); return false; }
    if (!d.address)   { showMessage('Address is required'); return false; }
    if (!d.city)      { showMessage('City is required'); return false; }
    return true;
}

/* =========================
   CASH ON DELIVERY
========================= */
function submitCOD() {

    const d = getBilling();
    if (!validate(d)) return;

    Object.keys(d).forEach(key => {
        document.getElementById('h_' + key).value = d[key];
    });

    const btn = document.getElementById('cod-btn');

    btn.disabled = true;
    document.getElementById('cod-btn-text').style.display = 'none';
    document.getElementById('cod-spinner').style.display = 'inline-flex';

    document.getElementById('cod-form').submit();
}

/* =========================
   STRIPE PAYMENT FLOW
========================= */
async function submitStripe() {

    const d = getBilling();
    if (!validate(d)) return;

    const btn = document.getElementById('stripe-btn');

    btn.disabled = true;
    document.getElementById('stripe-btn-text').style.display = 'none';
    document.getElementById('stripe-spinner').style.display = 'inline';

    try {

        /* STEP 1: Create PaymentIntent */
        const response = await fetch('{{ route("checkout.stripe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(d)
        });

        const data = await response.json();

        if (data.error) {
            showMessage(data.error);
            resetStripeButton();
            return;
        }

        /* STEP 2: Confirm Payment with Stripe */
        const result = await stripe.confirmCardPayment(
            data.clientSecret,
            {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: d.full_name,
                        email: d.email,
                        phone: d.phone
                    }
                }
            }
        );

        if (result.error) {
            document.getElementById('card-errors').textContent = result.error.message;
            resetStripeButton();
            return;
        }

        /* STEP 3: REDIRECT TO SUCCESS (IMPORTANT FIX) */
        if (result.paymentIntent.status === 'succeeded') {

            window.location.href =
                "{{ route('checkout.stripe.success') }}" +
                "?payment_intent=" + result.paymentIntent.id;
        }

    } catch (e) {
        console.log(e);
        showMessage('Something went wrong');
        resetStripeButton();
    }
}

function resetStripeButton() {
    document.getElementById('stripe-btn').disabled = false;
    document.getElementById('stripe-btn-text').style.display = 'inline';
    document.getElementById('stripe-spinner').style.display = 'none';
}

function showMessage(message, type = 'danger') {

    const box = document.getElementById('checkout-message');

    box.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    `;

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>
@endpush