<!DOCTYPE html>
<html>
<head>
    <title>Welcome Student</title>
</head>
<body>

<h2>Welcome {{ $user->name }}</h2>

<p>Your Student Account has been created successfully.</p>

<p><strong>Email:</strong> {{ $user->email }}</p>

<p>You can now login to the School Management System.</p>

<p>Thank You.</p>

</body>
</html>