<!DOCTYPE html>
<html>
<head>
    <title>Fee Payment Received</title>
</head>
<body>

<h2>Fee Payment Received ✅</h2>

<p>Dear {{ $fee->student->user->name ?? $fee->student->name }},</p>

<p>We have received your payment for <strong>{{ $fee->title }}</strong>.</p>

<table cellpadding="6" cellspacing="0" style="border-collapse: collapse; margin: 12px 0;">
    <tr>
        <td><strong>Amount Paid:</strong></td>
        <td>Rs {{ number_format($fee->amount, 0) }}</td>
    </tr>
    <tr>
        <td><strong>Payment Method:</strong></td>
        <td>{{ ucfirst($fee->payment_method ?? 'N/A') }}</td>
    </tr>
    <tr>
        <td><strong>Transaction ID:</strong></td>
        <td>{{ $fee->transaction_id ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Paid On:</strong></td>
        <td>{{ optional($fee->paid_at)->format('d M Y, h:i A') }}</td>
    </tr>
</table>

<p>Thank you for your payment. This is an automated receipt, please keep it for your records.</p>

<p>Thank You.</p>

</body>
</html>
