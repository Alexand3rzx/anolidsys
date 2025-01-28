<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory - Health Management System</title>
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
                <h2 class="text-3xl font-bold text-gray-800">Medicine Inventory</h2>
                <p class="text-gray-600">Manage the stock and details of medicines.</p>
            </header>

            <!-- Medicine Inventory Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('medicines.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded mb-4 inline-block">Add Medicine</a>

                    @if(session('success'))
                        <div class="mb-4 text-green-500">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 text-red-500">{{ session('error') }}</div>
                    @endif

                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="text-left bg-gray-100">
                                <th class="px-4 py-2">Medicine Name</th>
                                <th class="px-4 py-2">Details</th>
                                <th class="px-4 py-2">Stock</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $medicine)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $medicine->name }}</td>
                                    <td class="px-4 py-2">{{ $medicine->details }}</td>
                                    <td class="px-4 py-2">{{ $medicine->stock }}</td>
                                    <td class="px-4 py-2">
                                        <form action="{{ route('medicines.receive', $medicine) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Receive</button>
                                        </form>
                                        <form action="{{ route('medicines.give', $medicine) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Give</button>
                                        </form>
                                        <a href="{{ route('medicines.edit', $medicine) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                                        <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
