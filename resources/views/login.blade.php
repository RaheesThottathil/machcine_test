<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Omnific Machine Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-card">
        <h1>Welcome Back</h1>
        <p>Login to your account</p>

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                    <button type="button" id="password-toggle" class="text-toggle">Show</button>
                </div>
            </div>
            <button type="submit">Sign in</button>
        </form>

        <div class="credentials-tip">
            <strong>Default Credentials:</strong><br>
            Admin: admin@example.com<br>
            Staff: staff@example.com<br>
            Client: client@example.com<br>
            Password: password
        </div>
    </div>
       <script src="{{ asset('js/login.js') }}"></script>

</body>
</html>
