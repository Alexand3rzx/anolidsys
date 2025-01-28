<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Health Management System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #9c27b0, #4caf50);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .header {
            text-align: center;
            background: url('{{ asset('images/hahaa.jpg') }}') no-repeat center center;
            background-size: cover;
            width: 100%;
            padding: 50px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
        }
        .header h1 {
            font-size: 2.5rem;
            margin: 0;
            color: white;
        }
        .header p {
            font-size: 1.2rem;
            margin-top: 10px;
            color: white;
        }
        .login-form {
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-form h2 {
            color: white;
            margin-bottom: 20px;
        }
        .login-form input {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .auth-links {
            text-align: center;
            margin-top: 10px;
        }
        .auth-links a {
            color: white;
            background-color: #4caf50;
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1rem;
        }
        .auth-links a:hover {
            background-color: #45a049;
        }
        .login-btn {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
            border: none;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Health Management System</h1>
        <p>Brgy. Anolid, Mangaldan, Pangasinan</p>
    </header>
    <br>
    </br>

    <div class="login-form">
        <h2>Login</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Login Button -->
            <div class="mt-4">
                <button type="submit" class="login-btn">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS (optional for some interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
