<!DOCTYPE html>
<html>
<head>
    <title>Welcome Teacher</title>
</head>
<body>

<h2>Welcome {{ $user->name }}</h2>

<p>Your Teacher Account has been created successfully.</p>

<p><strong>Email:</strong> {{ $user->email }}</p>

<p>You can now login to the School Management System.</p>

<p>Thank You.</p>

</body>
</html>