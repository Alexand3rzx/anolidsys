<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficiaries</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="bg-gradient-to-b from-red-800 via-red-500 to-red-300 text-white w-64 flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Health Management System</h1>
                <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
            </div>
            <nav class="flex-grow">
                <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-red-600">Dashboard</a>
                <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>
                <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 bg-red-600">Beneficiaries</a>
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
            <h2 class="text-3xl font-bold mb-6">Beneficiaries</h2>

            <!-- Search & Filter Section -->
            <form method="GET" action="{{ route('beneficiaries.index') }}" class="flex items-center gap-2 mb-4">
                <!-- Search Bar -->
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="border p-2 w-64 rounded" placeholder="Search...">

                <!-- Filter Dropdown -->
                <select name="category" class="border p-2 rounded">
                    <option value="">All Categories</option>
                    <option value="Pregnant" {{ request('category') == 'Pregnant' ? 'selected' : '' }}>Pregnant</option>
                    <option value="Infant" {{ request('category') == 'Infant' ? 'selected' : '' }}>Infant</option>
                </select>

                <!-- Submit Button -->
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Filter</button>
            </form>

            <!-- Beneficiaries Table -->
            <table class="w-full border-collapse bg-white shadow-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Category</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beneficiaries as $beneficiary)
                    <tr>
                        <td class="p-3 border">{{ $beneficiary->name }}</td>
                        <td class="p-3 border">{{ $beneficiary->category }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Add Beneficiary Buttons -->
            <button onclick="openModal('pregnant')" class="mt-4 bg-red-500 text-white py-2 px-4 rounded">Add Pregnant</button>
            <button onclick="openModal('infant')" class="mt-4 bg-red-500 text-white py-2 px-4 rounded">Add Infant</button>

            <!-- Pregnant Modal -->
            <div id="pregnantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg">
                    <h3 class="text-xl font-bold mb-4">Add Pregnant Beneficiary</h3>
                    <form method="POST" action="{{ route('beneficiaries.storePregnant') }}">
                        @csrf
                        <label class="block">Name</label>
                        <input type="text" name="name" required class="border p-2 w-full mb-2">

                        <label class="block">Birthday:</label>
                        <input type="date" name="birthday" required class="border p-2 rounded w-full mb-2">

                        <label class="block">Age:</label>
                        <input type="number" name="age" required class="border p-2 rounded w-full mb-2">

                        <label class="block">How many months pregnant:</label>
                        <input type="number" name="months_pregnant" required class="border p-2 rounded w-full mb-2">

                        <label class="block">Estimated Due Date:</label>
                        <input type="month" name="due_date" required class="border p-2 rounded w-full mb-2">

                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
                        <button type="button" onclick="closeModal('pregnant')" class="bg-gray-500 text-white py-2 px-4 rounded">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Infant Modal -->
            <div id="infantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg">
                    <h3 class="text-xl font-bold mb-4">Add Infant Beneficiary</h3>
                    <form method="POST" action="{{ route('beneficiaries.storeInfant') }}">
                        @csrf
                        <label class="block">Name</label>
                        <input type="text" name="name" required class="border p-2 w-full mb-2">

                        <label class="block">Birthday:</label>
                        <input type="date" name="birthday" required class="border p-2 rounded w-full mb-2">

                        <label class="block">Age (Months):</label>
                        <input type="number" name="age" required class="border p-2 rounded w-full mb-2">

                        <label class="block">Weight (kg):</label>
                        <input type="number" step="0.1" name="weight" required class="border p-2 rounded w-full mb-2">

                        <label class="block">Height (cm):</label>
                        <input type="number" name="height" required class="border p-2 rounded w-full mb-2">

                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Save</button>
                        <button type="button" onclick="closeModal('infant')" class="bg-gray-500 text-white py-2 px-4 rounded">Cancel</button>
                    </form>
                </div>
            </div>

            <script>
                // Open the appropriate modal
                function openModal(type) {
                    if (type === 'pregnant') {
                        document.getElementById('pregnantModal').classList.remove('hidden');
                    } else if (type === 'infant') {
                        document.getElementById('infantModal').classList.remove('hidden');
                    }
                }

                // Close the appropriate modal
                function closeModal(type) {
                    if (type === 'pregnant') {
                        document.getElementById('pregnantModal').classList.add('hidden');
                    } else if (type === 'infant') {
                        document.getElementById('infantModal').classList.add('hidden');
                    }
                }
            </script>
        </main>
    </div>

</body>
</html>
// we stopped adding UI dito sa beneficiaries index...