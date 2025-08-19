<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #8B0000;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar img {
            height: 60px;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #a40000;
        }
        .main {
            flex-grow: 1;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background-color: #8B0000;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar button {
            background: none;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .content {
            flex-grow: 1;
            padding: 40px;
        }
        .welcome-box {
            background-color: white;
            border-radius: 8px;
            padding: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
    </div>
    <a href="{{ route('dashboard') }}" class="active">Dashboard</a>
    <a href="{{ route('medicine.request') }}">Medicine Request</a>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-light w-100">Logout</button>
    </form>
</div>

<!-- Main Content Area -->
<div class="main">
    <div class="topbar">
        <h4>User Dashboard</h4>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="welcome-box">
            <h2>Welcome to the Barangay Anolid Health Management System</h2>
            <p>You're logged in as a beneficiary.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
