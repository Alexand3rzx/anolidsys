<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pregnant Woman Record</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        /* Custom Scrollbar for Main Content */
        main::-webkit-scrollbar {
            width: 8px;
        }
        main::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }
        main::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white w-64 flex flex-col h-full">
        <div class="p-6">
            <h1 class="text-2xl font-bold">Health Management System</h1>
            <p class="text-sm">Brgy. Anolid Mangaldan, Pangasinan</p>
        </div>
        <nav class="flex-grow overflow-y-auto">
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

    <!-- Main Content -->
    <main class="flex-grow p-8 overflow-y-auto">
        <h2 class="text-3xl font-bold mb-6">Edit Pregnant Woman Record</h2>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form action="{{ route('pregnant.update', $woman->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-6">

                @csrf
                @method('PUT')

                <!-- Left Column -->
                <div class="space-y-4">
<div class="mb-6">
  <label for="photo" class="block text-sm font-semibold text-gray-800 mb-2">
    Profile Photo
  </label>

  <div class="flex items-center gap-6">
    <!-- Photo Preview -->
    <div class="relative">
      <img
        id="photoPreview"
        src="{{ $woman->photo ? asset('storage/pregnants/' . $woman->photo) : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}"
        alt="Pregnant photo"
        class="w-32 h-32 object-cover rounded-full border shadow-sm ring-2 ring-gray-200 transition-transform duration-200 hover:scale-105"
      >
      <div
        class="absolute inset-0 rounded-full bg-black/40 opacity-0 hover:opacity-100 flex items-center justify-center transition-opacity duration-200 cursor-pointer"
        onclick="document.getElementById('photo').click()"
      >
        <span class="text-white text-xs font-medium">Change</span>
      </div>
    </div>

    <!-- Upload Input -->
    <div class="flex-1">
      <input
        type="file"
        name="photo"
        id="photo"
        accept="image/*"
        class="hidden"
        onchange="previewPhoto(event)"
      >
      <button
        type="button"
        onclick="document.getElementById('photo').click()"
        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
      >
        Upload New Photo
      </button>

      <p class="text-gray-500 text-xs mt-2">Accepted formats: JPG, PNG, GIF (max 2MB)</p>
    </div>
  </div>
</div>
                    <div>
                        <label class="block text-sm font-medium">Full Name</label>
                        <input type="text" name="prgname" value="{{ old('prgname', $woman->prgname) }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Age</label>
                        <input type="number" id="prgage" name="prgage" value="{{ old('prgage', $woman->prgage) }}" class="w-full border rounded px-3 py-2" required readonly>
                    </div>


                    <div>
                        <label class="block text-sm font-medium">Date of Birth</label>
                        <input type="date" id="prgbday" name="prgbday" value="{{ old('prgbday', $woman->prgbday) }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Address</label>
                        <input type="text" name="prgaddress" value="{{ old('prgaddress', $woman->prgaddress) }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Purok</label>
                        <select name="purok" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Select Purok --</option>
                            @for ($i = 1; $i <= 7; $i++)
                                <option value="purok{{ $i }}" {{ old('purok', $woman->purok) == 'purok'.$i ? 'selected' : '' }}>Purok {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Occupation</label>
                        <input type="text" name="prgoccupation" value="{{ old('prgoccupation', $woman->prgoccupation) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Religion</label>
                        <input type="text" name="prgreligion" value="{{ old('prgreligion', $woman->prgreligion) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Mother’s Name</label>
                        <input type="text" name="prgmother_name" value="{{ old('prgmother_name', $woman->prgmother_name) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Number of Times Pregnant</label>
                        <input type="number" min="1" name="prgtimes" value="{{ old('prgtimes', $woman->prgtimes) }}" class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Partner’s Name</label>
                        <input type="text" name="partner_name" value="{{ old('partner_name', $woman->partner_name) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Age</label>
                        <input type="number" name="partner_age" value="{{ old('partner_age', $woman->partner_age) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Date of Birth</label>
                        <input type="date" name="partner_bday" value="{{ old('partner_bday', $woman->partner_bday) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Occupation</label>
                        <input type="text" name="partner_occupation" value="{{ old('partner_occupation', $woman->partner_occupation) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Religion</label>
                        <input type="text" name="partner_religion" value="{{ old('partner_religion', $woman->partner_religion) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Partner’s Contact Number</label>
                        <input type="text" name="partner_number" value="{{ old('partner_number', $woman->partner_number) }}" class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-span-2 flex justify-between items-center mt-6">
                    <a href="{{ route('beneficiaries.pregnants') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Back
                    </a>
                    <div class="space-x-2">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Immunization Record Section -->
        <h3 class="text-2xl font-bold mb-6">Pregnancy Immunization Record</h3>

        @php
            $pregnancyTimes = $woman->prgtimes;
        @endphp

        @if ($pregnancyTimes == 1)
            @php
                $records = $woman->firstPregnancyRecords ?? collect();
                $checkups = [
                    'First Trimester Check-up',
                    '2nd Trimester Check-up (Tetanus Diphtheria, Ferrous Sulfate + Folic Acid Supplement Distribution)',
                    '2nd Trimester Check-up & Tetanus Diphteria Vaccination',
                    ' Check-up',
                    ' Check-up',
                ];
            @endphp
        @elseif ($pregnancyTimes >= 2 && $pregnancyTimes <= 5)
            @php
                $records = $woman->secondToFifthPregnancyRecords ?? collect();
                $checkups = [
                    'First Trimester Check-up',
                    '2nd Trimester Check-Up (Tetanus Diphtheria, Ferrous Sulfate + Folic Acid Suppliment Distribution)',
                    '2nd Trimester Check-up ',
                    'Check-up',
                    'Check-up',
                ];
            @endphp
        @else
            @php
                $records = $woman->sixthPregnancyRecords ?? collect();
                $checkups = [
                    'First Trimester Check-up',
                    '2nd Trimester Check-up (Ferrous Sulfate + Folic Acid Suppliment Distribution)',
                    '2nd Trimester Check-up',
                    'Check-up',
                    'Check-up',
                ];
            @endphp
        @endif

        <table class="w-full border-collapse bg-white shadow-lg mb-6">
            <div class="flex justify-end mb-4">
    <button 
        type="button" 
        id="clearDatesBtn"
        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
        Clear All Dates
    </button>
</div>
            <thead>
                <tr class="bg-gray-200 text-sm sm:text-base">
                    <th class="p-3 border w-16">Visit</th>
                    <th class="p-3 border">Vaccine / Check-up</th>
                    <th class="p-3 border w-48">Date</th>
                    <th class="p-3 border w-40">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checkups as $index => $checkup)
                    @php
                        $record = $records[$index] ?? null;
                        $date = $record->visit_date ?? '';
                        $status = $date ? 'Complete' : 'Not Started';
                    @endphp
                    <tr>
                        <td class="p-3 border text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="p-3 border text-sm sm:text-base">{{ $checkup }}</td>
                        <td class="p-3 border">
                            <input 
                                type="date" 
                                class="border p-2 rounded w-full visit-date"
                                data-index="{{ $index }}"
                                data-checkup="{{ $checkup }}"
                                value="{{ $date }}">
                        </td>
                        <td class="p-3 border text-center">
                            <span class="status-badge px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : '⏳ Not Started' !!}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($woman->completedImmunizationRecords && $woman->completedImmunizationRecords->count() > 0)
    <h3 class="text-2xl font-bold mb-4 mt-8">🗂️ Completed Immunization Records</h3>

    <table class="w-full border-collapse bg-white shadow-lg mb-6">
        <thead>
            <tr class="bg-gray-200 text-sm sm:text-base">
                <th class="p-3 border">Record ID</th>
                <th class="p-3 border">Completion Date</th>
                <th class="p-3 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($woman->completedImmunizationRecords as $record)
                <tr>
                    <td class="p-3 border text-center">{{ $record->id }}</td>
                    <td class="p-3 border text-center">{{ $record->created_at->format('F d, Y') }}</td>
                    <td class="p-3 border text-center">
                       <button 
    type="button"
    onclick="openCompletedRecordModal({{ $woman->id }}, {{ $record->id }})"
    class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">
    👁️ View
</button>   
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

        {{-- ✅ Print Certificate Button --}}
        @php
            $allComplete = collect($records)->every(fn($record) => !empty($record->visit_date));
        @endphp

       @if ($allComplete)
    <div class="mt-6 flex justify-end space-x-4">
        <!-- Save Completed Record -->
        <form action="{{ route('pregnant.saveCompletedRecord', $woman->id) }}" method="POST">
            @csrf
            <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                💾 Save Completed Record
            </button>
        </form>

        <!-- Download Certificate -->
        <form action="{{ route('pregnant.certificate', $woman->id) }}" method="GET">
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                🖨️ Download Certificate
            </button>
        </form>
    </div>
@endif
</form>
            

        <!-- Toast Notification -->
        <div id="toast" class="fixed bottom-6 right-6 hidden p-4 rounded-lg shadow-lg text-white font-medium z-50"></div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toast = document.getElementById('toast');
    const today = new Date().toISOString().split("T")[0];

    document.querySelectorAll('.visit-date').forEach(input => {
        input.addEventListener('change', async function() {
            const selectedDate = this.value;
            const checkup = this.dataset.checkup;
            const index = this.dataset.index;
            const statusBadge = this.closest('tr').querySelector('.status-badge');

            if (!selectedDate) return;

            if (selectedDate < today) {
                if (!confirm(`You selected a past date for ${checkup} (${selectedDate}). Proceed?`)) {
                    this.value = "";
                    updateBadge(statusBadge, false);
                    return;
                }
            }

            updateBadge(statusBadge, true);

            try {
                const response = await fetch(`{{ route('pregnant.addImmunization', $woman->id) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        visit_dates: Array.from(document.querySelectorAll('.visit-date')).map(el => el.value),
                        remarks: Array.from(document.querySelectorAll('.status-badge')).map(el => 
                            el.textContent.includes('✔️') ? 'Complete' : 'Not Started'
                        )
                    })
                });

                if (response.ok) {
                    showToast('✅ Immunization record updated successfully!', 'bg-green-600');
                } else {
                    showToast('❌ Failed to update record. Try again.', 'bg-red-600');
                }
            } catch (error) {
                showToast('⚠️ Network error. Please try again later.', 'bg-yellow-500');
            }
        });
    });

    function updateBadge(badge, complete) {
        if (complete) {
            badge.textContent = '✔️ Complete';
            badge.className = 'status-badge px-2 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800';
        } else {
            badge.textContent = '⏳ Not Started';
            badge.className = 'status-badge px-2 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800';
        }
    }

    function showToast(message, colorClass) {
        toast.textContent = message;
        toast.className = `fixed bottom-6 right-6 p-4 rounded-lg shadow-lg text-white font-medium z-50 ${colorClass}`;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2500);
    }
});

document.getElementById('clearDatesBtn').addEventListener('click', function () {
        const dateInputs = document.querySelectorAll('.visit-date');
        dateInputs.forEach(input => input.value = '');
        
        // Optionally reset the status badges
        const badges = document.querySelectorAll('.status-badge');
        badges.forEach(badge => {
            badge.classList.remove('bg-green-100', 'text-green-800');
            badge.classList.add('bg-gray-100', 'text-gray-800');
            badge.innerHTML = '⏳ Not Started';
        });
    });

    async function openCompletedRecordModal(pregnantId, recordId) {
  const modal = document.getElementById('completedRecordModal');
  const content = document.getElementById('completedRecordContent');
  modal.classList.remove('hidden');

  // show temporary loading message
  content.innerHTML = `<p class="text-center text-gray-500">Loading...</p>`;

  try {
    const res = await fetch(`/pregnant/${pregnantId}/completed/${recordId}`);
    if (!res.ok) throw new Error('Failed to load record');
    const data = await res.json();

    // Build table dynamically
    let rows = '';
    data.records.forEach(r => {
      rows += `
        <tr>
          <td class="p-2 border text-center">${r.visit_number ?? ''}</td>
          <td class="p-2 border">${r.vaccine_given ?? ''}</td>
          <td class="p-2 border text-center">${r.visit_date ?? ''}</td>
          <td class="p-2 border text-center">${r.remarks ?? ''}</td>
        </tr>`;
    });

    content.innerHTML = `
      <div class="mb-4">
        <p><strong>Pregnant Woman:</strong> ${data.woman_name}</p>
        <p><strong>Date Completed:</strong> ${data.completed_at}</p>
      </div>
      <table class="w-full border-collapse bg-white shadow-lg">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-2 border">Visit</th>
            <th class="p-2 border">Vaccine / Check-up</th>
            <th class="p-2 border">Date</th>
            <th class="p-2 border">Remarks</th>
          </tr>
        </thead>
        <tbody>${rows}</tbody>
      </table>
    `;
  } catch (err) {
    content.innerHTML = `<p class="text-red-500 text-center">Error loading record.</p>`;
  }
}

function closeCompletedRecordModal() {
  document.getElementById('completedRecordModal').classList.add('hidden');
}

 function previewPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    const noPhotoText = document.getElementById('noPhotoText');

    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (noPhotoText) noPhotoText.classList.add('hidden');
      };
      reader.readAsDataURL(file);
    }
  }
</script>

<!-- Completed Record Modal -->
<div id="completedRecordModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl overflow-y-auto max-h-[80vh]">
    <div class="flex justify-between items-center border-b px-4 py-3">
      <h3 class="text-xl font-semibold">Completed Immunization Record</h3>
      <button onclick="closeCompletedRecordModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
    </div>

    <div class="p-6" id="completedRecordContent">
      <!-- content will be injected by JS -->
      <p class="text-center text-gray-500">Loading...</p>
    </div>
  </div>
</div>
</body>
</html>
