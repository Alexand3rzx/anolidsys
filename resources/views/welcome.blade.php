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
            background: linear-gradient(to bottom, #9c27b0, #4caf50); /* Purple to Green Gradient */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            overflow: hidden;
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

        .description {
            margin: 20px auto;
            font-size: 1rem;
            text-align: center;
            max-width: 800px;
            line-height: 1.6;
        }

        .box {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            color: white;
            background: linear-gradient(135deg, #4caf50, #8bc34a); /* Green Gradient */
        }

        .box img {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
        }

        .auth-links {
            margin-top: 20px;
            text-align: center;
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

        /* Different Gradient for each Box */
        .box-1 {
            background: linear-gradient(135deg, #f44336, #ff5722); /* Red to Orange */
        }

        .box-2 {
            background: linear-gradient(135deg, #2196f3, #03a9f4); /* Blue */
        }

        .box-3 {
            background: linear-gradient(135deg, #ff9800, #ffeb3b); /* Yellow to Orange */
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Health Management System</h1>
        <p>Brgy. Anolid, Mangaldan, Pangasinan</p>
    </header>

    <section class="description">
        <p>
            Welcome to the Health Management System. This platform is designed to efficiently manage and monitor health-related activities and services in our community.
        </p>
    </section>

    <!-- Three Health Boxes with Different Gradients -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-1">
                    <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons/health.svg" alt="Health Icon">
                    <h3>Health Monitoring</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis magna vitae leo egestas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-2">
                    <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons/heart.svg" alt="Health Icon">
                    <h3>Medical Assistance</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis magna vitae leo egestas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-3">
                    <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons/medkit.svg" alt="Health Icon">
                    <h3>Health Tips</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis magna vitae leo egestas.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Authentication Links -->
    <div class="auth-links">
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/home') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <!-- Add Register Link if necessary -->
                    @endif
                @endauth
            </div>
        @endif
    </div>

    <!-- Bootstrap JS (optional for some interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
