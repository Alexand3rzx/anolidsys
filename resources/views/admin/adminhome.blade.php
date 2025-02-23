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
    <aside class="bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white w-64 flex flex-col">
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
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Medicine Inventory</h3>
    <div class="flex justify-center items-center h-80">
        <canvas id="medicineStockChart" class="w-full h-full"></canvas>
    </div>
    <div class="flex justify-center mt-4">
        <button id="prevPage" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Previous</button>
        <button id="nextPage" class="bg-red-500 text-white px-4 py-2 rounded">Next</button>
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

   <!-- Include Chart.js datalabels plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('medicineStockChart').getContext('2d');

        // Medicines data from the server
        const medicineNames = @json($medicines->pluck('name'));
        const medicineStocks = @json($medicines->pluck('stock'));

        const medicinesPerPage = 8;
        let currentPage = 1;

        // Function to update chart data based on the current page
        function updateChartData(page) {
            const startIndex = (page - 1) * medicinesPerPage;
            const endIndex = startIndex + medicinesPerPage;
            const pageMedicines = medicineNames.slice(startIndex, endIndex);
            const pageStocks = medicineStocks.slice(startIndex, endIndex);

            chart.data.labels = pageMedicines;
            chart.data.datasets[0].data = pageStocks;
            chart.update();
        }

        // Initialize the chart with the first page
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Will be populated later
                datasets: [{
                    label: 'Stock Count',
                    data: [], // Will be populated later
                    backgroundColor: 'rgba(220, 38, 38, 0.6)', // Your red color
                    borderColor: 'rgba(220, 38, 38, 1)', // Border color
                    borderWidth: 1,
                    borderRadius: 4, // Rounded corners for bars
                    hoverBackgroundColor: 'rgba(220, 38, 38, 0.8)', // Darker on hover
                }]
            },
            options: {
                indexAxis: 'y', // Horizontal bars
                responsive: true,
                maintainAspectRatio: false, // Allow chart to adjust to container size
                scales: {
                    x: { 
                        display: false, // Hide x-axis (bottom numbers)
                    },
                    y: { 
                        grid: {
                            display: false, // Remove grid lines for y-axis
                        },
                        ticks: {
                            autoSkip: false, // Prevent labels from being skipped
                            font: {
                                size: 12, // Font size for y-axis labels
                            },
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false, // Hide legend (optional)
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)', // Dark tooltip background
                        titleFont: { size: 14 },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 4,
                    },
                    datalabels: { // Plugin to display stock totals beside bars
                        anchor: 'end', // Position the label at the end of the bar
                        align: 'right', // Align the label to the right
                        color: 'rgba(0, 0, 0, 0.8)', // Dark text color
                        font: {
                            size: 12, // Font size for the labels
                            weight: 'bold', // Bold text
                        },
                        formatter: (value) => {
                            return value; // Display the stock count
                        },
                    },
                },
            },
        });

        // Initial load with first page
        updateChartData(currentPage);

        // Pagination buttons
        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateChartData(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (currentPage * medicinesPerPage < medicineNames.length) {
                currentPage++;
                updateChartData(currentPage);
            }
        });
    });
</script>




</body>
</html>