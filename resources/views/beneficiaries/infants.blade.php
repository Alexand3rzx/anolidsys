<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infant Beneficiaries - BAHMS</title>
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

<!-- AlpineJS -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Main Content -->
<main class="flex-1 p-8 overflow-y-auto bg-gray-50">
    <div class="max-w-6xl mx-auto space-y-10">

        <!-- Infants List -->
        <div class="bg-white rounded-xl shadow">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-red-600">Infants</h2>
                <div class="flex space-x-3 items-center">
                    <!-- Search Input -->
                    <input type="text" id="searchInfant" placeholder="Search infants..." class="border rounded px-3 py-2 text-sm">

                    <!-- Purok Filter -->
                    <select id="filterPurok" class="border rounded px-3 py-2 text-sm">
                        <option value="">All Puroks</option>
                        <option value="purok1">Purok 1</option>
                        <option value="purok2">Purok 2</option>
                        <option value="purok3">Purok 3</option>
                        <option value="purok4">Purok 4</option>
                        <option value="purok5">Purok 5</option>
                        <option value="purok6">Purok 6</option>
                        <option value="purok7">Purok 7</option>
                    </select>

                    <!-- Import/Template Buttons -->
                    <form method="POST" action="{{ route('infants.import') }}" enctype="multipart/form-data" class="inline">
                        @csrf
                        <label class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 cursor-pointer">
                            Import CSV
                            <input type="file" name="csv_file" accept=".csv" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>

                    <a href="{{ route('infants.template') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Download Template
                    </a>

                    <button onclick="openInfantModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        + Add Infant
                    </button>
                </div>
            </div>

            <div id="infantList" class="divide-y">
                @forelse($infants as $infant)
                    <div onclick="window.location='{{ route('infant.show', $infant->id) }}'"
                         class="p-5 flex justify-between items-center hover:bg-red-50 cursor-pointer">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">{{ $infant->child_name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst(str_replace('purok', 'Purok ', $infant->purok)) }}</p>
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

            <!-- Purok Dropdown -->
            <select name="purok" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select Purok --</option>
                <option value="purok1">Purok 1</option>
                <option value="purok2">Purok 2</option>
                <option value="purok3">Purok 3</option>
                <option value="purok4">Purok 4</option>
                <option value="purok5">Purok 5</option>
                <option value="purok6">Purok 6</option>
                <option value="purok7">Purok 7</option>
            </select>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function openInfantModal() { document.getElementById('infantModal').classList.remove('hidden'); }
function closeInfantModal() { document.getElementById('infantModal').classList.add('hidden'); }

$(document).ready(function(){
    function filterInfants() {
        var query = $('#searchInfant').val();
        var purok = $('#filterPurok').val();

        $.ajax({
            url: "{{ route('beneficiaries.searchInfant') }}",
            type: "GET",
            data: { query: query, purok: purok },
            success: function(data){
                $('#infantList').html('');
                if (data.length > 0) {
                    $.each(data, function(index, inf){
                        $('#infantList').append(
                            '<div onclick="window.location=\'/infants/'+inf.id+'\'" class="p-5 flex justify-between items-center hover:bg-red-50 cursor-pointer">'+
                                '<div>'+
                                    '<p class="text-lg font-semibold text-gray-800">'+inf.child_name+'</p>'+
                                    '<p class="text-sm text-gray-500">'+inf.purok.replace("purok", "Purok ")+'</p>'+
                                    '<p class="text-sm text-gray-500">Gender: '+inf.child_gender+'</p>'+
                                    '<p class="text-sm text-gray-500">Mother: '+inf.child_mother+' • Father: '+inf.child_father+'</p>'+
                                '</div>'+
                                '<span class="text-red-500">&rarr;</span>'+
                            '</div>'
                        );
                    });
                } else {
                    $('#infantList').html('<p class="p-5 text-center text-gray-500">No results found</p>');
                }
            }
        });
    }

    $('#searchInfant').on('keyup', filterInfants);
    $('#filterPurok').on('change', filterInfants);
});
</script>

</body>
</html>
