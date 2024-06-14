<!DOCTYPE html>
<html>
<head>
    <title>Transaction Receipt</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Transaction ID: {{ $transaction->id }}</p>
    <p>User ID: {{ $transaction->user_id }}</p>
    <p>Amount: {{ $transaction->amount }}</p>
    <p>Payment Method: {{ $transaction->payment_method }}</p>
    <p>Status: {{ $transaction->status }}</p>
    <p>Date: {{ $date }}</p>
</body>
</html>