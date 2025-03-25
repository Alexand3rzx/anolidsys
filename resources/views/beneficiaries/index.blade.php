<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficiaries</title>
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
        <main class="flex-grow p-6">
            <h2 class="text-3xl font-bold mb-6">Beneficiaries</h2>

            <!-- Flex Container for Tables -->
            <div class="flex gap-6">


           <!-- Pregnant Women Table -->
<div class="w-1/2 bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold">Pregnant Women</h3>
        <input type="text" id="pregnantSearch" class="border p-2 rounded" placeholder="Search..." onkeyup="searchPregnant()">
        <button onclick="toggleModal()" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            + Add Pregnant
        </button>
    </div>

    <table id="pregnantTable" class="w-full border-collapse bg-white shadow-lg">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 border">Name</th>
                <th class="p-3 border">Age</th>
                <th class="p-3 border">Address</th>
            </tr>
        </thead>
        <tbody id="pregnantTableBody">
            @forelse($pregnantWomen as $woman)
                <tr onclick="openEditModal({{ $woman->id }})" class="cursor-pointer hover:bg-gray-100">
                    <td class="p-3 border">{{ $woman->prgname }}</td>
                    <td class="p-3 border">{{ $woman->prgage }}</td>
                    <td class="p-3 border">{{ $woman->prgaddress }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-3 text-center border">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $pregnantWomen->links() }}
    </div>
</div>

<!-- Infants Table -->
<div class="w-1/2 bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold">Infants</h3>
        <input type="text" id="infantSearch" class="border p-2 rounded" placeholder="Search..." onkeyup="searchInfants()">
        <button onclick="toggleInfantModal()" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
            + Add Infant
        </button>
    </div>

    <table id="infantsTable" class="w-full border-collapse bg-white shadow-lg">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 border">Child's Name</th>
                <th class="p-3 border">Date of Birth</th>
                <th class="p-3 border">Mother's Name</th>
                <th class="p-3 border">Gender</th>
            </tr>
        </thead>
        <tbody id="infantsTableBody">
            @forelse($infants as $infant)
                <tr onclick="openEditInfantModal({{ $infant->id }})" class="cursor-pointer hover:bg-gray-100">
                    <td class="p-3 border">{{ $infant->child_name }}</td>
                    <td class="p-3 border">{{ $infant->child_bday }}</td>
                    <td class="p-3 border">{{ $infant->child_mother }}</td>
                    <td class="p-3 border">{{ $infant->child_gender }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-3 text-center border">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $infants->links() }}
    </div>
</div>

  <!-- Add Pregnant Woman Modal -->
<div id="pregnantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-1/2">
        <h3 class="text-2xl font-bold mb-6">Add Pregnant Woman</h3>
        <form method="POST" action="{{ route('pregnant.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <!-- Left Side -->
                <div>
                    <label class="block mb-2">Full Name</label>
                    <input type="text" name="prgname" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Date of Birth</label>
                    <input type="date" name="prgbday" id="prgbday" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Age</label>
                    <input type="number" name="prgage" id="prgage" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Address</label>
                    <input type="text" name="prgaddress" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Occupation</label>
                    <input type="text" name="prgoccupation" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Civil Status</label>
                    <select name="prgreligion" class="border p-2 w-full rounded mb-4" required>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Separated">Separated</option>
                    </select>

                    <label class="block mb-2">Mother's Name</label>
                    <input type="text" name="prgmother_name" class="border p-2 w-full rounded mb-4">
                </div>

                <!-- Right Side -->
                <div>
                    <label class="block mb-2">Partner's Name</label>
                    <input type="text" name="partner_name" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Date of Birth</label>
                    <input type="date" name="partner_bday" id="partner_bday" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Age</label>
                    <input type="number" name="partner_age" id="partner_age" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Occupation</label>
                    <input type="text" name="partner_occupation" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Civil Status</label>
                    <select name="partner_religion" class="border p-2 w-full rounded mb-4">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Separated">Separated</option>
                    </select>

                    <label class="block mb-2">Partner's Contact Number</label>
                    <input type="text" name="partner_number" class="border p-2 w-full rounded mb-4">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" onclick="toggleModal()" class="bg-gray-500 text-white py-2 px-4 rounded mr-2 hover:bg-gray-600">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Add Infant Modal -->
    <div id="addInfantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-1/2">
            <h3 class="text-2xl font-bold mb-6">Add Infant</h3>
            <form method="POST" action="{{ route('infants.store') }}">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <!-- Left Side -->
                    <div>
                        <label class="block mb-2">Child's Name</label>
                        <input type="text" name="child_name" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Date of Birth</label>
                        <input type="date" name="child_bday" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Place of Birth</label>
                        <input type="text" name="child_place" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Address</label>
                        <input type="text" name="child_address" class="border p-2 w-full rounded mb-4" required>
                    </div>

                    <!-- Right Side -->
                    <div>
                        <label class="block mb-2">Mother's Name</label>
                        <input type="text" name="child_mother" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Father's Name</label>
                        <input type="text" name="child_father" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Gender</label>
                        <select name="child_gender" class="border p-2 w-full rounded mb-4" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>

                        <label class="block mb-2">Height (cm)</label>
                        <input type="number" step="0.1" name="child_height" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Weight (kg)</label>
                        <input type="number" step="0.1" name="child_weight" class="border p-2 w-full rounded mb-4" required>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeModal('addInfantModal')" class="bg-gray-500 text-white py-2 px-4 rounded mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

   <!-- Edit Infant Modal with Immunization Section -->
   <div id="editInfantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-3/4 max-h-screen overflow-y-auto">
            <h3 class="text-2xl font-bold mb-6">Edit Infant</h3>
            <form id="editInfantForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editInfantId">

                <!-- Infant Details -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Left Side -->
                    <div>
                        <label class="block mb-2">Child's Name</label>
                        <input type="text" name="child_name" id="editChildName" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Date of Birth</label>
                        <input type="date" name="child_bday" id="editChildBday" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Place of Birth</label>
                        <input type="text" name="child_place" id="editChildPlace" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Address</label>
                        <input type="text" name="child_address" id="editChildAddress" class="border p-2 w-full rounded mb-4" required>
                    </div>

                    <!-- Right Side -->
                    <div>
                        <label class="block mb-2">Mother's Name</label>
                        <input type="text" name="child_mother" id="editChildMother" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Father's Name</label>
                        <input type="text" name="child_father" id="editChildFather" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Gender</label>
                        <select name="child_gender" id="editChildGender" class="border p-2 w-full rounded mb-4" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>

                        <label class="block mb-2">Height (cm)</label>
                        <input type="number" step="0.1" name="child_height" id="editChildHeight" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Weight (kg)</label>
                        <input type="number" step="0.1" name="child_weight" id="editChildWeight" class="border p-2 w-full rounded mb-4" required>
                    </div>
                </div>

                
                <!-- Immunization Section -->
                <div class="mt-8">
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
                            <!-- BCG Vaccine -->
                            <tr>
                                <td class="p-3 border">BCG Vaccine</td>
                                <td class="p-3 border">1 dose</td>
                                <td class="p-3 border">
                                    <input type="date" name="bcg_date" id="bcg_date" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-bcg">Not Started</span>
                                </td>
                            </tr>

                            <!-- Hepatitis B Vaccine -->
                            <tr>
                                <td class="p-3 border">Hepatitis B Vaccine</td>
                                <td class="p-3 border">1 dose</td>
                                <td class="p-3 border">
                                    <input type="date" name="hepatitis_b_date" id="hepatitis_b_date" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-hepatitis-b">Not Started</span>
                                </td>
                            </tr>

                            <!-- Pentavalent Vaccine -->
                            <tr>
                                <td class="p-3 border">Pentavalent Vaccine</td>
                                <td class="p-3 border">3 doses</td>
                                <td class="p-3 border">
                                    <input type="date" name="pentavalent_date_1" id="pentavalent_date_1" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="pentavalent_date_2" id="pentavalent_date_2" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="pentavalent_date_3" id="pentavalent_date_3" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-pentavalent">Not Started</span>
                                </td>
                            </tr>

                            <!-- Oral Polio Vaccine (OPV) -->
                            <tr>
                                <td class="p-3 border">Oral Polio Vaccine (OPV)</td>
                                <td class="p-3 border">3 doses</td>
                                <td class="p-3 border">
                                    <input type="date" name="opv_date_1" id="opv_date_1" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="opv_date_2" id="opv_date_2" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="opv_date_3" id="opv_date_3" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-opv">Not Started</span>
                                </td>
                            </tr>

                            <!-- Inactivated Polio Vaccine (IPV) -->
                            <tr>
                                <td class="p-3 border">Inactivated Polio Vaccine (IPV)</td>
                                <td class="p-3 border">2 doses</td>
                                <td class="p-3 border">
                                    <input type="date" name="ipv_date_1" id="ipv_date_1" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="ipv_date_2" id="ipv_date_2" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-ipv">Not Started</span>
                                </td>
                            </tr>

                            <!-- Pneumococcal Conjugate Vaccine (PCV) -->
                            <tr>
                                <td class="p-3 border">Pneumococcal Conjugate Vaccine (PCV)</td>
                                <td class="p-3 border">3 doses</td>
                                <td class="p-3 border">
                                    <input type="date" name="pcv_date_1" id="pcv_date_1" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="pcv_date_2" id="pcv_date_2" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="pcv_date_3" id="pcv_date_3" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-pcv">Not Started</span>
                                </td>
                            </tr>

                            <!-- Measles, Mumps, Rubella Vaccine (MMR) -->
                            <tr>
                                <td class="p-3 border">Measles, Mumps, Rubella Vaccine (MMR)</td>
                                <td class="p-3 border">2 doses</td>
                                <td class="p-3 border">
                                    <input type="date" name="mmr_date_1" id="mmr_date_1" class="border p-2 w-70 rounded mb-2">
                                    <input type="date" name="mmr_date_2" id="mmr_date_2" class="border p-2 w-70 rounded">
                                </td>
                                <td class="p-3 border">
                                    <span id="remarks-mmr">Not Started</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeModal('editInfantModal')" class="bg-gray-500 text-white py-2 px-4 rounded mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Pregnant Woman Modal -->
<div id="editPregnantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-1/2">
        <h3 class="text-2xl font-bold mb-6">Edit Pregnant Woman</h3>
        <form id="editPregnantForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">

            <div class="grid grid-cols-2 gap-6">
                <!-- Left Side -->
                <div>
                    <label class="block mb-2">Full Name</label>
                    <input type="text" name="prgname" id="editPrgname" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Date of Birth</label>
                    <input type="date" name="prgbday" id="editPrgbday" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Age</label>
<input type="number" name="prgage" id="editPrgage" class="border p-2 w-full rounded mb-4 bg-gray-100 cursor-not-allowed" readonly>

                    <label class="block mb-2">Address</label>
                    <input type="text" name="prgaddress" id="editPrgaddress" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Occupation</label>
                    <input type="text" name="prgoccupation" id="editPrgoccupation" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Civil Status</label>
                    <!-- Replace the input with a dropdown -->
                    <select name="prgreligion" id="editPrgreligion" class="border p-2 w-full rounded mb-4" required>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Separated">Separated</option>
                    </select>

                    <label class="block mb-2">Mother's Name</label>
                    <input type="text" name="prgmother_name" id="editPrgmotherName" class="border p-2 w-full rounded mb-4">
                </div>

                <!-- Right Side -->
                <div>
                    <label class="block mb-2">Partner's Name</label>
                    <input type="text" name="partner_name" id="editPartnerName" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Age</label>
<input type="number" name="partner_age" id="editPartnerAge" class="border p-2 w-full rounded mb-4 bg-gray-100 cursor-not-allowed" readonly>

                    <label class="block mb-2">Partner's Date of Birth</label>
                    <input type="date" name="partner_bday" id="editPartnerBday" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Occupation</label>
                    <input type="text" name="partner_occupation" id="editPartnerOccupation" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Civil Status</label>
                    <!-- Replace the input with a dropdown -->
                    <select name="partner_religion" id="editPartnerReligion" class="border p-2 w-full rounded mb-4">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Separated">Separated</option>
                    </select>

                    <label class="block mb-2">Partner's Contact Number</label>
                    <input type="text" name="partner_number" id="editPartnerNumber" class="border p-2 w-full rounded mb-4">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeModal('editPregnantModal')" class="bg-gray-500 text-white py-2 px-4 rounded mr-2 hover:bg-gray-600">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('pregnantModal');
            modal.classList.toggle('hidden');
        }

        async function openEditModal(id) {
    try {
        const response = await fetch(`/pregnant/${id}/edit`);
        if (!response.ok) throw new Error('Failed to fetch record');
        const woman = await response.json();

        // Populate the edit modal with the fetched data
        document.getElementById('editId').value = woman.id;
        document.getElementById('editPrgname').value = woman.prgname;
        document.getElementById('editPrgage').value = woman.prgage;
        document.getElementById('editPrgbday').value = woman.prgbday;
        document.getElementById('editPrgaddress').value = woman.prgaddress;
        document.getElementById('editPrgoccupation').value = woman.prgoccupation;
        document.getElementById('editPrgmotherName').value = woman.prgmother_name;

        // Partner's details
        document.getElementById('editPartnerName').value = woman.partner_name;
        document.getElementById('editPartnerAge').value = woman.partner_age;
        document.getElementById('editPartnerBday').value = woman.partner_bday;
        document.getElementById('editPartnerOccupation').value = woman.partner_occupation;
        document.getElementById('editPartnerNumber').value = woman.partner_number;

        // Set the selected option for Civil Status dropdowns
        const prgreligionDropdown = document.getElementById('editPrgreligion');
        const partnerReligionDropdown = document.getElementById('editPartnerReligion');

        if (prgreligionDropdown) {
            prgreligionDropdown.value = woman.prgreligion; // Set the selected value
        }

        if (partnerReligionDropdown) {
            partnerReligionDropdown.value = woman.partner_religion; // Set the selected value
        }

        // Set the form action dynamically
        document.getElementById('editPregnantForm').action = `/pregnant/${woman.id}`;

        // Show the edit modal
        document.getElementById('editPregnantModal').classList.remove('hidden');
    } catch (error) {
        console.error('Error fetching record:', error);
        alert('Failed to fetch record. Please try again.');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

        
    


        // Toggle Add Infant Modal
        function toggleInfantModal() {
            const modal = document.getElementById('addInfantModal');
            modal.classList.toggle('hidden');
        }

    

       // Open Edit Infant Modal
       async function openEditInfantModal(id) {
    try {
        // Fetch the infant data
        const response = await fetch(`/infants/${id}/edit`);
        if (!response.ok) throw new Error('Failed to fetch record');
        const infant = await response.json();

        // Log the fetched data for debugging
        console.log('Fetched Infant Data:', infant);

        // Populate the infant details
        document.getElementById('editInfantId').value = infant.id;
        document.getElementById('editChildName').value = infant.child_name;
        document.getElementById('editChildBday').value = infant.child_bday;
        document.getElementById('editChildPlace').value = infant.child_place;
        document.getElementById('editChildAddress').value = infant.child_address;
        document.getElementById('editChildMother').value = infant.child_mother;
        document.getElementById('editChildFather').value = infant.child_father;
        document.getElementById('editChildGender').value = infant.child_gender;
        document.getElementById('editChildHeight').value = infant.child_height;
        document.getElementById('editChildWeight').value = infant.child_weight;

        // Populate immunization fields
        if (infant.immunization) {
            const immunization = infant.immunization;

            // BCG Vaccine
            document.getElementById('bcg_date').value = immunization.bcg_date || '';
            document.getElementById('remarks-bcg').textContent = immunization.bcg_date ? 'Complete' : 'Not Started';

            // Hepatitis B Vaccine
            document.getElementById('hepatitis_b_date').value = immunization.hepatitis_b_date || '';
            document.getElementById('remarks-hepatitis-b').textContent = immunization.hepatitis_b_date ? 'Complete' : 'Not Started';

            // Pentavalent Vaccine
            document.getElementById('pentavalent_date_1').value = immunization.pentavalent_date_1 || '';
            document.getElementById('pentavalent_date_2').value = immunization.pentavalent_date_2 || '';
            document.getElementById('pentavalent_date_3').value = immunization.pentavalent_date_3 || '';
            document.getElementById('remarks-pentavalent').textContent =
                immunization.pentavalent_date_1 && immunization.pentavalent_date_2 && immunization.pentavalent_date_3
                    ? 'Complete'
                    : (immunization.pentavalent_date_1 || immunization.pentavalent_date_2 || immunization.pentavalent_date_3
                        ? 'Incomplete'
                        : 'Not Started');

            // Oral Polio Vaccine (OPV)
            document.getElementById('opv_date_1').value = immunization.opv_date_1 || '';
            document.getElementById('opv_date_2').value = immunization.opv_date_2 || '';
            document.getElementById('opv_date_3').value = immunization.opv_date_3 || '';
            document.getElementById('remarks-opv').textContent =
                immunization.opv_date_1 && immunization.opv_date_2 && immunization.opv_date_3
                    ? 'Complete'
                    : (immunization.opv_date_1 || immunization.opv_date_2 || immunization.opv_date_3
                        ? 'Incomplete'
                        : 'Not Started');

            // Inactivated Polio Vaccine (IPV)
            document.getElementById('ipv_date_1').value = immunization.ipv_date_1 || '';
            document.getElementById('ipv_date_2').value = immunization.ipv_date_2 || '';
            document.getElementById('remarks-ipv').textContent =
                immunization.ipv_date_1 && immunization.ipv_date_2
                    ? 'Complete'
                    : (immunization.ipv_date_1 || immunization.ipv_date_2
                        ? 'Incomplete'
                        : 'Not Started');

            // Pneumococcal Conjugate Vaccine (PCV)
            document.getElementById('pcv_date_1').value = immunization.pcv_date_1 || '';
            document.getElementById('pcv_date_2').value = immunization.pcv_date_2 || '';
            document.getElementById('pcv_date_3').value = immunization.pcv_date_3 || '';
            document.getElementById('remarks-pcv').textContent =
                immunization.pcv_date_1 && immunization.pcv_date_2 && immunization.pcv_date_3
                    ? 'Complete'
                    : (immunization.pcv_date_1 || immunization.pcv_date_2 || immunization.pcv_date_3
                        ? 'Incomplete'
                        : 'Not Started');

            // Measles, Mumps, Rubella Vaccine (MMR)
            document.getElementById('mmr_date_1').value = immunization.mmr_date_1 || '';
            document.getElementById('mmr_date_2').value = immunization.mmr_date_2 || '';
            document.getElementById('remarks-mmr').textContent =
                immunization.mmr_date_1 && immunization.mmr_date_2
                    ? 'Complete'
                    : (immunization.mmr_date_1 || immunization.mmr_date_2
                        ? 'Incomplete'
                        : 'Not Started');
        }

        // Update the form action
        document.getElementById('editInfantForm').action = `/infants/${infant.id}`;

        // Show the modal
        document.getElementById('editInfantModal').classList.remove('hidden');
    } catch (error) {
        console.error('Error fetching infant record:', error);
        alert('Failed to fetch infant record. Please try again.');
    }
}
// Event listener for input changes
document.addEventListener('input', function (event) {
    const target = event.target;
    const idParts = target.id.split('_');
    if (idParts.length > 2) {
        const infantId = idParts[idParts.length - 1]; // Extract infant ID from the input's ID

        // Group inputs by vaccine type
        const vaccineGroups = {
            'bcg': ['bcg_date'],
            'hepatitis-b': ['hepatitis_b_date'],
            'pentavalent': ['pentavalent_date_1', 'pentavalent_date_2', 'pentavalent_date_3'],
            'opv': ['opv_date_1', 'opv_date_2', 'opv_date_3'],
            'ipv': ['ipv_date_1', 'ipv_date_2'],
            'pcv': ['pcv_date_1', 'pcv_date_2', 'pcv_date_3'],
            'mmr': ['mmr_date_1', 'mmr_date_2']
        };

        // Update remarks for each vaccine group
        for (const [vaccine, fields] of Object.entries(vaccineGroups)) {
            const allDosesComplete = fields.every(field => {
                const input = document.getElementById(`${field}_${infantId}`);
                return input && input.value; // Check if all doses have a value
            });

            const remarksEl = document.getElementById(`remarks-${vaccine}_${infantId}`);
            if (remarksEl) {
                if (fields.length === 1) {
                    // Single-dose vaccines (BCG, Hepatitis B)
                    remarksEl.textContent = allDosesComplete ? 'Complete' : 'Not Started';
                } else {
                    // Multi-dose vaccines (Pentavalent, OPV, IPV, PCV, MMR)
                    const anyDoseStarted = fields.some(field => {
                        const input = document.getElementById(`${field}_${infantId}`);
                        return input && input.value;
                    });

                    if (allDosesComplete) {
                        remarksEl.textContent = 'Complete';
                    } else if (anyDoseStarted) {
                        remarksEl.textContent = 'Incomplete';
                    } else {
                        remarksEl.textContent = 'Not Started';
                    }
                }
            }
        }
    }
});
      // Function to calculate age based on the date of birth
      function calculateAge(dateOfBirth) {
        const today = new Date();
        const birthDate = new Date(dateOfBirth);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();

        // Adjust age if the birthday hasn't occurred yet this year
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }

   // Add an event listener to the Date of Birth input field in the Add Modal
document.getElementById('prgbday')?.addEventListener('change', function () {
    const dateOfBirth = this.value; // Get the selected date of birth
    const ageInput = document.getElementById('prgage'); // Get the Age input field

    if (dateOfBirth) {
        const age = calculateAge(dateOfBirth); // Calculate the age
        ageInput.value = age; // Update the Age field
    } else {
        ageInput.value = ''; // Clear the Age field if no date is selected
    }
});

// Add an event listener to the Partner's Date of Birth input field in the Add Modal
document.getElementById('partner_bday')?.addEventListener('change', function () {
    const dateOfBirth = this.value; // Get the selected date of birth
    const ageInput = document.getElementById('partner_age'); // Get the Partner's Age input field

    if (dateOfBirth) {
        const age = calculateAge(dateOfBirth); // Calculate the age
        ageInput.value = age; // Update the Partner's Age field
    } else {
        ageInput.value = ''; // Clear the Partner's Age field if no date is selected
    }
});

// Add an event listener to the Date of Birth input field in the Edit Modal
document.getElementById('editPrgbday')?.addEventListener('change', function () {
    const dateOfBirth = this.value; // Get the selected date of birth
    const ageInput = document.getElementById('editPrgage'); // Get the Age input field

    if (dateOfBirth) {
        const age = calculateAge(dateOfBirth); // Calculate the age
        ageInput.value = age; // Update the Age field
    } else {
        ageInput.value = ''; // Clear the Age field if no date is selected
    }
});

// Add an event listener to the Partner's Date of Birth input field in the Edit Modal
document.getElementById('editPartnerBday')?.addEventListener('change', function () {
    const dateOfBirth = this.value; // Get the selected date of birth
    const ageInput = document.getElementById('editPartnerAge'); // Get the Partner's Age input field

    if (dateOfBirth) {
        const age = calculateAge(dateOfBirth); // Calculate the age
        ageInput.value = age; // Update the Partner's Age field
    } else {
        ageInput.value = ''; // Clear the Partner's Age field if no date is selected
    }
});


function searchPregnant() {
    let input = document.getElementById("pregnantSearch").value.toLowerCase();
    let table = document.getElementById("pregnantTableBody");
    let rows = table.getElementsByTagName("tr");

    for (let row of rows) {
        let name = row.cells[0]?.innerText.toLowerCase();
        let age = row.cells[1]?.innerText.toLowerCase();
        let address = row.cells[2]?.innerText.toLowerCase();
        
        if (name.includes(input) || age.includes(input) || address.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}

document.getElementById("pregnantSearch").addEventListener("keyup", function () {
    let query = this.value;

    fetch(`/search-pregnant?query=${query}`)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("pregnantTableBody");
            tableBody.innerHTML = "";

            if (data.length > 0) {
                data.forEach(woman => {
                    let row = `<tr onclick="openEditModal(${woman.id})" class="cursor-pointer hover:bg-gray-100">
                        <td class="p-3 border">${woman.prgname}</td>
                        <td class="p-3 border">${woman.prgage}</td>
                        <td class="p-3 border">${woman.prgaddress}</td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = `<tr><td colspan="3" class="p-3 text-center border">No records found.</td></tr>`;
            }
        });
});

function searchInfants() {
    let input = document.getElementById("infantSearch").value.toLowerCase();
    let rows = document.querySelectorAll("#infantsTableBody tr");

    rows.forEach(row => {
        let name = row.cells[0].textContent.toLowerCase();
        let dob = row.cells[1].textContent.toLowerCase();
        let mother = row.cells[2].textContent.toLowerCase();
        let gender = row.cells[3].textContent.toLowerCase();

        if (name.includes(input) || dob.includes(input) || mother.includes(input) || gender.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
    </script>
</body>
</html>

