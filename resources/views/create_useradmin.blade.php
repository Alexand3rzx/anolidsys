<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Admin - BAHMS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white w-64 flex flex-col">
        <div class="p-6">
            <h1 class="text-2xl font-bold">Health Management System</h1>
            <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
        </div>
        <nav class="flex-grow">
            <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-red-600">Dashboard</a>
            <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>
            <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Beneficiaries</a>
            <a href="{{ route('useradmin.create') }}" class="block py-2.5 px-4 bg-red-600">Create User Admin</a>
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
    <main class="flex-1 p-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-red-600 mb-6">Create User Admin</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    <ul class="list-disc pl-6">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('useradmin.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <div>
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <div>
                    <label class="block text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <div>
                    <label class="block text-gray-700">Purok</label>
                    <select name="purok" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Select Purok</option>
                        <option value="Purok 1">Purok 1</option>
                        <option value="Purok 2">Purok 2</option>
                        <option value="Purok 3">Purok 3</option>
                        <option value="Purok 4">Purok 4</option>
                        <option value="Purok 5">Purok 5</option>
                    </select>
                </div>

                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Create User Admin</button>
            </form>
        </div>
    </main>

</body>
</html>
