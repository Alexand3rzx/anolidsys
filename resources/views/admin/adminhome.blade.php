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
            <a href="{{ route('useradmin.create') }}" class="block py-2.5 px-4 hover:bg-red-600">Create User Admin</a>
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

        <!-- Updated Medicine Inventory Section -->
        <section class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Medicine Inventory Overview</h3>

            @php
                $totalMedicines = $medicines->count();
                $lowStockCount = $medicines->where('stock', '<', 20)->count();
                $outOfStockCount = $medicines->where('stock', '<=', 0)->count();
                $expiringSoonCount = 0; // Add logic if expiration dates exist
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-red-100 p-4 rounded shadow">
                    <p class="text-gray-600">Total Medicines</p>
                    <h2 class="text-2xl font-bold text-red-800">{{ $totalMedicines }}</h2>
                </div>
                <div class="bg-yellow-100 p-4 rounded shadow">
                    <p class="text-gray-600">Low Stock Items (&lt; 20)</p>
                    <h2 class="text-2xl font-bold text-yellow-700">{{ $lowStockCount }}</h2>
                </div>
                <div class="bg-orange-100 p-4 rounded shadow">
                    <p class="text-gray-600">Expiring Soon</p>
                    <h2 class="text-2xl font-bold text-orange-700">{{ $expiringSoonCount }}</h2>
                </div>
                <div class="bg-green-100 p-4 rounded shadow">
                    <p class="text-gray-600">Out of Stock</p>
                    <h2 class="text-2xl font-bold text-green-800">{{ $outOfStockCount }}</h2>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Medicine Stock Table</h4>
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-left p-2 border">Medicine</th>
                            <th class="text-left p-2 border">Stock Left</th>
                            <th class="text-left p-2 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $medicine)
                            <tr class="border-t">
                                <td class="p-2 border">{{ $medicine->name }}</td>
                                <td class="p-2 border">{{ $medicine->stock }}</td>
                                <td class="p-2 border">
                                    @if ($medicine->stock <= 0)
                                        <span class="text-red-600 font-semibold">Out of Stock</span>
                                    @elseif ($medicine->stock < 20)
                                        <span class="text-yellow-600 font-semibold">Low Stock</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</body>
</html>
