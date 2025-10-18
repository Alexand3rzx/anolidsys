<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>BAHMS — Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body {
            background: #f8fafc;
            color: #0f172a;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            /* allow page scrolling */
        }
        input, select, textarea {
            background: #ffffff;
            border: 1px solid #e6edf3;
            padding: .5rem;
            border-radius: .375rem;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
            border-color: #3b82f6;
        }

        /* Fix sidebar and scroll main only */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem; /* 64 in Tailwind */
            display: flex;
            flex-direction: column;
            z-index: 40;
        }

        .main-content {
            margin-left: 16rem; /* exactly matches sidebar width */
            width: calc(100% - 16rem); /* fill remaining horizontal space */
            height: 100vh;
            overflow-y: auto;
            padding: 1.5rem;
        }

        /* Small helper to keep notification dropdown above everything */
        #notifMenu {
            min-width: 20rem;
        }
    </style>
</head>
<body class="flex">

   <!-- Sidebar -->
    <aside class="sidebar bg-gradient-to-b from-red-300 via-red-500 to-red-800 text-white">
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

    <!-- MAIN -->
    <main class="main-content">
        {{-- Provide safe defaults so view doesn't break if controller didn't pass these --}}
        @php
            $notifications = $notifications ?? collect();
            $unreadCount = $unreadCount ?? 0;
        @endphp

        <header class="flex items-center justify-between mb-6">
            <div>
                {{-- Dynamic title: show Purok X Dashboard for useradmin, Admin Dashboard for admin --}}
                @if(Auth::check() && Auth::user()->usertype === 'useradmin')
                    <h2 class="text-2xl font-bold">{{ ucfirst(str_replace('purok', 'Purok ', Auth::user()->purok)) }} Dashboard <span class="text-sm font-normal text-gray-500">(User Admin)</span></h2>
                    <p class="text-sm text-gray-500">Showing analytics for {{ ucfirst(str_replace('purok', 'Purok ', Auth::user()->purok)) }} only.</p>
                @else
                    <h2 class="text-2xl font-bold">Admin Dashboard</h2>
                    <p class="text-sm text-gray-500">Overview & analytics</p>
                @endif
                
            </div>
            @if(Auth::check() && Auth::user()->usertype === 'admin')
    <a href="{{ route('admin.report.download') }}" 
       class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
       Download PDF Report
    </a>
@endif

            <!-- notifications (header-right) -->
            @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'useradmin']))
            <div class="relative">
                <button id="notifBtn" onclick="toggleNotifMenu()" class="relative p-2 rounded hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" 
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>

                    <!-- 🔴 Red badge counter -->
                    @if($unreadCount > 0)
                        <span id="notifBadge" class="absolute top-0 right-0 inline-flex items-center justify-center 
                                     w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full transform translate-x-1 -translate-y-1">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <div id="notifMenu" class="hidden absolute right-0 mt-2 w-80 bg-white border rounded shadow z-50">
                    <div class="px-4 py-2 font-semibold text-gray-700 border-b">Notifications</div>
                    <div class="max-h-64 overflow-auto">
                        @forelse($notifications as $notif)
                            <div class="px-4 py-2 border-b last:border-b-0 {{ $notif->is_read ? 'text-gray-500' : '' }}">
                                <div class="text-sm">{{ $notif->message }}</div>
                                <small class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-400">No notifications</div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </header>

       <!-- SUMMARY CARDS -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Beneficiaries -->
    <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
        <p class="text-xs text-gray-500">Total Beneficiaries</p>
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalBeneficiaries ?? 0 }}</h3>
            <div class="text-sm font-medium px-2 py-1 rounded-lg bg-blue-500 text-white shadow-sm">
                {{ (Auth::user()->usertype === 'useradmin') ? ucfirst(str_replace('purok','Purok ',Auth::user()->purok)) : 'All' }}
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">Pregnants + Infants</p>
    </div>

    <!-- Pregnant Women -->
    <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
        <p class="text-xs text-gray-500">Pregnant Women</p>
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalPregnants ?? 0 }}</h3>
            <div class="text-sm font-medium px-2 py-1 rounded-lg bg-pink-500 text-white shadow-sm">
                Pregnants
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">{{ $pregnantBelow18 ?? 0 }} under 18 • {{ $pregnantAbove18 ?? 0 }} 18+</p>
    </div>

    <!-- Infants -->
    <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
        <p class="text-xs text-gray-500">Infants</p>
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalInfants ?? 0 }}</h3>
            <div class="text-sm font-medium px-2 py-1 rounded-lg bg-green-500 text-white shadow-sm">
                Infants
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">{{ $infantMale ?? 0 }} male • {{ $infantFemale ?? 0 }} female</p>
    </div>
</section>


        @if(isset($insights) && count($insights) > 0)
<section class="bg-gradient-to-r from-indigo-50 via-white to-pink-50 border border-indigo-100 rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-lg font-semibold mb-3 text-gray-800 flex items-center gap-2">
        🧠 System Insights
    </h3>
    <div class="space-y-2">
        @foreach($insights as $tip)
            <div class="flex items-start gap-2 bg-white rounded-lg p-3 shadow-sm border border-gray-100 hover:shadow-md transition">
                <span class="text-indigo-500 text-lg">🧩</span>
                <p class="text-sm text-gray-700">{{ $tip }}</p>
            </div>
        @endforeach
    </div>
</section>
@endif

        <!-- CHARTS GRID -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
           <!-- Pregnant Monthly Check-ins -->
<div class="bg-white rounded shadow p-6">
    <div class="flex items-center justify-between mb-3">
        <h4 class="font-semibold">Pregnant Check-ins Tracker</h4>
        <small class="text-gray-500">Monthly registrations this year</small>
    </div>

    <div class="text-center mb-4">
        <h2 class="text-3xl font-bold text-pink-600">
            {{ $pregnantsThisMonth }}
        </h2>
        <p class="text-gray-500">pregnant women checked in this month</p>
    </div>

    <div class="h-64">
        <canvas id="pregnantCheckinChart"></canvas>
    </div>
</div>


            <!-- Infant Gender (doughnut) -->
            <div class="bg-white rounded shadow p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold">Infant Gender Breakdown</h4>
                    <small class="text-gray-500">Male vs Female</small>
                </div>
                <div class="flex items-center gap-6">
                    <div class="w-48 h-48">
                        <canvas id="infantGenderChart"></canvas>
                    </div>
                    <div>
                        <ul class="text-sm space-y-2">
                            <li class="flex justify-between"><span>Male</span><strong>{{ $infantMale ?? 0 }}</strong></li>
                            <li class="flex justify-between"><span>Female</span><strong>{{ $infantFemale ?? 0 }}</strong></li>
                            <li class="flex justify-between"><span>Total</span><strong>{{ ($infantMale ?? 0) + ($infantFemale ?? 0) }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Pregnants per Purok (bar) -->
            <div class="bg-white rounded shadow p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold">Pregnants by Purok</h4>
                    <small class="text-gray-500">Geographic distribution</small>
                </div>
                <div class="h-64">
                    <canvas id="pregnantsPurokChart"></canvas>
                </div>
            </div>

            <!-- Infants per Purok (bar) -->
            <div class="bg-white rounded shadow p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold">Infants by Purok</h4>
                    <small class="text-gray-500">Geographic distribution</small>
                </div>
                <div class="h-64">
                    <canvas id="infantsPurokChart"></canvas>
                </div>
            </div>
        </section>

        <!-- TWO-COLUMN: Left = Top requested medicines, Right = Medicine Inventory -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- LEFT: Top requested medicines (spans 2 cols on lg) -->
            <div class="lg:col-span-2">
                @if(isset($topMedicinesByPurok) && Auth::user()->usertype === 'admin')
                <div class="bg-white rounded shadow p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold">Top Requested Medicines by Purok (This Month)</h4>
                        <small class="text-gray-500">Completed requests</small>
                    </div>

                    @if(empty($topMedicinesByPurok) || count($topMedicinesByPurok) === 0)
                        <div class="text-gray-500">No data available for this month.</div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($topMedicinesByPurok as $purok => $items)
                            <div class="bg-gray-50 p-5 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h5 class="font-semibold text-pink-600 mb-3">
                                    {{ ucfirst(str_replace('purok','Purok ',$purok)) }}
                                </h5>

                                <div class="space-y-2">
                                    @foreach($items->take(3) as $item)
                                        <div class="flex justify-between items-center">
                                            <div class="text-gray-700 font-medium text-sm">{{ $item->name }}</div>
                                            <div class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                {{ $item->total_quantity }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($items->count() > 3)
                                <div class="text-xs text-gray-400 mt-2 italic">
                                    +{{ $items->count() - 3 }} more medicines
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif

                <!-- You can add other wide content here if needed -->
            </div>

            <!-- RIGHT: Medicine inventory -->
            <aside class="bg-white rounded shadow p-6 overflow-auto">
                <h4 class="font-semibold mb-4">Medicine Inventory (Sample)</h4>

                <!-- Purok filter -->
                @if(Auth::check() && Auth::user()->usertype === 'admin')
                    <form method="GET" action="{{ route('home') }}" class="mb-4">
                        <label class="text-xs text-gray-500">Filter by Purok</label>
                        <select name="purok" onchange="this.form.submit()" class="mt-1 w-full">
                            <option value="">All</option>
                            @foreach($puroks as $p)
                                <option value="{{ $p }}" {{ isset($selectedPurok) && $selectedPurok == $p ? 'selected' : '' }}>{{ ucfirst(str_replace('purok','Purok ',$p)) }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="text-left py-2">Medicine</th>
                                <th class="text-left py-2">Purok</th>
                                <th class="text-left py-2">Stock</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $m)
                                <tr class="border-t">
                                    <td class="py-2">{{ $m->name }}</td>
                                    <td class="py-2">{{ ucfirst(str_replace('purok','Purok ',$m->purok)) }}</td>
                                    <td class="py-2">{{ $m->stock }}</td>
                                    <td class="py-2">
                                        @if($m->stock <= 0)
                                            <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">Out</span>
                                        @elseif($m->stock < 20)
                                            <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Low</span>
                                        @else
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Available</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </aside>
        </section>

        @yield('content')
    </main>

    <script>
        // Toggle notifications dropdown and mark as read
        function toggleNotifMenu() {
            const menu = document.getElementById('notifMenu');
            menu.classList.toggle('hidden');

            // If just opened, mark notifications as read
            if (!menu.classList.contains('hidden')) {
                fetch("{{ route('notifications.markAllRead') }}")
                    .then(response => {
                        if (response.ok) {
                            const badge = document.querySelector('#notifBtn #notifBadge') || document.querySelector('#notifBtn span');
                            if (badge) badge.remove();
                        }
                    })
                    .catch(err => {
                        console.error('Failed to mark notifications read', err);
                    });
            }
        }

        // Chart code and data initialization run on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function () {
            // Pass data from Blade to JS
            const pregnantAgeGroups = @json($pregnantAgeGroups ?? []);
            const infantMale = Number(@json($infantMale ?? 0));
            const infantFemale = Number(@json($infantFemale ?? 0));
            const pregnantsByPurok = @json($pregnantsByPurok ?? []);
            const infantsByPurok = @json($infantsByPurok ?? []);
            const topRequestedMedicines = @json($topRequestedMedicines ?? []);
            const topMedicinesByPurok = @json($topMedicinesByPurok ?? []);
            const isUserAdmin = @json(Auth::check() && Auth::user()->usertype === 'useradmin');

            // Utility: safe get canvas context
            function getCtx(id) {
                const el = document.getElementById(id);
                return el ? el.getContext('2d') : null;
            }

            // Pregnant Age Chart
            (function(){
                const ctx = getCtx('pregnantAgeChart');
                if(!ctx) return;

                const labels = Object.keys(pregnantAgeGroups);
                const data = Object.values(pregnantAgeGroups);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Pregnants',
                            data,
                            backgroundColor: ['#fb7185','#f97316','#f59e0b','#60a5fa'],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive:true,
                        maintainAspectRatio:false,
                        plugins: { legend: { display:false } },
                        scales: {
                            x: { ticks: { color: '#334155' } },
                            y: { ticks: { color: '#334155', beginAtZero:true } }
                        }
                    }
                });
            })();

            // Infant Gender Chart (doughnut)
            (function(){
                const ctx = getCtx('infantGenderChart');
                if(!ctx) return;

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Male','Female'],
                        datasets: [{
                            data: [infantMale, infantFemale],
                            backgroundColor: ['#60a5fa','#f973a1']
                        }]
                    },
                    options: {
                        responsive:true,
                        maintainAspectRatio:false,
                        plugins: { legend: { position:'bottom' } }
                    }
                });
            })();

            // Pregnants by Purok (bar)
            (function(){
                const ctx = getCtx('pregnantsPurokChart');
                if(!ctx) return;

                const labels = pregnantsByPurok.map(p => (p.purok ?? 'Unknown').toString().replace(/purok/gi, 'Purok '));
                const data = pregnantsByPurok.map(p => Number(p.total ?? 0));

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Pregnants',
                            data,
                            backgroundColor: labels.map((_,i)=>`rgba(99,102,241, ${Math.max(0.25, 0.85 - i*0.05)})`),
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive:true,
                        maintainAspectRatio:false,
                        plugins: { legend: { display:false } },
                        scales: {
                            x: { ticks: { color:'#334155' } },
                            y: { ticks: { color:'#334155', beginAtZero:true } }
                        }
                    }
                });
            })();

            // Infants by Purok (bar)
            (function(){
                const ctx = getCtx('infantsPurokChart');
                if(!ctx) return;

                const labels = infantsByPurok.map(p => (p.purok ?? 'Unknown').toString().replace(/purok/gi, 'Purok '));
                const data = infantsByPurok.map(p => Number(p.total ?? 0));

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Infants',
                            data,
                            backgroundColor: labels.map((_,i)=>`rgba(16,185,129, ${Math.max(0.25, 0.85 - i*0.03)})`),
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive:true,
                        maintainAspectRatio:false,
                        plugins: { legend: { display:false } },
                        scales: {
                            x: { ticks: { color:'#334155' } },
                            y: { ticks: { color:'#334155', beginAtZero:true } }
                        }
                    }
                });
            })();

        }); // end DOMContentLoaded

        document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('pregnantCheckinChart').getContext('2d');
    const data = @json($pregnantMonthlyCounts ?? []);
    const labels = Object.keys(data);
    const values = Object.values(data);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Pregnant Check-ins',
                data: values,
                backgroundColor: '#ec4899',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
    </script>

</body>
</html>
