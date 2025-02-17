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
    <aside class="bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white w-64 flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Health Management System</h1>
                <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
            </div>
            <nav class="flex-grow">
                <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-red-600">Dashboard</a>
                <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 bg-red-600">Medicine Inventory</a>
                <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Beneficiaries</a>
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
    <div class="flex items-center justify-between mb-4">
        <!-- Add Medicine Button -->
        <a href="{{ route('medicines.create') }}" class="px-4 py-2 bg-red-500 text-white rounded">Add Medicine</a> 
        
        <!-- Search Bar -->
        <form method="GET" action="{{ route('medicines.index') }}" class="flex" id="searchForm">
    <input type="text" name="search" value="{{ request('search') }}" 
           placeholder="Search medicines..." 
           class="px-4 py-2 border rounded-l w-64" id="searchInput">
    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-r hover:bg-red-600">Search</button>
</form>
    </div>
                    

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
            <th class="px-4 py-2">Expiration Date</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medicines as $medicine)
        <tr class="border-b">
            <td class="px-4 py-2">{{ $medicine->name }}</td>
            <td class="px-4 py-2">{{ $medicine->details }}</td>
            <td class="px-4 py-2">{{ $medicine->stock }}</td>
            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($medicine->expiration)->format('F Y') }}</td>
            <td class="px-4 py-2">
                <button type="button" onclick="openReceiveModal('{{ $medicine->id }}', '{{ $medicine->name }}')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Receive</button>
                <button type="button" onclick="openGiveModal('{{ $medicine->id }}', '{{ $medicine->name }}')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Give</button>
                <button 
    type="button" 
    onclick="openEditModal(
        '{{ $medicine->id }}', 
        '{{ $medicine->name }}', 
        '{{ $medicine->details }}', 
        '{{ $medicine->stock }}', 
        '{{ \Carbon\Carbon::parse($medicine->expiration)->format('Y-m-d') }}'  // Proper date format for the input field
    )" 
    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
    Edit
</button>
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
<div id="receiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
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
                <input type="text" name="receiver" class="w-full p-2 border rounded bg-gray-200" value="{{ Auth::user()->name }}" readonly>
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
<div id="giveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="text-xl font-bold mb-4">Give Medicine</h3>
        <form id="giveForm" action="{{ route('medicines.give', $medicine) }}" method="POST">
            @csrf
            <input type="hidden" name="medicine_id" id="giveMedicineId">
            
            <div class="mb-4">
                <label class="block text-gray-700">Medicine Name</label>
                <input type="text" id="giveMedicineName" class="w-full p-2 border rounded bg-gray-200" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Quantity</label>
                <input type="number" name="quantity" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Who Administered?</label>
                <input type="text" name="administered_by" class="w-full p-2 border rounded bg-gray-200" value="{{ Auth::user()->name }}" readonly>
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


<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="text-xl font-bold mb-4">Edit Medicine</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="medicine_id" id="editMedicineId">

            <div class="mb-4">
                <label class="block text-gray-700">Medicine Name</label>
                <input type="text" id="editMedicineName" name="name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Details</label>
                <textarea id="editMedicineDetails" name="details" class="w-full p-2 border rounded" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Stock</label>
                <input type="number" id="editMedicineStock" name="stock" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Expiration Date</label>
                <input type="date" id="editMedicineExpiration" name="expiration" class="w-full p-2 border rounded" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<script>

document.getElementById('searchInput').addEventListener('input', function() {
        // Get the input value
        let searchQuery = this.value.toLowerCase();
        
        // Get all table rows
        let rows = document.querySelectorAll('table tbody tr');

        // Loop through each row and hide/show based on the search input
        rows.forEach(row => {
            let medicineName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (medicineName.includes(searchQuery)) {
                row.style.display = ''; // Show the row if the name matches
            } else {
                row.style.display = 'none'; // Hide the row if it doesn't match
            }
        });
    });


    function openReceiveModal(medicineId, medicineName) {
    document.getElementById('receiveMedicineId').value = medicineId;
    document.getElementById('receiveMedicineName').value = medicineName;

    // Dynamically set the form action
    const form = document.getElementById('receiveForm');
    form.action = `/medicines/${medicineId}/receive`;  // Adjust the route pattern as per your web.php

    document.getElementById('receiveModal').classList.remove('hidden');
}

    function closeReceiveModal() {
        document.getElementById('receiveModal').classList.add('hidden');
    }

    function openGiveModal(medicineId, medicineName) {
    document.getElementById('giveMedicineId').value = medicineId;
    document.getElementById('giveMedicineName').value = medicineName;

    // Dynamically set the form action
    const form = document.getElementById('giveForm');
    form.action = `/medicines/${medicineId}/give`;  // Adjust the route pattern as per your web.php

    document.getElementById('giveModal').classList.remove('hidden');
}


    function closeGiveModal() {
        document.getElementById('giveModal').classList.add('hidden');
    }

    

    function openEditModal(id, name, details, stock, expiration) {
        document.getElementById('editMedicineId').value = id;
        document.getElementById('editMedicineName').value = name;
        document.getElementById('editMedicineDetails').value = details;
        document.getElementById('editMedicineStock').value = stock;
        document.getElementById('editMedicineExpiration').value = expiration;

        document.getElementById('editForm').action = `/medicines/${id}`;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>



</body>
</html>

//things to do
tabs tabs 
