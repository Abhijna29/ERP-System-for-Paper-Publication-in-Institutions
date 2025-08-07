@php
    // Map roles to layouts (adjust paths as per your project)
    $layouts = [
        'admin' => 'layouts.admin',
        'researcher' => 'layouts.researcher',
        'reviewer' => 'layouts.reviewer',
        'institution' => 'layouts.institution',
        'department' => 'layouts.department',
    ];

    // Pick layout or default to researcher layout
    $layout = $layouts[$role];
@endphp

@extends($layout)

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-4">Pay ₹ {{ $plan->price }} to subscribe to {{ $plan->name }}</h5>
        <p>Base Price: ₹{{ number_format($plan->price, 2) }}</p>
        <p>GST (18%): ₹{{ number_format($plan->price * 0.18, 2) }}</p>
        <p><strong>Total: ₹{{ number_format($plan->price + ($plan->price * 0.18), 2) }}</strong></p>

        <button id="rzp-button1" class="btn btn-success">{{ __('Pay with')}} Razorpay</button>

        <form id="payment-form" action="{{ route('subscription.payment.success') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="payment_id" id="payment_id">
        </form>
    </div>   
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{ $key }}",
        "amount": "{{ $order['amount'] }}",
        "currency": "INR",
        "name": "{{ $user->name }}",
        "description": "Subscription Payment",
        "order_id": "{{ $order['id'] }}",
        "handler": function (response){
            document.getElementById('payment_id').value = response.razorpay_payment_id;
            document.getElementById('payment-form').submit();
        },
        "prefill": {
            "name": "{{ $user->name }}",
            "email": "{{ $user->email }}"
        },
        "theme": {
            "color": "#0d6efd"
        }
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
    }
</script>
@endsection
