<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medicine - Health Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
<aside class="bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white w-64 flex flex-col">
    <div class="p-6">
        <h1 class="text-2xl font-bold">Health Management System</h1>
        <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
    </div>
    <nav class="flex-grow">
        <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-red-600">Dashboard</a>
        <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>

        <!-- Beneficiaries Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-red-600 focus:outline-none">
                <span>Beneficiaries</span>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="ml-4 mt-1 space-y-1">
                <a href="{{ route('beneficiaries.pregnants') }}" class="block py-2.5 px-4 hover:bg-red-600">Pregnants</a>
                <a href="{{ route('beneficiaries.infants') }}" class="block py-2.5 px-4 hover:bg-red-600">Infants</a>
            </div>
        </div>

        @if(Auth::check() && Auth::user()->usertype === 'admin')
            <a href="{{ route('useradmin.create') }}" class="block py-2.5 px-4 hover:bg-red-600">Create User Admin</a>
        @endif
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

<!-- AlpineJS for dropdown -->
<script src="//unpkg.com/alpinejs" defer></script>


    <!-- Main Content -->
    <main class="flex-grow p-6">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Edit Medicine</h2>
            <p class="text-gray-600">Update the details of the selected medicine.</p>
        </header>

        <!-- Edit Medicine Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('medicines.update', $medicine) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Medicine Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $medicine->name) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
                    <textarea id="details" name="details" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>{{ old('details', $medicine->details) }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $medicine->stock) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Update Medicine</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
