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
<aside class="bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white w-64 flex flex-col">
    <div class="p-6">
        <h1 class="text-2xl font-bold">Health Management System</h1>
        <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
    </div>
    <nav class="flex-grow">
        <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-red-600">Dashboard</a>
        <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>

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

<main class="flex-1 p-6">
    <header class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Request Medicines</h2>
        <p class="text-gray-600">Browse available medicines from Admin’s inventory and send requests.</p>
    </header>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded shadow">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded shadow">⚠️ {{ session('error') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
           <div class="flex justify-between mb-4">
    <a href="{{ route('medicines.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">← Back</a>
    <div class="flex items-center space-x-2">
        <select id="batchFilter" class="border rounded px-3 py-2 bg-gray-100">
            <option value="">All Batches</option>
            @foreach($adminMedicines->unique('batch_number') as $item)
                <option value="{{ $item->batch_number }}">{{ $item->batch_number }}</option>
            @endforeach
        </select>
        <input type="text" id="medicineSearch" placeholder="🔍 Live search..." class="px-4 py-2 border rounded w-64 bg-gray-100 focus:outline-none">
    </div>
</div>

<table class="min-w-full bg-white border border-gray-200" id="medicineTable">
    <thead>
        <tr class="text-left bg-gray-100">
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Details</th>
            <th class="px-4 py-2">Batch</th>
            <th class="px-4 py-2">Expiration</th>
            <th class="px-4 py-2 text-center">Stock</th>
            <th class="px-4 py-2 text-center">Qty</th>
            <th class="px-4 py-2 text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($adminMedicines as $medicine)
        <tr class="border-b" data-batch="{{ $medicine->batch_number }}">
            <td class="px-4 py-2">{{ $medicine->name }}</td>
            <td class="px-4 py-2">{{ $medicine->details }}</td>
            <td class="px-4 py-2">{{ $medicine->batch_number ?? '—' }}</td>
            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($medicine->expiration)->format('M d, Y') }}</td>
            <td class="px-4 py-2 text-center">{{ $medicine->stock }}</td>
            <td class="px-4 py-2 text-center">
                <form action="{{ route('medicine-requests.store') }}" method="POST" class="requestForm">
                    @csrf
                    <input type="hidden" name="medicine_id" value="{{ $medicine->medicine_id }}">
                    <input type="hidden" name="batch_id" value="{{ $medicine->batch_id }}">
                    <input type="number" name="quantity" min="1" max="{{ $medicine->stock }}" class="w-20 border rounded p-1 text-center" required>
            </td>
            <td class="px-4 py-2 text-center">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Request</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-6 text-gray-500">🚫 No medicines available.</td></tr>
        @endforelse
    </tbody>
</table>
            <!-- My Requests Section -->
<div class="mt-10 bg-white shadow-sm rounded-lg">
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">📦 My Medicine Requests</h3>

        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Medicine</th>
                    <th class="px-4 py-2 text-center">Quantity</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Pickup Code</th>
                    <th class="px-4 py-2 text-center">Pickup Date</th>
                    <th class="px-4 py-2 text-center">Requested On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myRequests as $req)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $req->medicine->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-center">{{ $req->quantity }}</td>
                    <td class="px-4 py-2 text-center">
                        @if($req->status === 'pending')
                            <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded">Pending</span>
                        @elseif($req->status === 'approved')
                            <span class="bg-blue-200 text-blue-800 px-3 py-1 rounded">Approved</span>
                        @elseif($req->status === 'completed')
                            <span class="bg-green-200 text-green-800 px-3 py-1 rounded">Completed</span>
                        @elseif($req->status === 'rejected')
                            <span class="bg-red-200 text-red-800 px-3 py-1 rounded">Rejected</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $req->pickup_code ?? '—' }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $req->pickup_date ? \Carbon\Carbon::parse($req->pickup_date)->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $req->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        🕓 No requests made yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
        </div>
    </div>
</main>
</div>



<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("medicineSearch");
    const batchFilter = document.getElementById("batchFilter");
    const rows = document.querySelectorAll("#medicineTable tbody tr");

    function filterTable() {
        const term = searchInput.value.toLowerCase();
        const batch = batchFilter.value;
        rows.forEach(row => {
            const matchText = row.innerText.toLowerCase().includes(term);
            const matchBatch = !batch || row.dataset.batch === batch;
            row.style.display = matchText && matchBatch ? "" : "none";
        });
    }

    searchInput.addEventListener("keyup", filterTable);
    batchFilter.addEventListener("change", filterTable);

    document.querySelectorAll(".requestForm").forEach(form => {
        form.addEventListener("submit", e => {
            if (!confirm("Are you sure you want to request this medicine batch?")) e.preventDefault();
        });
    });
});


</script>

</body>
</html>
