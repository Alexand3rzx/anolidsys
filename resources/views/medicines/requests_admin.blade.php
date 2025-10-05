<!-- resources/views/medicines/requests_admin.blade.php -->
<!DOCTYPE html>
<html lang="en" x-data="{ openModal: false, actionUrl: '', actionText: '' }" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Requests - Health Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
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
            <h2 class="text-3xl font-bold text-gray-800">Medicine Requests</h2>
            <p class="text-gray-600">Review and manage all medicine requests.</p>

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

        <!-- Requests Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                <!-- Search -->
                <form method="GET" action="{{ route('medicine.requests.admin') }}" class="flex mb-4">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search requests..."
                           class="px-4 py-2 border rounded-l w-64 bg-gray-100 focus:outline-none">
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 text-white rounded-r hover:bg-red-600">
                        Search
                    </button>
                </form>

                <!-- Success / Error Messages -->
                @if(session('success'))
                    <div class="mb-4 text-green-500">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="mb-4 text-red-500">{{ session('error') }}</div>
                @endif

                <!-- Table -->
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="text-left bg-gray-100">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Medicine</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Requested By</th>
                            <th class="px-4 py-2">Purok</th>
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
                            <td class="px-4 py-2">
                                @if($req->status === 'pending')
                                    <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-700">Pending</span>
                                @elseif($req->status === 'approved')
                                    <span class="px-2 py-1 text-sm rounded bg-green-100 text-green-700">Approved</span>
                                @else
                                    <span class="px-2 py-1 text-sm rounded bg-red-100 text-red-700">Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                @if($req->status === 'pending')
                                    <button type="button"
                                            @click="openModal = true; actionUrl = '{{ route('medicine-requests.approve', $req->id) }}'; actionText = 'Approve';"
                                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                                        Approve
                                    </button>
                                    <button type="button"
                                            @click="openModal = true; actionUrl = '{{ route('medicine-requests.reject', $req->id) }}'; actionText = 'Reject';"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                                        Reject
                                    </button>
                                @else
                                    <span class="text-gray-500 text-sm">No actions</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">🚫 No medicine requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Confirmation Modal -->
<div x-show="openModal"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
     x-transition>
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-lg font-bold text-gray-800">Confirm Action</h3>
        <p class="mt-2 text-gray-600">Are you sure you want to <span x-text="actionText"></span> this request?</p>
        <div class="mt-4 flex justify-end space-x-2">
            <button type="button" @click="openModal = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <form method="POST" :action="actionUrl">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        x-text="actionText"></button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
