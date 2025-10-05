<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pregnant Woman Record</title>
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
    <main class="flex-grow p-8">
        <h2 class="text-3xl font-bold mb-6">Edit Pregnant Woman Record</h2>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('pregnant.update', $woman->id) }}" method="POST" class="grid grid-cols-2 gap-6">
                @csrf
                @method('PUT')

                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Full Name</label>
                        <input type="text" name="prgname" value="{{ old('prgname', $woman->prgname) }}" 
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Age</label>
                        <input type="number" id="prgage" name="prgage" value="{{ old('prgage', $woman->prgage) }}" 
                               class="w-full border rounded px-3 py-2" required readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Date of Birth</label>
                        <input type="date" id="prgbday" name="prgbday" value="{{ old('prgbday', $woman->prgbday) }}" 
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Address</label>
                        <input type="text" name="prgaddress" value="{{ old('prgaddress', $woman->prgaddress) }}" 
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <!-- NEW: Purok Dropdown -->
                    <div>
                        <label class="block text-sm font-medium">Purok</label>
                        <select name="purok" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Select Purok --</option>
                            <option value="purok1" {{ old('purok', $woman->purok) == 'purok1' ? 'selected' : '' }}>Purok 1</option>
                            <option value="purok2" {{ old('purok', $woman->purok) == 'purok2' ? 'selected' : '' }}>Purok 2</option>
                            <option value="purok3" {{ old('purok', $woman->purok) == 'purok3' ? 'selected' : '' }}>Purok 3</option>
                            <option value="purok4" {{ old('purok', $woman->purok) == 'purok4' ? 'selected' : '' }}>Purok 4</option>
                            <option value="purok5" {{ old('purok', $woman->purok) == 'purok5' ? 'selected' : '' }}>Purok 5</option>
                            <option value="purok6" {{ old('purok', $woman->purok) == 'purok6' ? 'selected' : '' }}>Purok 6</option>
                            <option value="purok7" {{ old('purok', $woman->purok) == 'purok7' ? 'selected' : '' }}>Purok 7</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Occupation</label>
                        <input type="text" name="prgoccupation" value="{{ old('prgoccupation', $woman->prgoccupation) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Religion</label>
                        <input type="text" name="prgreligion" value="{{ old('prgreligion', $woman->prgreligion) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Mother’s Name</label>
                        <input type="text" name="prgmother_name" value="{{ old('prgmother_name', $woman->prgmother_name) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Partner’s Name</label>
                        <input type="text" name="partner_name" value="{{ old('partner_name', $woman->partner_name) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Age</label>
                        <input type="number" name="partner_age" value="{{ old('partner_age', $woman->partner_age) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Date of Birth</label>
                        <input type="date" name="partner_bday" value="{{ old('partner_bday', $woman->partner_bday) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Occupation</label>
                        <input type="text" name="partner_occupation" value="{{ old('partner_occupation', $woman->partner_occupation) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Religion</label>
                        <input type="text" name="partner_religion" value="{{ old('partner_religion', $woman->partner_religion) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Contact Number</label>
                        <input type="text" name="partner_number" value="{{ old('partner_number', $woman->partner_number) }}" 
                               class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-span-2 flex justify-between items-center mt-6">
                    <a href="{{ route('beneficiaries.pregnants') }}" 
                       class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Back
                    </a>
                    <div class="space-x-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Auto Age Calculation -->
<script>
    document.getElementById('prgbday').addEventListener('change', function () {
        let dob = new Date(this.value);
        let today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        let m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        document.getElementById('prgage').value = age >= 0 ? age : '';
    });
</script>

</body>
</html>
