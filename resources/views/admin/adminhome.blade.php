<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Management System - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="flex h-screen">
        <aside class="bg-blue-900 text-white w-64 flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Health Management System</h1>
                <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
            </div>
            <nav class="flex-grow">
                <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-blue-800">Dashboard</a>
                <!-- Update this link to the medicine inventory route -->
                <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-blue-800">Medicine Inventory</a>
                <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 hover:bg-blue-800">Beneficiaries</a>
                <a class="block py-2.5 px-4 hover:bg-blue-800">Pregnant Women Tracking</a>
                <a class="block py-2.5 px-4 hover:bg-blue-800">Babies Immunization</a>
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

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Medicine Inventory -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-2">Medicine Inventory</h3>
                    <p class="text-gray-600">Manage medicine stock and prescriptions.</p>
                    <!-- Link to Medicine Inventory -->
                    <a href="{{ route('medicines.index') }}" class="block mt-4 text-blue-500 hover:underline">View Inventory</a>
                </div>

                <!-- Pregnant Women Tracking -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-2">Pregnant Women Tracking</h3>
                    <p class="text-gray-600">Track attendance for checkups, vitamins, and vaccines.</p>
                    <a  class="block mt-4 text-blue-500 hover:underline">View Tracking</a>
                </div>

                <!-- Babies Immunization Tracking -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-2">Babies Immunization</h3>
                    <p class="text-gray-600">Monitor immunization schedules and attendance.</p>
                    <a href="#" class="block mt-4 text-blue-500 hover:underline">View Immunization</a>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
