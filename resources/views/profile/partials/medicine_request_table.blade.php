<!-- Medicines Table -->
<div class="overflow-x-auto">
    <table class="w-full border border-gray-200 rounded-lg">
        <thead class="bg-red-500 text-white">
            <tr>
                <th class="px-4 py-2 text-left">Medicine</th>
                <th class="px-4 py-2 text-left">Details</th>
                <th class="px-4 py-2 text-center">Stock</th>
                <th class="px-4 py-2 text-center">Request Qty</th>
                <th class="px-4 py-2 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($medicines as $medicine)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $medicine->name }}</td>
                    <td class="px-4 py-3">{{ $medicine->details }}</td>
                    <td class="px-4 py-3 text-center">{{ $medicine->stock }}</td>
                    <td class="px-4 py-3 text-center">
                        <input type="number" min="1" max="{{ $medicine->stock }}" 
                               class="w-20 border rounded p-1 text-center"
                               id="qty-{{ $medicine->id }}">
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="submitRequest({{ $medicine->id }})"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            Request
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                        No medicines available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $medicines->links() }}
</div>
