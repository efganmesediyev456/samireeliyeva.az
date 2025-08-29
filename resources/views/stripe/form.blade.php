<!DOCTYPE html>
<html>
<head>
    <title>Stripe Test Ödəniş</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form action="{{ route('stripe.post') }}" method="POST" id="payment-form">
        @csrf
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="{{ env('STRIPE_KEY') }}"
            data-amount="1000"
            data-name="Test Ödəniş"
            data-description="10$"
            data-currency="usd"
            data-locale="auto">
        </script>
    </form>
</body>
</html>
