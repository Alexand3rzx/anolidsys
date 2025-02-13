<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Management System - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: black;
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="bg-gradient-to-b from-red-800 via-red-500 to-red-300 text-white w-64 flex flex-col">
        <div class="p-6">
            <h1 class="text-2xl font-bold">Health Management System</h1>
            <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
        </div>
        <nav class="flex-grow">
            <a href="{{ route('home') }}" class="block py-2.5 px-4 bg-red-600">Dashboard</a>
            <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>
            <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Beneficiaries</a>
            <a class="block py-2.5 px-4 hover:bg-red-600">Pregnant Women Tracking</a>
            <a class="block py-2.5 px-4 hover:bg-red-600">Babies Immunization</a>
        </nav>
        <footer class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700">
                    Logout
                </button>
            </form>
        </footer>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow p-6">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-600">Welcome to the Health Management System</p>
        </header>

        <!-- Chart Section -->
        <div class="bg-pink-100 shadow rounded-lg p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4">Inventory</h3>
            <div class="flex justify-center items-center h-80"> <!-- Adjusted height and centered -->
                <canvas id="medicineStockChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Beneficiary Statistics Section -->
<div class="grid grid-cols-2 gap-4">
    <!-- Pregnant Count Box -->
    <div class="bg-gray-700 text-white shadow-lg rounded-lg p-6 flex flex-col items-center">
        <h3 class="text-2xl font-bold">Pregnant Women</h3>
        <p class="text-4xl font-extrabold mt-2">{{ $pregnantCount }}</p>
    </div>

    <!-- Infant Count Box -->
    <div class="bg-gray-500 text-white shadow-lg rounded-lg p-6 flex flex-col items-center">
        <h3 class="text-2xl font-bold">Infants</h3>
        <p class="text-4xl font-extrabold mt-2">{{ $infantCount }}</p>
    </div>
</div>
    </main>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('medicineStockChart').getContext('2d');

            const medicineNames = @json($medicines->pluck('name'));
            const medicineStocks = @json($medicines->pluck('stock'));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: medicineNames,
                    datasets: [{
                        label: 'Stock Count',
                        data: medicineStocks,
                        backgroundColor: 'rgba(220, 38, 38, 0.6)',
                        borderColor: 'rgba(220, 38, 38, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false, // Allow chart to adjust to container size
                    scales: {
                        x: { beginAtZero: true },
                        y: { 
                            ticks: {
                                autoSkip: false, // Prevent labels from being skipped
                                font: {
                                    size: 12 // Adjust font size for y-axis labels
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top', // Move legend to the top
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>