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
    <main class="flex-grow p-6 overflow-y-auto">
        <h2 class="text-3xl font-bold mb-6">Edit Infant</h2>

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
                <a href="{{ route('beneficiaries.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded mr-2 hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Save Changes</button>
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
</script>


</body>
</html>