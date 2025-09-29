<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficiaries - BAHMS</title>
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
            <a href="{{ route('home') }}" class="block py-2.5 px-4 hover:bg-red-600">Dashboard</a>
            <a href="{{ route('medicines.index') }}" class="block py-2.5 px-4 hover:bg-red-600">Medicine Inventory</a>
            <a href="{{ route('beneficiaries.index') }}" class="block py-2.5 px-4 bg-red-600">Beneficiaries</a>
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
    <main class="flex-1 p-8 overflow-y-auto bg-gray-50">
        <div class="max-w-6xl mx-auto space-y-10">

            <!-- Pregnant Women List -->
            <div class="bg-white rounded-xl shadow">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-red-600">Pregnant Women</h2>
                    <button onclick="openPregnantModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        + Add Pregnant
                    </button>
                </div>

                <div class="divide-y">
                    @forelse($pregnantWomen as $woman)
                        <div onclick="window.location='{{ route('pregnant.show', $woman->id) }}'"
                             class="p-5 flex justify-between items-center hover:bg-red-50 cursor-pointer">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">{{ $woman->prgname }}</p>
                                <p class="text-sm text-gray-500">Age: {{ $woman->prgage }} • {{ $woman->prgaddress }}</p>
                                <p class="text-sm text-gray-500">Occupation: {{ $woman->prgoccupation ?? '—' }}</p>
                            </div>
                            <span class="text-red-500">&rarr;</span>
                        </div>
                    @empty
                        <p class="p-5 text-center text-gray-500">No records found</p>
                    @endforelse
                </div>

                <div class="p-4">{{ $pregnantWomen->links() }}</div>
            </div>

            <!-- Infants List -->
            <div class="bg-white rounded-xl shadow">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-red-600">Infants</h2>
                    <button onclick="openInfantModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        + Add Infant
                    </button>
                </div>

                <div class="divide-y">
                    @forelse($infants as $infant)
                        <div onclick="window.location='{{ route('infant.show', $infant->id) }}'"
                             class="p-5 flex justify-between items-center hover:bg-red-50 cursor-pointer">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">{{ $infant->child_name }}</p>
                                <p class="text-sm text-gray-500">Gender: {{ $infant->child_gender }}</p>
                                <p class="text-sm text-gray-500">Mother: {{ $infant->child_mother }} • Father: {{ $infant->child_father }}</p>
                            </div>
                            <span class="text-red-500">&rarr;</span>
                        </div>
                    @empty
                        <p class="p-5 text-center text-gray-500">No records found</p>
                    @endforelse
                </div>

                <div class="p-4">{{ $infants->links() }}</div>
            </div>

        </div>
    </main>

    <!-- Add Pregnant Modal -->
    <div id="pregnantModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-bold text-red-600 mb-4">Add Pregnant Woman</h3>
            <form method="POST" action="{{ route('pregnant.store') }}" class="space-y-4">
                @csrf
                <input type="text" name="prgname" placeholder="Full Name" class="w-full border rounded px-3 py-2" required>
                <input type="number" name="prgage" placeholder="Age" class="w-full border rounded px-3 py-2" required>
                <input type="date" name="prgbday" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="prgaddress" placeholder="Address" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="prgoccupation" placeholder="Occupation" class="w-full border rounded px-3 py-2">
                <input type="text" name="prgreligion" placeholder="Religion" class="w-full border rounded px-3 py-2">
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Save</button>
                <button type="button" onclick="closePregnantModal()" class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add Infant Modal -->
    <div id="infantModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-bold text-red-600 mb-4">Add Infant</h3>
            <form method="POST" action="{{ route('infants.store') }}" class="space-y-4">
                @csrf
                <input type="text" name="child_name" placeholder="Child's Name" class="w-full border rounded px-3 py-2" required>
                <input type="date" name="child_bday" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="child_place" placeholder="Place of Birth" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="child_address" placeholder="Address" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="child_mother" placeholder="Mother's Name" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="child_father" placeholder="Father's Name" class="w-full border rounded px-3 py-2" required>
                <select name="child_gender" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="number" name="child_height" placeholder="Height (cm)" class="w-full border rounded px-3 py-2" required>
                <input type="number" name="child_weight" placeholder="Weight (kg)" class="w-full border rounded px-3 py-2" required>
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Save</button>
                <button type="button" onclick="closeInfantModal()" class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openPregnantModal() { document.getElementById('pregnantModal').classList.remove('hidden'); }
        function closePregnantModal() { document.getElementById('pregnantModal').classList.add('hidden'); }
        function openInfantModal() { document.getElementById('infantModal').classList.remove('hidden'); }
        function closeInfantModal() { document.getElementById('infantModal').classList.add('hidden'); }
    </script>

</body>
</html>
