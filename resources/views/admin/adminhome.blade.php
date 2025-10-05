<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Management System - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: black;
        }
        /* 👇 Make textboxes slightly gray */
        input[type="text"], input[type="email"], input[type="password"], select, textarea {
            background-color: #f3f4f6; /* Tailwind gray-100 */
            border: 1px solid #d1d5db; /* Tailwind gray-300 */
            padding: 0.5rem;
            border-radius: 0.375rem; /* rounded-md */
            width: 100%;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #ef4444; /* red-500 */
            box-shadow: 0 0 0 2px #fecaca; /* red-200 */
        }
    </style>
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
        <header class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-600">Welcome to the Health Management System</p>
    </div>

   <!-- ✅ Notification Bell (Admins Only) -->
@if(Auth::check() && Auth::user()->usertype === 'admin')
<div class="relative">
    <button class="relative focus:outline-none" id="notifDropdown" onclick="toggleDropdown()">
        <span class="sr-only">View notifications</span>
        <!-- Bell Icon -->
        <svg class="w-7 h-7 text-gray-700 hover:text-red-600 transition" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a2 2 0 002-2H8a2 2 0 002 2z"/>
        </svg>

        <!-- 🔴 Badge -->
        @if($notifications->where('is_read', false)->count() > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 shadow">
                {{ $notifications->where('is_read', false)->count() }}
            </span>
        @endif
    </button>

    <!-- 🔽 Dropdown -->
    <div id="notifMenu" 
         class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl max-h-96 overflow-y-auto z-50">
        <div class="px-4 py-2 font-semibold text-gray-700 border-b bg-gray-50">
            Notifications
        </div>
        @forelse($notifications as $notif)
            <a href="{{ url('medicine-requests/admin') }}" 
               class="block px-4 py-3 text-sm border-b last:border-0 
                      {{ $notif->is_read ? 'text-gray-600' : 'font-semibold text-gray-800' }} 
                      hover:bg-red-50 transition">
                <div>{{ $notif->message }}</div>
                <small class="text-gray-500">{{ $notif->created_at->diffForHumans() }}</small>
            </a>
        @empty
            <div class="px-4 py-4 text-center text-gray-500 text-sm">
                🎉 No new notifications
            </div>
        @endforelse
    </div>
</div>
@endif
</header>

       <!-- Medicine Inventory Section -->
<section class="bg-white shadow rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            💊 Medicine Inventory
        </h3>

        <!-- ✅ Purok Filter (Admins Only) -->
        @if(Auth::check() && Auth::user()->usertype === 'admin')
            <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2 mt-4 md:mt-0">
                <label for="purok" class="text-gray-700 font-medium">Filter by Purok:</label>
                <select name="purok" id="purok" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">-- All Puroks --</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok }}" {{ $selectedPurok == $purok ? 'selected' : '' }}>
                            {{ ucfirst($purok) }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>

   @php
    use Carbon\Carbon;

    $totalMedicines = $medicines->count();
    $lowStockCount = $medicines->where('stock', '<', 20)->count();
    $outOfStockCount = $medicines->where('stock', '<=', 0)->count();

    // ✅ Medicines expiring within 30 days (but not expired yet)
    $expiringSoonCount = $medicines->filter(function($med) {
        return $med->expiration 
            && \Carbon\Carbon::parse($medicines->expiration)->isBetween(
                \Carbon\Carbon::now(),
                \Carbon\Carbon::now()->addDays(30)
            );
    })->count();
@endphp

    <!-- ✅ Medicine Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="p-5 bg-gradient-to-r from-red-100 to-red-200 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-700">Total Medicines</p>
            <h2 class="text-3xl font-extrabold text-red-700">{{ $totalMedicines }}</h2>
        </div>
        <div class="p-5 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-700">Low Stock (&lt; 20)</p>
            <h2 class="text-3xl font-extrabold text-yellow-700">{{ $lowStockCount }}</h2>
        </div>
        <div class="p-5 bg-gradient-to-r from-orange-100 to-orange-200 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-700">Expiring Soon</p>
            <h2 class="text-3xl font-extrabold text-orange-700">{{ $expiringSoonCount }}</h2>
        </div>
        <div class="p-5 bg-gradient-to-r from-green-100 to-green-200 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-700">Out of Stock</p>
            <h2 class="text-3xl font-extrabold text-green-700">{{ $outOfStockCount }}</h2>
        </div>
    </div>

    <!-- ✅ Medicine Table -->
<div class="overflow-x-auto">
    <table class="w-full border border-gray-200 text-sm rounded-lg overflow-hidden shadow">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3 border">Medicine</th>
                <th class="text-left px-4 py-3 border">Purok</th> <!-- 👈 Added column -->
                <th class="text-left px-4 py-3 border">Stock Left</th>
                <th class="text-left px-4 py-3 border">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($medicines as $medicine)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 border font-medium text-gray-800">{{ $medicine->name }}</td>
                    <td class="px-4 py-3 border">{{ ucfirst($medicine->purok) }}</td> <!-- 👈 Show purok -->
                    <td class="px-4 py-3 border">{{ $medicine->stock }}</td>
                    <td class="px-4 py-3 border">
                        @if ($medicine->stock <= 0)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Out of Stock</span>
                        @elseif ($medicine->stock < 20)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Low Stock</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Available</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</section>

    </main>
</body>

<script>
function toggleDropdown() {
    document.getElementById('notifMenu').classList.toggle('hidden');
}
</script>
</html>
