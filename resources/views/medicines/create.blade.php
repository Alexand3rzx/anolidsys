<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Medicine - Health Management System</title>
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
                <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-blue-800">Medicine Inventory</a>
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
                <h2 class="text-3xl font-bold text-gray-800">Add New Medicine</h2>
                <p class="text-gray-600">Enter the details of the new medicine to add it to the inventory.</p>
            </header>

            <!-- Create Medicine Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4 text-red-500">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Medicine Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
                            <textarea id="details" name="details" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>{{ old('details') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock') }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add Medicine</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
// maglalagay ng functions sa receive tas give