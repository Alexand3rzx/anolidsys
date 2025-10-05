<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory - Health Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
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
    <main class="flex-1 p-6">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Medicine Inventory</h2>
            <p class="text-gray-600">Manage the stock and details of medicines.</p>

            <!-- Current Purok Badge -->
            @if(Auth::check())
                <div class="inline-block mt-3 px-4 py-2 rounded-full 
                            bg-red-100 text-red-700 font-semibold shadow-sm">
                    @if(Auth::user()->usertype === 'admin')
                        Viewing: <span class="text-red-800">adminpurok</span>
                    @elseif(Auth::user()->usertype === 'useradmin')
                        Viewing: <span class="text-red-800">{{ Auth::user()->purok }}</span>
                    @endif
                </div>
            @endif
        </header>

        <!-- Medicine Inventory Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                    
                    <!-- Role-based Button -->
                    @if(Auth::check())
                        @if(Auth::user()->usertype === 'admin')
                            <div class="flex gap-3">
                                <a href="{{ route('medicines.create') }}" 
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    Add Medicine
                                </a>

                                <a href="{{ route('medicine-requests.admin') }}" 
                                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    Show Requests
                                </a>
                            </div>
                        @elseif(Auth::user()->usertype === 'useradmin')
                            <a href="{{ route('medicines.request') }}"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                Request Medicines
                            </a>
                        @endif
                    @endif

                    <!-- Search + Filters -->
                    <div class="flex flex-wrap gap-2 items-center">
                        <!-- Search Bar -->
                        <form method="GET" action="{{ route('medicines.index') }}" class="flex" id="searchForm">
                            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                                placeholder="Search medicines..." 
                                class="px-4 py-2 border rounded-l-lg w-64 bg-gray-100 focus:outline-none">
                            <button type="submit" 
                                class="px-4 py-2 bg-red-500 text-white rounded-r-lg hover:bg-red-600">
                                Search
                            </button>
                        </form>

                        <!-- Purok Filter (Admin only) -->
                        @if(Auth::check() && Auth::user()->usertype === 'admin')
                            <form method="GET" action="{{ route('medicines.index') }}">
                                <select name="purok" onchange="this.form.submit()"
                                    class="px-3 py-2 border rounded-lg bg-gray-50 text-gray-700">
                                    <option value="">All Puroks</option>
                                    @foreach($puroks as $purok)
                                        <option value="{{ $purok }}" {{ request('purok') == $purok ? 'selected' : '' }}>
                                            {{ ucfirst($purok) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif

                        <!-- Reset Button -->
                        @if(request('search') || request('expiration_filter') || request('stock_filter') || request('purok'))
                            <a href="{{ route('medicines.index') }}" 
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Alerts -->
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
                        @forelse($medicines as $medicine)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $medicine->name }}</td>
                            <td class="px-4 py-2">{{ $medicine->details }}</td>
                            <td class="px-4 py-2">
                                @if($medicine->stock <= 0)
                                    <span class="text-red-600 font-semibold">Out of Stock</span>
                                @elseif($medicine->stock <= 10)
                                    <span class="text-yellow-600 font-semibold">Low ({{ $medicine->stock }})</span>
                                @else
                                    <span class="text-green-600 font-semibold">{{ $medicine->stock }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($medicine->expiration)
                                    @php
                                        $expDate = \Carbon\Carbon::parse($medicine->expiration);
                                        $now = \Carbon\Carbon::now();
                                        $diffMonths = $now->diffInMonths($expDate, false);
                                    @endphp
                                    @if($expDate->isPast())
                                        <span class="text-red-600 font-semibold">Expired ({{ $expDate->format('M Y') }})</span>
                                    @elseif($diffMonths <= 1)
                                        <span class="text-yellow-600 font-semibold">Expiring Soon ({{ $expDate->format('M Y') }})</span>
                                    @else
                                        <span class="text-green-600">{{ $expDate->format('M Y') }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 flex flex-wrap gap-2">
                                @if(Auth::check() && Auth::user()->usertype === 'admin')
                                    <button type="button" onclick="openReceiveModal('{{ $medicine->id }}', '{{ $medicine->name }}')" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Receive</button>

                                    <button type="button" onclick="openEditModal('{{ $medicine->id }}','{{ $medicine->name }}','{{ $medicine->details }}','{{ $medicine->stock }}','{{ \Carbon\Carbon::parse($medicine->expiration)->format('Y-m-d') }}')" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Edit</button>

                                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                    </form>
                                @endif

                                <!-- Give button visible for all -->
                                <button type="button" onclick="openGiveModal('{{ $medicine->id }}', '{{ $medicine->name }}')" 
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Give</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">🚫 No medicines found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <div class="mt-4">
                    {{ $medicines->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

        <!-- Receive Modal -->
    <div id="receiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h3 class="text-xl font-bold mb-4">Receive Medicine</h3>
            <form id="receiveForm" method="POST">
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
                    <label class="block text-gray-700">Donated By:</label>
                    <input type="text" name="donor" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Received By:</label>
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
            <form id="giveForm" method="POST">
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
                    <label class="block text-gray-700">Administered By:</label>
                    <input type="text" name="administered_by" class="w-full p-2 border rounded bg-gray-200" value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Received By:</label>
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

    <!-- Request Medicine Modal -->
    <div id="requestMedicineModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl p-6">

            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Request Medicines</h2>
                <button onclick="closeRequestMedicineModal()" class="text-gray-400 hover:text-red-500">✕</button>
            </div>

            <!-- Search -->
            <form id="requestSearchForm" method="GET" action="{{ route('medicines.index') }}" class="mb-4 flex">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search available medicines..."
                    class="px-4 py-2 border rounded-l-lg w-72 bg-gray-100 focus:outline-none">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-r-lg hover:bg-red-600">
                    Search
                </button>
            </form>

            <!-- Medicines Table -->
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg">
                    <thead class="bg-red-500 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Medicine</th>
                            <th class="px-4 py-2 text-left">Details</th>
                            <th class="px-4 py-2 text-center">Stock</th>
                            <th class="px-4 py-2 text-center">Request Qty</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($adminMedicines as $medicine)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold">{{ $medicine->name }}</td>
                                <td class="px-4 py-3">{{ $medicine->details }}</td>
                                <td class="px-4 py-3 text-center">{{ $medicine->stock }}</td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" min="1" max="{{ $medicine->stock }}" 
                                        class="w-20 border rounded p-1 text-center"
                                        id="qty-{{ $medicine->id }}">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="submitRequest({{ $medicine->id }})"
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                        Request
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                    No medicines available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $medicines->links() }}
            </div>
        </div>
    </div>

    <script>

    document.getElementById('searchInput').addEventListener('input', function() {
        let searchQuery = this.value.toLowerCase();
        let rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            let medicineName = row.querySelector('td:first-child')?.textContent.toLowerCase() || "";
            let medicineDetails = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || "";
            if (medicineName.includes(searchQuery) || medicineDetails.includes(searchQuery)) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        });
    });


        function openReceiveModal(medicineId, medicineName) {
        document.getElementById('receiveMedicineId').value = medicineId;
        document.getElementById('receiveMedicineName').value = medicineName;

        const form = document.getElementById('receiveForm');
        form.action = `/medicines/${medicineId}/receive`;  

        document.getElementById('receiveModal').classList.remove('hidden');
    }

        function closeReceiveModal() {
            document.getElementById('receiveModal').classList.add('hidden');
        }

        function openGiveModal(medicineId, medicineName) {
        document.getElementById('giveMedicineId').value = medicineId;
        document.getElementById('giveMedicineName').value = medicineName;

        const form = document.getElementById('giveForm');
        form.action = `/medicines/${medicineId}/give`;  

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

        // Open modal
        function openRequestMedicineModal() {
            document.getElementById('requestMedicineModal').classList.remove('hidden');
        }

        // Close modal
        function closeRequestMedicineModal() {
            document.getElementById('requestMedicineModal').classList.add('hidden');
        }

        // Optional: Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('requestMedicineModal');
            if (e.target === modal) {
                closeRequestMedicineModal();
            }
        });

        function submitRequest(medicineId) {
        const qty = document.getElementById(`qty-${medicineId}`).value;
        if (!qty || qty <= 0) {
            alert("Please enter a valid quantity.");
            return;
        }

        fetch("{{ route('medicine-requests.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                medicine_id: medicineId,
                quantity: qty
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ Request submitted successfully!");
                location.reload();
            } else {
                alert("❌ " + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("⚠️ Something went wrong.");
        });
    }

    </script>



    </body>
    </html>
