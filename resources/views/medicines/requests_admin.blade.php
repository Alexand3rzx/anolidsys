<!-- resources/views/medicines/requests_admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Requests - Health Management System</title>
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
            <th class="px-4 py-2">Purok</th> <!-- Added -->
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
            <td class="px-4 py-2">{{ $req->useradmin->purok ?? 'N/A' }}</td> <!-- Added -->
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
                    <form action="{{ route('medicine-requests.approve', $req->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('medicine-requests.reject', $req->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                            Reject
                        </button>
                    </form>
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

</body>
</html>
