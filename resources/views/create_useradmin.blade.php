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
        <a href="{{ route('home') }}" class="block py-2.5 px-4 bg-red-600">Dashboard</a>
        <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>
        <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Beneficiaries</a>

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

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto bg-gray-50">
        <div class="max-w-5xl mx-auto space-y-10">

            <!-- Create User Admin Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-red-600">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Create User Admin</h2>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-800 p-4 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-800 p-4 rounded mb-6">
                        <ul class="list-disc pl-6">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('useradmin.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Name</label>
                        <input type="text" name="name" 
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email</label>
                        <input type="email" name="email" 
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" 
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 pr-10" required>
                            <button type="button" onclick="togglePassword('password', this)" 
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-500">
                                Show
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 pr-10" required>
                            <button type="button" onclick="togglePassword('password_confirmation', this)" 
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-500">
                                Show
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-medium mb-1">Purok</label>
                        <select name="purok" 
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" required>
                            <option value="">Select Purok</option>
                            <option value="purok1">Purok 1</option>
                            <option value="purok2">Purok 2</option>
                            <option value="purok3">Purok 3</option>
                            <option value="purok4">Purok 4</option>
                            <option value="purok5">Purok 5</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" 
                            class="w-full bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700">
                            Create User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Manage User Admins Table -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-red-600">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Manage User Admins</h2>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-red-600 text-white">
                            <tr>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Purok</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($useradmins as $admin)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-700">{{ $admin->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $admin->email }}</td>
                                    <td class="px-6 py-3 capitalize text-gray-600">{{ $admin->purok }}</td>
                                    <td class="px-6 py-3 flex space-x-2">
                                        <!-- Edit Modal Trigger -->
                                        <button onclick="openEditModal({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->email }}', '{{ $admin->purok }}')" 
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            Edit
                                        </button>
                                        <!-- Delete Modal Trigger -->
                                        <button onclick="openDeleteModal({{ $admin->id }}, '{{ $admin->name }}')" 
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No user admins created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">Edit User Admin</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="editName" class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Purok</label>
                    <select name="purok" id="editPurok" class="w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm">
                        <option value="purok1">Purok 1</option>
                        <option value="purok2">Purok 2</option>
                        <option value="purok3">Purok 3</option>
                        <option value="purok4">Purok 4</option>
                        <option value="purok5">Purok 5</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">Delete User Admin</h3>
            <p id="deleteMessage" class="text-gray-700 mb-6"></p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            btn.textContent = "Hide";
        } else {
            input.type = "password";
            btn.textContent = "Show";
        }
    }

    function openEditModal(id, name, email, purok) {
        document.getElementById('editForm').action = `/useradmin/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editPurok').value = purok;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteForm').action = `/useradmin/${id}`;
        document.getElementById('deleteMessage').textContent = `Are you sure you want to delete "${name}"?`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    </script>

</body>
</html>
