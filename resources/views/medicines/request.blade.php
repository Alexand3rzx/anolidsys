<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Medicines - Health Management System</title>
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
    <main class="flex-1 p-6">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Request Medicines</h2>
            <p class="text-gray-600">Browse available medicines from Admin’s inventory and send requests.</p>

            <!-- Current Purok Badge -->
            @if(Auth::check())
                <div class="inline-block mt-3 px-4 py-2 rounded-full 
                            bg-red-100 text-red-700 font-semibold shadow-sm">
                    @if(Auth::user()->usertype === 'admin')
                        Viewing as <span class="text-red-800 font-bold">Main Admin</span>
                    @elseif(Auth::user()->usertype === 'useradmin')
                        Viewing: <span class="text-red-800 font-bold">Purok {{ Auth::user()->purok }}</span>
                    @else
                        Viewing as <span class="text-red-800 font-bold">User</span>
                    @endif
                </div>
            @endif
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded shadow">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- Medicines Request Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-4">

                    <!-- Back Button -->
                    <a href="{{ route('medicines.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                       ← Back to Inventory
                    </a>

                    <!-- Live Search Bar -->
                    <div class="flex">
                        <input type="text" id="medicineSearch" 
                            placeholder="🔍 Live search medicines..." 
                            class="px-4 py-2 border rounded w-64 bg-gray-100 focus:outline-none">
                    </div>
                </div>

                <table class="min-w-full bg-white border border-gray-200" id="medicineTable">
                    <thead>
                        <tr class="text-left bg-gray-100">
                            <th class="px-4 py-2">Medicine Name</th>
                            <th class="px-4 py-2">Details</th>
                            <th class="px-4 py-2 text-center">Stock</th>
                            <th class="px-4 py-2 text-center">Request Qty</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adminMedicines as $medicine)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $medicine->name }}</td>
                            <td class="px-4 py-2">{{ $medicine->details }}</td>
                            <td class="px-4 py-2 text-center">{{ $medicine->stock }}</td>
                            <td class="px-4 py-2 text-center">
                                <form action="{{ route('medicine-requests.store') }}" method="POST" class="requestForm">
                                    @csrf
                                    <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                                    <input type="number" name="quantity" min="1" max="{{ $medicine->stock }}"
                                           class="w-20 border rounded p-1 text-center" required>
                            </td>
                            <td class="px-4 py-2 text-center">
                                    <button type="submit"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                        Request
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">🚫 No medicines available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // live search
    const searchInput = document.getElementById("medicineSearch");
    const rows = document.querySelectorAll("#medicineTable tbody tr");

    searchInput.addEventListener("keyup", function () {
        const term = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? "" : "none";
        });
    });

    // confirm request submission
    const requestForms = document.querySelectorAll(".requestForm");
    requestForms.forEach(form => {
        form.addEventListener("submit", function (e) {
            const confirmAction = confirm("Are you sure you want to request this medicine?");
            if (!confirmAction) {
                e.preventDefault();
            }
        });
    });
});
</script>

</body>
</html>
