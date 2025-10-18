<!DOCTYPE html>
<html lang="en" x-data="{ openModal: false, actionText: '', currentRequestId: null }" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Requests - Health Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
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

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Medicine Requests</h2>
            <p class="text-gray-600">Review and manage all medicine requests.</p>

            <!-- Purok Indicator -->
            @if(Auth::check())
                <div class="inline-block mt-3 px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold shadow-sm">
                    Viewing: <span class="text-red-800">
                        {{ Auth::user()->usertype === 'admin' ? 'adminpurok' : Auth::user()->purok }}
                    </span>
                </div>
            @endif
        </header>

        <!-- Search + Messages -->
        <form method="GET" action="{{ route('medicine.requests.admin') }}" class="flex mb-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search requests..."
                   class="px-4 py-2 border rounded-l w-64 bg-gray-100 focus:outline-none">
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-r hover:bg-red-600">
                Search
            </button>
        </form>

        @if(session('success'))
            <div class="mb-4 text-green-500">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 text-red-500">{{ session('error') }}</div>
        @endif

        <!-- Requests Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
    <tr class="text-left bg-gray-100">
        <th class="px-4 py-2">#</th>
        <th class="px-4 py-2">Medicine</th>
        <th class="px-4 py-2">Quantity</th>
        <th class="px-4 py-2">Requested By</th>
        <th class="px-4 py-2">Purok</th>
        <th class="px-4 py-2">Pickup Code</th>
        <th class="px-4 py-2">Pickup Date</th>
        <th class="px-4 py-2">Status</th>
        <th class="px-4 py-2">Actions</th>
    </tr>
</thead>
<tbody>
    @forelse($requests as $req)
        <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ $req->id }}</td>
            <td class="px-4 py-2">{{ $req->medicine->name }}</td>
            <td class="px-4 py-2">{{ $req->quantity }}</td>
            <td class="px-4 py-2">{{ $req->useradmin->name ?? 'Unknown' }}</td>
            <td class="px-4 py-2">{{ $req->useradmin->purok ?? 'N/A' }}</td>

            <!-- NEW: Pickup Code -->
            <td class="px-4 py-2 font-mono text-sm text-gray-700">
                {{ $req->pickup_code ?? '—' }}
            </td>

            <!-- NEW: Pickup Date -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ $req->pickup_date ? \Carbon\Carbon::parse($req->pickup_date)->format('M d, Y') : '—' }}
            </td>

            <td class="px-4 py-2">
                @if($req->status === 'pending')
                    <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-700">Pending</span>
                @elseif($req->status === 'approved')
                    <span class="px-2 py-1 text-sm rounded bg-green-100 text-green-700">Approved</span>
                @elseif($req->status === 'completed')
                    <span class="px-2 py-1 text-sm rounded bg-blue-100 text-blue-700">Completed</span>
                @else
                    <span class="px-2 py-1 text-sm rounded bg-red-100 text-red-700">Rejected</span>
                @endif
            </td>

            <td class="px-4 py-2 space-x-2">
                @if($req->status === 'pending')
                    <button @click="openModal = true; actionText = 'Approve'; currentRequestId = {{ $req->id }};"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Approve</button>
                    <button @click="openModal = true; actionText = 'Reject'; currentRequestId = {{ $req->id }};"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Reject</button>
                @elseif($req->status === 'approved')
                    <form method="POST" action="{{ route('medicine-requests.confirmPickup') }}" class="inline">
                        @csrf
                        <input type="hidden" name="pickup_code" value="{{ $req->pickup_code }}">
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            Confirm Pickup
                        </button>
                    </form>
                @else
                    <span class="text-gray-500 text-sm">No actions</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center py-6 text-gray-500">🚫 No medicine requests found.</td>
        </tr>
    @endforelse
</tbody>
                </table>
                <div class="mt-4">{{ $requests->links() }}</div>
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div x-show="openModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition>
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-lg font-bold text-gray-800">Confirm <span x-text="actionText"></span></h3>
        
        <!-- Dynamic form based on action -->
        <template x-if="actionText === 'Approve'">
            <form method="POST" x-bind:action="`/medicine-requests/${currentRequestId}/approve`" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="pickup_date" class="block text-gray-700">Pickup Date</label>
                    <input type="date" name="pickup_date" id="pickup_date" class="border rounded w-full px-3 py-2 mt-1" 
                           min="{{ date('Y-m-d') }}" required>
                </div>
                <p class="mt-2 text-gray-600">Are you sure you want to approve this request?</p>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Approve</button>
                </div>
            </form>
        </template>

        <template x-if="actionText === 'Reject'">
            <form method="POST" x-bind:action="`/medicine-requests/${currentRequestId}/reject`" class="mt-4">
                @csrf
                <p class="mt-2 text-gray-600">Are you sure you want to reject this request?</p>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject</button>
                </div>
            </form>
        </template>
    </div>
</div>
</body>
</html>