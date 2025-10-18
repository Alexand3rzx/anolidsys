<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #fff;
            color: #333;
        }
        .navbar {
            padding: 1rem 2rem;
            background-color: white;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: #333;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
        }
        .navbar .btn-login {
            background-color: #8B4513;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .navbar .btn-login:hover {
            background-color: #5a2d0c;
        }
        .main-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: 3rem 5%;
            min-height: calc(100vh - 80px);
        }
        .text-column {
            flex: 1 1 50%;
            max-width: 600px;
        }
        .text-column h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #cc0000;
        }
        .text-column p {
            font-size: 1.1rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }
        .btn-start {
            background-color: #8B4513;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-start:hover {
            background-color: #5a2d0c;
        }
        .image-column {
            flex: 1 1 40%;
            text-align: center;
        }
        .image-column img {
            max-width: 100%;
            height: auto;
        }
        /* About Section */
        .about-section {
    padding: 60px 20px;
    background-color: #f9f9f9;
    text-align: center;
}

.about-section h2 {
    font-size: 28px;
    margin-bottom: 40px;
    font-weight: bold;
    color: #333;
}

.about-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.about-image img {
    width: 250px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.about-text {
    max-width: 400px;
    text-align: justify;
    font-size: 16px;
    line-height: 1.6;
}
        /* Contact Section */
        .contact-section {
            background-color: #cc0000;
            color: white;
            padding: 40px 5%;
            text-align: center;
        }
        .contact-section h4 {
            margin-bottom: 20px;
            font-weight: bold;
        }
        .contact-item {
            margin: 10px 0;
        }
        @media (max-width: 768px) {
            .main-section {
                flex-direction: column;
                text-align: center;
            }
            .text-column, .image-column, .about-text {
                flex: 1 1 100%;
            }
            .text-column h1 {
                font-size: 2rem;
            }
            .about-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px;">
        </div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#about">About</a>
            @if (Route::has('login'))
                @auth
                    <a class="btn btn-login" href="{{ url('/home') }}">Dashboard</a>
                @else
                    <a class="btn btn-login" href="{{ route('login') }}">Login</a>
                @endauth
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <section class="main-section">
        <div class="text-column">
            <h1>Welcome to the Barangay Anolid Health System</h1>
            <p>
                This platform helps manage health records, track beneficiaries, monitor pregnant women and infants, and streamline healthcare services within our community.
            </p>
        </div>
        <div class="image-column">
            <img src="{{ asset('images/logo.png') }}" alt="Health Illustration">
        </div>
    </section>

    <!-- About Us Section -->
<section id="about" class="about-section">
    <h2>ABOUT US: THE BARANGAY HEALTH WORKERS OF ANOLID</h2>
    <div class="about-content">
        <!-- Left Image -->
        <div class="about-image">
            <img src="{{ asset('images/about1.jpg') }}" alt="Barangay Event 1">
        </div>

        <!-- Center Text -->
        <div class="about-text">
            <p>Barangay Anolid is 1 of the 30 barangays located in Mangaldan, Pangasinan. With a total land area of 226.42 sq. hectares, it is the largest barangay in Mangaldan, housing an estimated 8,700 residents.</p>
            <p>The Barangay Health Workers (BHWs) are located at the center of Anolid, serving health services to assist residents with their health needs by providing medicine, check-ups, and monitoring.</p>
        </div>

        <!-- Right Image -->
        <div class="about-image">
            <img src="{{ asset('images/about2.jpg') }}" alt="Barangay Event 2">
        </div>
    </div>
</section>

    <!-- Contact Section -->
    <section class="contact-section">
        <h4>CONTACT US</h4>
        <div class="contact-item">📞 Phone: 0963 879 4967 | (075) 633 4068</div>
        <div class="contact-item">📧 Email: barangayanolid@gmail.com</div>
        <div class="contact-item">📘 Facebook: Sangguniang Barangay ng Anolid, Mangaldan, Pangasinan 2023-2025</div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
