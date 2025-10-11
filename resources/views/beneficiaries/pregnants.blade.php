<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnant Beneficiaries - BAHMS</title>
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

        <!-- Pregnant Women List -->
<div class="bg-white rounded-xl shadow">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-xl font-bold text-red-600">Pregnant Women</h2>
        <div class="flex space-x-3">
            <input type="text" id="searchPregnant" placeholder="Search pregnant women..." class="border rounded px-3 py-2 text-sm">
            <button onclick="openPregnantModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                + Add Pregnant
            </button>
        </div>
    </div>

    <div id="pregnantList" class="divide-y">
        @forelse($pregnantWomen as $woman)
            <div onclick="window.location='{{ route('pregnant.show', $woman->id) }}'"
                 class="p-5 flex justify-between items-center hover:bg-red-50 cursor-pointer">
                <div>
                    <p class="text-lg font-semibold text-gray-800">{{ $woman->prgname }}</p>
                    <p class="text-sm text-gray-500">
                        {{ ucfirst(str_replace('purok', 'Purok ', $woman->purok)) }}
                    </p>
                    <p class="text-sm text-gray-500">Age: {{ $woman->prgage }}</p>
                    <p class="text-sm text-gray-500">Address: {{ $woman->prgaddress }}</p>
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
</main>

<!-- Add Pregnant Modal -->
<div id="pregnantModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h3 class="text-lg font-bold text-red-600 mb-4">Add Pregnant Woman</h3>
        <form method="POST" action="{{ route('pregnant.store') }}" class="space-y-4">
            @csrf
            <input type="text" name="prgname" placeholder="Full Name" class="w-full border rounded px-3 py-2" required>
            
            <input type="date" name="prgbday" id="prgbday" class="w-full border rounded px-3 py-2" required>
            <input type="number" name="prgage" id="prgage" placeholder="Age" class="w-full border rounded px-3 py-2" readonly>
            
            <input type="text" name="prgaddress" placeholder="Address" class="w-full border rounded px-3 py-2" required>
            
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
            <input type="text" name="prgoccupation" placeholder="Occupation" class="w-full border rounded px-3 py-2">
            <input type="text" name="prgreligion" placeholder="Religion" class="w-full border rounded px-3 py-2">

            <!-- 👇 NEW FIELD: Number of Times Pregnant -->
            <input type="number" name="prgtimes" placeholder="Number of times pregnant" class="w-full border rounded px-3 py-2" min="1" required>

            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Save</button>
            <button type="button" onclick="closePregnantModal()" class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function openPregnantModal() { document.getElementById('pregnantModal').classList.remove('hidden'); }
function closePregnantModal() { document.getElementById('pregnantModal').classList.add('hidden'); }

// Auto calculate age when birthdate is chosen
document.getElementById("prgbday").addEventListener("change", function() {
    let birthDate = new Date(this.value);
    let today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    let monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    document.getElementById("prgage").value = age > 0 ? age : 0;
});

$(document).ready(function(){
    $('#searchPregnant').on('keyup', function() {
        var query = $(this).val();
        $.ajax({
            url: "{{ route('beneficiaries.searchPregnant') }}",
            type: "GET",
            data: {'query': query},
            success:function(data){
                $('#pregnantList').html('');
                if(data.length > 0){
                    $.each(data, function(index, woman){
                        $('#pregnantList').append(
                            '<div class="p-5 flex justify-between items-center hover:bg-red-50 cursor-pointer">'+
                                '<div>'+
                                    '<p class="text-lg font-semibold text-gray-800">'+woman.prgname+'</p>'+
                                    '<p class="text-sm text-gray-500">Age: '+woman.prgage+' • Purok: '+(woman.purok ?? "—")+'</p>'+
                                    '<p class="text-sm text-gray-500">Address: '+woman.prgaddress+'</p>'+
                                    '<p class="text-sm text-gray-500">Occupation: '+(woman.prgoccupation ?? "—")+'</p>'+
                                '</div>'+
                                '<span class="text-red-500">&rarr;</span>'+
                            '</div>'
                        );
                    });
                } else {
                    $('#pregnantList').html('<p class="p-5 text-center text-gray-500">No results found</p>');
                }
            }
        });
    });
});
</script>

</body>
</html>
