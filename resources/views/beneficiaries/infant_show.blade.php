<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Infant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
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
    <main class="flex-grow p-6 overflow-y-auto">
        <h2 class="text-3xl font-bold mb-6">Edit Infant</h2>
        <p class="text-gray-700 mb-6">
    <span class="font-semibold">Infant Code:</span> 
    <span class="text-blue-700 font-mono bg-blue-100 px-2 py-1 rounded">
        {{ $infant->infant_code }}
    </span>
</p>

        <form action="{{ route('infants.update', $infant->id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')

            <!-- Infant Details -->
            <h3 class="text-xl font-semibold mb-4">Infant Details</h3>
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block mb-2">Child's Name</label>
                    <input type="text" name="child_name" value="{{ $infant->child_name }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Date of Birth</label>
                    <input type="date" name="child_bday" value="{{ $infant->child_bday }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Place of Birth</label>
                    <input type="text" name="child_place" value="{{ $infant->child_place }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Address</label>
                    <input type="text" name="child_address" value="{{ $infant->child_address }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Purok</label>
                    <select name="purok" class="border p-2 w-full rounded mb-4" required>
                        <option value="">Select Purok</option>
                        <option value="purok1" {{ $infant->purok === 'purok1' ? 'selected' : '' }}>Purok 1</option>
                        <option value="purok2" {{ $infant->purok === 'purok2' ? 'selected' : '' }}>Purok 2</option>
                        <option value="purok3" {{ $infant->purok === 'purok3' ? 'selected' : '' }}>Purok 3</option>
                        <option value="purok4" {{ $infant->purok === 'purok4' ? 'selected' : '' }}>Purok 4</option>
                        <option value="purok5" {{ $infant->purok === 'purok5' ? 'selected' : '' }}>Purok 5</option>
                        <option value="purok6" {{ $infant->purok === 'purok6' ? 'selected' : '' }}>Purok 6</option>
                        <option value="purok7" {{ $infant->purok === 'purok7' ? 'selected' : '' }}>Purok 7</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2">Mother's Name</label>
                    <input type="text" name="child_mother" value="{{ $infant->child_mother }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Father's Name</label>
                    <input type="text" name="child_father" value="{{ $infant->child_father }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Gender</label>
                    <select name="child_gender" class="border p-2 w-full rounded mb-4" required>
                        <option value="Male" {{ $infant->child_gender === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $infant->child_gender === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>

                    <label class="block mb-2">Height (cm)</label>
                    <input type="number" step="0.1" name="child_height" value="{{ $infant->child_height }}" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Weight (kg)</label>
                    <input type="number" step="0.1" name="child_weight" value="{{ $infant->child_weight }}" class="border p-2 w-full rounded mb-4" required>
                </div>
            </div>
            
       <!-- Recommended Vaccination Schedule (Innovated Table Design - Fixed) -->
<div x-data="{ open: false }" class="bg-blue-50 border border-blue-300 rounded-xl shadow-md p-5 mb-8 transition-all duration-300">
    <!-- Header / Toggle -->
    <div class="flex justify-between items-center cursor-pointer select-none" @click="open = !open">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4 class="font-semibold text-lg text-blue-800">Recommended Vaccination Schedule</h4>
        </div>
        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform duration-200 text-blue-600"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
    <!-- Collapsible Content -->
    <div x-show="open" x-collapse class="mt-4 text-sm text-gray-700 leading-relaxed space-y-4">
        <p class="mb-3 text-gray-600">
            Based on the child’s date of birth, here are the suggested vaccination dates:
        </p>
        <!-- Innovated Table: Clean, responsive with date pills for better presentation -->
        <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
            <table id="vaccineTable"
                   class="min-w-full text-sm bg-white divide-y divide-gray-200">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider border-r border-gray-200">Vaccine</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Recommended Date(s)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="vaccineTbody">
                    <!-- Placeholder rows (populated by JS) -->
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">BCG</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                    <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">Hepatitis B</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">Pentavalent (3 doses)</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                    <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">OPV (3 doses)</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">IPV (2 doses)</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                    <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">PCV (3 doses)</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">MMR (2 doses)</td>
                        <td class="px-6 py-4 text-gray-700">—</td>
                    </tr>
                </tbody>
            </table>
        </div>
       
        <p class="text-xs text-gray-500 italic flex items-center">
            <span class="inline-block w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>
            These are recommended schedules. Adjust based on actual administration or medical advice.
        </p>
        <!-- PDF Download Button -->
        <div class="flex justify-end">
            <button onclick="downloadVaccinePDF()"
                    class="flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 active:scale-95 transition-all duration-200 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Schedule (PDF)
            </button>
        </div>
    </div>
    <!-- Custom Styles for Date Pills (Innovated Presentation) -->
    <style>
        .date-pill {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            padding: 0.25rem 0.75rem;
            margin: 0.125rem 0.25rem 0.125rem 0;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            border: 1px solid #93c5fd;
        }
        .date-pill::before {
            content: '📅';
            margin-right: 0.25rem;
            font-size: 0.75rem;
        }
        .dose-list {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        @media (max-width: 640px) {
            .date-pill {
                display: block;
                margin: 0.125rem 0;
                text-align: center;
            }
            .dose-list {
                flex-direction: column;
            }
        }
    </style>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            <!-- Immunization Section -->
            <h3 class="text-2xl font-bold mb-6">Immunization</h3>
            <table class="w-full border-collapse bg-white shadow-lg mb-6">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 border">Vaccine</th>
                        <th class="p-3 border">Doses</th>
                        <th class="p-3 border">Date Taken</th>
                        <th class="p-3 border">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php $im = $infant->immunization; @endphp

                    <!-- BCG -->
                    <tr>
                        <td class="p-3 border">BCG Vaccine</td>
                        <td class="p-3 border">1 dose</td>
                        <td class="p-3 border">
                            <input type="date" name="bcg_date" id="bcg_date" value="{{ $im->bcg_date ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php $status = $im->bcg_date ? 'Complete' : 'Not Started'; @endphp
                            <span id="remarks-bcg" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : '⏳ Not Started' !!}
                            </span>
                        </td>
                    </tr>

                    <!-- Hepatitis B -->
                    <tr>
                        <td class="p-3 border">Hepatitis B Vaccine</td>
                        <td class="p-3 border">1 dose</td>
                        <td class="p-3 border">
                            <input type="date" name="hepatitis_b_date" id="hepatitis_b_date" value="{{ $im->hepatitis_b_date ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php $status = $im->hepatitis_b_date ? 'Complete' : 'Not Started'; @endphp
                            <span id="remarks-hepatitis_b" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : '⏳ Not Started' !!}
                            </span>
                        </td>
                    </tr>

                    <!-- Pentavalent -->
                    <tr>
                        <td class="p-3 border">Pentavalent Vaccine</td>
                        <td class="p-3 border">3 doses</td>
                        <td class="p-3 border">
                            <input type="date" name="pentavalent_date_1" id="pentavalent_date_1" value="{{ $im->pentavalent_date_1 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="pentavalent_date_2" id="pentavalent_date_2" value="{{ $im->pentavalent_date_2 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="pentavalent_date_3" id="pentavalent_date_3" value="{{ $im->pentavalent_date_3 ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php
                                $filled = collect([$im->pentavalent_date_1, $im->pentavalent_date_2, $im->pentavalent_date_3])->filter()->count();
                                $status = $filled === 0 ? 'Not Started' : ($filled < 3 ? 'Incomplete' : 'Complete');
                            @endphp
                            <span id="remarks-pentavalent" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : ($status === 'Incomplete' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : ($status === 'Incomplete' ? '⚠️ Incomplete' : '⏳ Not Started') !!}
                            </span>
                        </td>
                    </tr>

                    <!-- OPV -->
                    <tr>
                        <td class="p-3 border">Oral Polio Vaccine (OPV)</td>
                        <td class="p-3 border">3 doses</td>
                        <td class="p-3 border">
                            <input type="date" name="opv_date_1" id="opv_date_1" value="{{ $im->opv_date_1 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="opv_date_2" id="opv_date_2" value="{{ $im->opv_date_2 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="opv_date_3" id="opv_date_3" value="{{ $im->opv_date_3 ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php
                                $filled = collect([$im->opv_date_1, $im->opv_date_2, $im->opv_date_3])->filter()->count();
                                $status = $filled === 0 ? 'Not Started' : ($filled < 3 ? 'Incomplete' : 'Complete');
                            @endphp
                            <span id="remarks-opv" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : ($status === 'Incomplete' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : ($status === 'Incomplete' ? '⚠️ Incomplete' : '⏳ Not Started') !!}
                            </span>
                        </td>
                    </tr>

                    <!-- IPV -->
                    <tr>
                        <td class="p-3 border">Inactivated Polio Vaccine (IPV)</td>
                        <td class="p-3 border">2 doses</td>
                        <td class="p-3 border">
                            <input type="date" name="ipv_date_1" id="ipv_date_1" value="{{ $im->ipv_date_1 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="ipv_date_2" id="ipv_date_2" value="{{ $im->ipv_date_2 ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php
                                $filled = collect([$im->ipv_date_1, $im->ipv_date_2])->filter()->count();
                                $status = $filled === 0 ? 'Not Started' : ($filled < 2 ? 'Incomplete' : 'Complete');
                            @endphp
                            <span id="remarks-ipv" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : ($status === 'Incomplete' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : ($status === 'Incomplete' ? '⚠️ Incomplete' : '⏳ Not Started') !!}
                            </span>
                        </td>
                    </tr>

                    <!-- PCV -->
                    <tr>
                        <td class="p-3 border">Pneumococcal Conjugate Vaccine (PCV)</td>
                        <td class="p-3 border">3 doses</td>
                        <td class="p-3 border">
                            <input type="date" name="pcv_date_1" id="pcv_date_1" value="{{ $im->pcv_date_1 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="pcv_date_2" id="pcv_date_2" value="{{ $im->pcv_date_2 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="pcv_date_3" id="pcv_date_3" value="{{ $im->pcv_date_3 ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php
                                $filled = collect([$im->pcv_date_1, $im->pcv_date_2, $im->pcv_date_3])->filter()->count();
                                $status = $filled === 0 ? 'Not Started' : ($filled < 3 ? 'Incomplete' : 'Complete');
                            @endphp
                            <span id="remarks-pcv" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : ($status === 'Incomplete' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : ($status === 'Incomplete' ? '⚠️ Incomplete' : '⏳ Not Started') !!}
                            </span>
                        </td>
                    </tr>

                    <!-- MMR -->
                    <tr>
                        <td class="p-3 border">Measles, Mumps, Rubella (MMR)</td>
                        <td class="p-3 border">2 doses</td>
                        <td class="p-3 border">
                            <input type="date" name="mmr_date_1" id="mmr_date_1" value="{{ $im->mmr_date_1 ?? '' }}" class="border p-2 rounded mb-2">
                            <input type="date" name="mmr_date_2" id="mmr_date_2" value="{{ $im->mmr_date_2 ?? '' }}" class="border p-2 rounded">
                        </td>
                        <td class="p-3 border">
                            @php
                                $filled = collect([$im->mmr_date_1, $im->mmr_date_2])->filter()->count();
                                $status = $filled === 0 ? 'Not Started' : ($filled < 2 ? 'Incomplete' : 'Complete');
                            @endphp
                            <span id="remarks-mmr" class="px-2 py-1 rounded-full text-sm font-semibold 
                                {{ $status === 'Complete' ? 'bg-green-100 text-green-800' : ($status === 'Incomplete' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {!! $status === 'Complete' ? '✔️ Complete' : ($status === 'Incomplete' ? '⚠️ Incomplete' : '⏳ Not Started') !!}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Action Buttons -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('beneficiaries.infants') }}" class="bg-gray-500 text-white py-2 px-4 rounded mr-2 hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Save Changes</button>
                <a href="{{ route('infants.generateCertificate', $infant->id) }}"
   class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 ml-2">
   Generate Vaccination Certificate (PDF)
</a>
            </div>
        </form>
    </main>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <h2 class="text-lg font-bold mb-4">Confirm Vaccination Date</h2>
        <p id="confirmMessage" class="mb-4 text-gray-700"></p>
        <div class="flex justify-end space-x-4">
            <button id="cancelBtn" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">No</button>
            <button id="confirmBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Yes</button>
        </div>
    </div>
</div>

<script>
    // Function to update remarks based on group inputs
    function updateRemarks(group) {
        const inputs = document.querySelectorAll(`[id^="${group}_date"]`);
        let filled = 0, total = inputs.length;

        inputs.forEach(input => { if (input.value) filled++; });

        const remarks = document.getElementById(`remarks-${group}`);
        if (remarks) {
            if (filled === 0) {
                remarks.textContent = '⏳ Not Started';
                remarks.className = 'px-2 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800';
            } else if (filled < total) {
                remarks.textContent = '⚠️ Incomplete';
                remarks.className = 'px-2 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800';
            } else {
                remarks.textContent = '✔️ Complete';
                remarks.className = 'px-2 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800';
            }
        }
    }

    // Auto-update remarks dynamically with icons
    document.addEventListener('input', function (event) {
        if (event.target.type !== 'date') return;

        const id = event.target.id;
        if (!id) return;

        const group = id.split('_')[0];
        updateRemarks(group);
    });

    // Confirmation modal logic
    const today = new Date().toISOString().split("T")[0];
    let targetInput = null;

    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener("change", function () {
            const selectedDate = this.value;
            if (selectedDate && selectedDate < today) { // ⬅️ only past dates
                targetInput = this;
                const todayFormatted = new Date().toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
                const chosenFormatted = new Date(selectedDate).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
                
                document.getElementById("confirmMessage").innerHTML = `
                    Are you sure that the vaccination took place <br>
                    <strong>${chosenFormatted}</strong>? <br><br>
                    The day today is: <strong>${todayFormatted}</strong>
                `;

                document.getElementById("confirmModal").classList.remove("hidden");
            }
        });
    });

    document.getElementById("cancelBtn").addEventListener("click", () => {
        if (targetInput) {
            // Clear the input
            targetInput.value = "";

            // Re-evaluate remarks for the vaccine group
            const group = targetInput.id.split('_')[0];
            updateRemarks(group);
        }
        document.getElementById("confirmModal").classList.add("hidden");
    });

    document.getElementById("confirmBtn").addEventListener("click", () => {
        targetInput = null;
        document.getElementById("confirmModal").classList.add("hidden");
    });

   // 🩺 Vaccine Recommendation Logic (Table Format - using BCG/Hepatitis B as base)
function calculateRecommendedDates(baseDate) {
    const base = new Date(baseDate);
    if (isNaN(base)) return {};

    const addWeeks = (w) => {
        const date = new Date(base);
        date.setDate(date.getDate() + w * 7);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    return {
        bcg: addWeeks(0),
        hepatitis_b: addWeeks(0),
        pentavalent: [addWeeks(6), addWeeks(10), addWeeks(14)],
        opv: [addWeeks(6), addWeeks(10), addWeeks(14)],
        ipv: [addWeeks(14), addWeeks(20)],
        pcv: [addWeeks(6), addWeeks(10), addWeeks(14)],
        mmr: [addWeeks(36), addWeeks(52)]
    };
}

// 🧮 Update Vaccine Table (uses BCG/Hepatitis B as base)
function updateVaccineTable() {
    const bcgDate = document.querySelector('input[name="bcg_date"]')?.value;
    const hepDate = document.querySelector('input[name="hepatitis_b_date"]')?.value;
    const bdayDate = document.querySelector('input[name="child_bday"]')?.value;

    // 🩹 Pick the base date: BCG → Hepatitis B → Birthday
    const baseDate = bcgDate || hepDate || bdayDate;
    const vaccineTable = document.getElementById("vaccineTable");
    if (!baseDate || !vaccineTable) return;

    const rec = calculateRecommendedDates(baseDate);

    vaccineTable.innerHTML = `
        <tr><td class="px-3 py-2 font-medium border border-gray-300">BCG</td><td class="px-3 py-2 border border-gray-300">${rec.bcg}</td></tr>
        <tr><td class="px-3 py-2 font-medium border border-gray-300">Hepatitis B</td><td class="px-3 py-2 border border-gray-300">${rec.hepatitis_b}</td></tr>
        <tr><td class="px-3 py-2 font-medium border border-gray-300">Pentavalent (3 doses)</td><td class="px-3 py-2 border border-gray-300">${rec.pentavalent.join('<br>')}</td></tr>
        <tr><td class="px-3 py-2 font-medium border border-gray-300">OPV (3 doses)</td><td class="px-3 py-2 border border-gray-300">${rec.opv.join('<br>')}</td></tr>
        <tr><td class="px-3 py-2 font-medium border border-gray-300">IPV (2 doses)</td><td class="px-3 py-2 border border-gray-300">${rec.ipv.join('<br>')}</td></tr>
        <tr><td class="px-3 py-2 font-medium border border-gray-300">PCV (3 doses)</td><td class="px-3 py-2 border border-gray-300">${rec.pcv.join('<br>')}</td></tr>
        <tr><td class="px-3 py-2 font-medium border border-gray-300">MMR (2 doses)</td><td class="px-3 py-2 border border-gray-300">${rec.mmr.join('<br>')}</td></tr>
    `;
}

// 🔁 Sync BCG → Hepatitis B automatically
document.addEventListener("DOMContentLoaded", function () {
    const bcgInput = document.querySelector('input[name="bcg_date"]');
    const hepInput = document.querySelector('input[name="hepatitis_b_date"]');
    const bdayInput = document.querySelector('input[name="child_bday"]');

    if (!bcgInput || !hepInput) return;

    // If BCG changes, copy to Hepatitis B and refresh
    bcgInput.addEventListener("change", function () {
        if (this.value) {
            hepInput.value = this.value;
        }
        updateVaccineTable();
    });

    // Refresh whenever relevant dates change
    [hepInput, bdayInput].forEach(input => {
        if (input) input.addEventListener("change", updateVaccineTable);
    });

    // Initial load
    updateVaccineTable();
});

// 🧾 Generate PDF
function downloadVaccinePDF() {
    const table = document.getElementById("vaccineTable").cloneNode(true);
    const childName = document.querySelector('input[name="child_name"]')?.value || 'Infant';
    const bcgDate = document.querySelector('input[name="bcg_date"]')?.value;
    const hepDate = document.querySelector('input[name="hepatitis_b_date"]')?.value;
    const baseUsed = bcgDate ? `BCG: ${bcgDate}` : hepDate ? `Hepatitis B: ${hepDate}` : '';

    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
        <h2 style="text-align:center; color:#1e40af;">Recommended Vaccination Schedule</h2>
        <p style="text-align:center; margin-bottom:10px;">Child: <strong>${childName}</strong> ${baseUsed ? `• Base Date: ${baseUsed}` : ''}</p>
        ${table.outerHTML}
        <p style="font-size:12px; text-align:center; margin-top:10px; color:gray;">
            Generated by BAHMS | ${new Date().toLocaleDateString()}
        </p>
    `;

    const opt = {
        margin: 0.5,
        filename: `${childName}_Vaccine_Schedule.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(wrapper).save();
}
</script>


</body>
</html>