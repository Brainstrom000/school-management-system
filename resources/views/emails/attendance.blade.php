<!DOCTYPE html>
<html>
<head>
    <title>Attendance Update</title>
</head>
<body>

<h2>Attendance Update</h2>

<p>Dear {{ $attendance->student->user->name ?? $attendance->student->name }},</p>

<p>Your attendance has been marked for <strong>{{ $attendance->date->format('d M Y') }}</strong> as:</p>

<p style="font-size: 18px;">
    <strong>
        @if($attendance->status === 'Present')
            <span style="color: #4caf50;">Present ✅</span>
        @elseif($attendance->status === 'Absent')
            <span style="color: #f44336;">Absent ❌</span>
        @else
            <span style="color: #ff9800;">Leave 🟡</span>
        @endif
    </strong>
</p>

<p>If you believe this is a mistake, please contact your school administration.</p>

<p>Thank You.</p>

</body>
</html>
