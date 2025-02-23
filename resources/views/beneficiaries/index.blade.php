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
                <a class="block py-2.5 px-4 hover:bg-blue-800">Pregnant Women Tracking</a>
                <a class="block py-2.5 px-4 hover:bg-blue-800">Babies Immunization</a>
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
                <!-- Pregnant Women Table (Left Side) -->
                <div class="w-1/2 bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-semibold">Pregnant Women</h3>
                        <button onclick="toggleModal()" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                            + Add Pregnant
                        </button>
                    </div>

                    <table class="w-full border-collapse bg-white shadow-lg">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-3 border">Name</th>
                                <th class="p-3 border">Age</th>
                                <th class="p-3 border">Address</th>
                            </tr>
                        </thead>
                        <tbody>
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
                </div>

                <!-- Infants Table (Right Side) -->
                <div class="w-1/2 bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-semibold">Infants</h3>
                        <button onclick="toggleInfantModal()" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                            + Add Infant
                        </button>
                    </div>

                    <table class="w-full border-collapse bg-white shadow-lg">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-3 border">Child's Name</th>
                                <th class="p-3 border">Date of Birth</th>
                                <th class="p-3 border">Mother's Name</th>
                                <th class="p-3 border">Gender</th>
                            </tr>
                        </thead>
                        <tbody>
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
                </div>
            </div>
        </main>
    </div>

    <!-- Pregnant Modal -->
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

                        <label class="block mb-2">Age</label>
                        <input type="number" name="prgage" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Date of Birth</label>
                        <input type="date" name="prgbday" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Address</label>
                        <input type="text" name="prgaddress" class="border p-2 w-full rounded mb-4" required>

                        <label class="block mb-2">Occupation</label>
                        <input type="text" name="prgoccupation" class="border p-2 w-full rounded mb-4">

                        <label class="block mb-2">Religion</label>
                        <input type="text" name="prgreligion" class="border p-2 w-full rounded mb-4">

                        <label class="block mb-2">Mother's Name</label>
                        <input type="text" name="prgmother_name" class="border p-2 w-full rounded mb-4">
                    </div>

                    <!-- Right Side -->
                    <div>
                        <label class="block mb-2">Partner's Name</label>
                        <input type="text" name="partner_name" class="border p-2 w-full rounded mb-4">

                        <label class="block mb-2">Partner's Age</label>
                        <input type="number" name="partner_age" class="border p-2 w-full rounded mb-4">

                        <label class="block mb-2">Partner's Date of Birth</label>
                        <input type="date" name="partner_bday" class="border p-2 w-full rounded mb-4">

                        <label class="block mb-2">Partner's Occupation</label>
                        <input type="text" name="partner_occupation" class="border p-2 w-full rounded mb-4">

                        <label class="block mb-2">Partner's Religion</label>
                        <input type="text" name="partner_religion" class="border p-2 w-full rounded mb-4">

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

<!-- Edit Infant Modal -->
<div id="editInfantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-1/2">
        <h3 class="text-2xl font-bold mb-6">Edit Infant</h3>
        <form id="editInfantForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editInfantId">

            <div class="grid grid-cols-2 gap-6">
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
            @method('PUT') <!-- Use PUT method for updates -->
            <input type="hidden" name="id" id="editId">

            <div class="grid grid-cols-2 gap-6">
                <!-- Left Side -->
                <div>
                    <label class="block mb-2">Full Name</label>
                    <input type="text" name="prgname" id="editPrgname" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Age</label>
                    <input type="number" name="prgage" id="editPrgage" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Date of Birth</label>
                    <input type="date" name="prgbday" id="editPrgbday" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Address</label>
                    <input type="text" name="prgaddress" id="editPrgaddress" class="border p-2 w-full rounded mb-4" required>

                    <label class="block mb-2">Occupation</label>
                    <input type="text" name="prgoccupation" id="editPrgoccupation" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Religion</label>
                    <input type="text" name="prgreligion" id="editPrgreligion" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Mother's Name</label>
                    <input type="text" name="prgmother_name" id="editPrgmotherName" class="border p-2 w-full rounded mb-4">
                </div>

                <!-- Right Side -->
                <div>
                    <label class="block mb-2">Partner's Name</label>
                    <input type="text" name="partner_name" id="editPartnerName" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Age</label>
                    <input type="number" name="partner_age" id="editPartnerAge" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Date of Birth</label>
                    <input type="date" name="partner_bday" id="editPartnerBday" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Occupation</label>
                    <input type="text" name="partner_occupation" id="editPartnerOccupation" class="border p-2 w-full rounded mb-4">

                    <label class="block mb-2">Partner's Religion</label>
                    <input type="text" name="partner_religion" id="editPartnerReligion" class="border p-2 w-full rounded mb-4">

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
        // Fetch the record from the database
        const response = await fetch(`/pregnant/${id}/edit`);
        if (!response.ok) {
            throw new Error('Failed to fetch record');
        }
        const woman = await response.json();

        // Populate the edit modal with the fetched data
        document.getElementById('editId').value = woman.id;
        document.getElementById('editPrgname').value = woman.prgname;
        document.getElementById('editPrgage').value = woman.prgage;
        document.getElementById('editPrgbday').value = woman.prgbday;
        document.getElementById('editPrgaddress').value = woman.prgaddress;
        document.getElementById('editPrgoccupation').value = woman.prgoccupation;
        document.getElementById('editPrgreligion').value = woman.prgreligion;
        document.getElementById('editPrgmotherName').value = woman.prgmother_name;

        // Partner's details
        document.getElementById('editPartnerName').value = woman.partner_name;
        document.getElementById('editPartnerAge').value = woman.partner_age;
        document.getElementById('editPartnerBday').value = woman.partner_bday;
        document.getElementById('editPartnerOccupation').value = woman.partner_occupation;
        document.getElementById('editPartnerReligion').value = woman.partner_religion;
        document.getElementById('editPartnerNumber').value = woman.partner_number;

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
        const response = await fetch(`/infants/${id}/edit`);
        if (!response.ok) throw new Error('Failed to fetch record');
        const infant = await response.json();

        // Populate the edit modal with the fetched data
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

        // Set the form action dynamically
        document.getElementById('editInfantForm').action = `/infants/${infant.id}`;

        // Show the edit modal
        document.getElementById('editInfantModal').classList.remove('hidden');
    } catch (error) {
        console.error('Error fetching infant record:', error);
        alert('Failed to fetch infant record. Please try again.');
    }
}

// Close Modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
    </script>
</body>
</html>