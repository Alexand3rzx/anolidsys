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
        <button type="button" onclick="openReceiveModal('{{ $medicine->id }}', '{{ $medicine->name }}')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Receive</button>
        <button type="button" onclick="openGiveModal('{{ $medicine->id }}', '{{ $medicine->name }}')" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Give</button>
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

     <!-- Receive Modal -->
     <div id="receiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h3 class="text-xl font-bold mb-4">Receive Medicine</h3>
            <form id="receiveForm" action="{{ route('medicines.receive', $medicine) }}" method="POST">
                @csrf
                <input type="hidden" name="medicine_id" id="receiveMedicineId">
                <div class="mb-4">
                    <label class="block text-gray-700">Medicine Name</label>
                    <input type="text" id="receiveMedicineName" class="w-full p-2 border rounded" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Quantity</label>
                    <input type="number" name="quantity" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Who Donated?</label>
                    <input type="text" name="donor" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Who Received?</label>
                    <input type="text" name="receiver" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Additional Details</label>
                    <textarea name="details" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeReceiveModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Give Modal -->
    <div id="giveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h3 class="text-xl font-bold mb-4">Give Medicine</h3>
            <form id="giveForm" action="{{ route('medicines.give', $medicine) }}" method="POST">
                @csrf
                <input type="hidden" name="medicine_id" id="giveMedicineId">
                <div class="mb-4">
                    <label class="block text-gray-700">Medicine Name</label>
                    <input type="text" id="giveMedicineName" class="w-full p-2 border rounded" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Quantity</label>
                    <input type="number" name="quantity" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Who Administered?</label>
                    <input type="text" name="administered_by" class="w-full p-2 border rounded" value="{{ auth()->user()->name }}" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Who Received?</label>
                    <input type="text" name="receiver" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Additional Details</label>
                    <textarea name="details" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeGiveModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Receive Modal Functions
        function openReceiveModal(medicineId, medicineName) {
            document.getElementById('receiveMedicineId').value = medicineId;
            document.getElementById('receiveMedicineName').value = medicineName;
            document.getElementById('receiveModal').classList.remove('hidden');
        }

        function closeReceiveModal() {
            document.getElementById('receiveModal').classList.add('hidden');
        }

        // Give Modal Functions
        function openGiveModal(medicineId, medicineName) {
            document.getElementById('giveMedicineId').value = medicineId;
            document.getElementById('giveMedicineName').value = medicineName;
            document.getElementById('giveModal').classList.remove('hidden');
        }

        function closeGiveModal() {
            document.getElementById('giveModal').classList.add('hidden');
        }
    </script>

</body>
</html>
