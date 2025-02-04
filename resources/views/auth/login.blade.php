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
            background: linear-gradient(to bottom, #cc0000, #ff4d4d, #ffffff); /* Red gradient */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        .logo {
            width: 150px; /* Adjust logo size */
            height: 150px;
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin: 0;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2rem;
            margin-top: 10px;
            color: white;
        }

        input {
            display: block;
            width: 300px;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            outline: none;
            text-align: center;
        }

        .login-btn {
            background-color: #8B4513; /* Brown Login Button */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            width: 300px;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #5a2d0c; /* Darker brown on hover */
        }
    </style>
</head>
<body>
    <!-- Logo in the Center -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">

    <div class="header">
        <h1>Health Management System</h1>
        <p>Brgy. Anolid, Mangaldan, Pangasinan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">

        <!-- Password -->
        <input id="password" type="password" name="password" placeholder="Password" required autocomplete="current-password">

        <!-- Login Button -->
        <button type="submit" class="login-btn">Log in</button>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
