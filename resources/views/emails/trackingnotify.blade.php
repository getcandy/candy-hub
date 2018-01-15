<h1>Your order has been shipped!</h1>

<p>Thank you for your order</p>

@if($order->tracking_no)
    <p>Your order tracking number is: {{ $order->tracking_no }}</p>
@endif