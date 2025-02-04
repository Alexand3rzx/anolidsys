<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Management System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #cc0000, #ff4d4d, #ffffff); /* Stronger red with white at bottom */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        .logo {
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin: 0;
            color: white; /* White title for better visibility */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.3rem;
            margin-top: 10px;
            color: white;
        }

        .description {
            margin: 20px auto;
            font-size: 1rem;
            text-align: center;
            max-width: 800px;
            line-height: 1.6;
            color: white; /* White text for better contrast */
        }

        .auth-links {
            margin-top: 20px;
            text-align: center;
        }

        .auth-links a {
            color: white;
            background-color: #8B4513; /* Brown background for buttons */
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .auth-links a:hover {
            background-color: #5a2d0c; /* Darker brown on hover */
        }
    </style>
</head>
<body>
    <!-- Logo in the Center -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">

    <!-- Header with System Name and Location -->
    <div class="header">
        <h1>Health Management System</h1>
        <p>Brgy. Anolid, Mangaldan, Pangasinan</p>
    </div>

    <!-- Description -->
    <div class="description">
        <p>
            Welcome to the Health Management System. This platform is designed to efficiently manage and monitor health-related activities and services in our community.
        </p>
    </div>

    <!-- Authentication Links -->
    <div class="auth-links">
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/home') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                @endauth
            </div>
        @endif
    </div>

    <!-- Bootstrap JS (optional for some interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
