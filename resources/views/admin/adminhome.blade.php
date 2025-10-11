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
            overflow: hidden; /* Prevent body scroll */
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

            <!-- notifications -->
            @if(Auth::check() && Auth::user()->usertype === 'admin')
            <div class="relative">
                <button id="notifBtn" onclick="toggleNotifMenu()" class="p-2 rounded hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" 
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
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
            <div class="bg-white p-4 rounded shadow">
                <p class="text-xs text-gray-500">Total Beneficiaries</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $totalBeneficiaries ?? 0 }}</h3>
                    <div class="text-sm text-white bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ (Auth::user()->usertype === 'useradmin') ? ucfirst(str_replace('purok','Purok ',Auth::user()->purok)) : 'All' }}</div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Pregnants + Infants</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-xs text-gray-500">Pregnant Women</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $totalPregnants ?? 0 }}</h3>
                    <div class="text-sm text-white bg-pink-100 text-pink-700 px-2 py-1 rounded">Pregnants</div>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ $pregnantBelow18 ?? 0 }} under 18 • {{ $pregnantAbove18 ?? 0 }} 18+</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-xs text-gray-500">Infants</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $totalInfants ?? 0 }}</h3>
                    <div class="text-sm text-white bg-green-100 text-green-700 px-2 py-1 rounded">Infants</div>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ $infantMale ?? 0 }} male • {{ $infantFemale ?? 0 }} female</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-xs text-gray-500">This Month — Top Requests</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $topRequestedMedicines->sum('total_quantity') ?? 0 }}</h3>
                    <div class="text-sm text-white bg-orange-100 text-orange-700 px-2 py-1 rounded">Requested</div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Top 5 medicines (approved)</p>
            </div>
        </section>

        <!-- CHARTS GRID -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pregnant Age Groups (bar) -->
            <div class="bg-white rounded shadow p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold">Pregnant Age Distribution</h4>
                    <small class="text-gray-500">Grouped ages</small>
                </div>
                <div class="h-64">
                    <canvas id="pregnantAgeChart"></canvas>
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

        <!-- Top requested medicines + progress -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold">Top 5 Requested Medicines (This Month)</h4>
                    <small class="text-gray-500">Approved requests</small>
                </div>

                <div class="space-y-3">
                    @forelse($topRequestedMedicines as $item)
                        @php
                            $max = max($topRequestedMedicines->pluck('total_quantity')->toArray() ?: [1]);
                            $pct = $max ? round(($item->total_quantity / $max) * 100) : 0;
                        @endphp

                        <div class="flex items-center justify-between">
                            <div class="w-2/3">
                                <div class="text-sm font-medium text-gray-700">{{ $item->name }}</div>
                                <div class="mt-1 bg-gray-100 h-2 rounded overflow-hidden">
                                    <div class="h-2 rounded" style="width: {{ $pct }}%; background:linear-gradient(90deg,#fb7185,#f97316)"></div>
                                </div>
                            </div>
                            <div class="w-1/3 text-right">
                                <div class="text-sm font-semibold">{{ $item->total_quantity }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500">No approved requests recorded this month.</div>
                    @endforelse
                </div>
            </div>

            <!-- keep the medicine inventory table on the right -->
            <div class="bg-white rounded shadow p-6 overflow-auto">
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

            </div>
        </section>
         @yield('content')
    </main>

    <script>

  function toggleNotifMenu(){
            document.getElementById('notifMenu').classList.toggle('hidden');
        }

        // Pass data from Blade to JS
        const pregnantAgeGroups = @json($pregnantAgeGroups ?? []);
        const infantMale = Number(@json($infantMale ?? 0));
        const infantFemale = Number(@json($infantFemale ?? 0));
        const pregnantsByPurok = @json($pregnantsByPurok ?? []);
        const infantsByPurok = @json($infantsByPurok ?? []);
        const topRequestedMedicines = @json($topRequestedMedicines ?? []);

        // Pregnant Age Chart
        (function(){
            const labels = Object.keys(pregnantAgeGroups);
            const data = Object.values(pregnantAgeGroups);

            const ctx = document.getElementById('pregnantAgeChart').getContext('2d');
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
            const ctx = document.getElementById('infantGenderChart').getContext('2d');
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
            const labels = pregnantsByPurok.map(p => p.purok ?? 'Unknown').map(p => p.replace(/purok/gi, 'Purok '));
            const data = pregnantsByPurok.map(p => Number(p.total ?? 0));

            const ctx = document.getElementById('pregnantsPurokChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pregnants',
                        data,
                        backgroundColor: labels.map((_,i)=>`rgba(99,102,241, ${0.8 - i*0.04})`),
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
            const labels = infantsByPurok.map(p => p.purok ?? 'Unknown').map(p => p.replace(/purok/gi, 'Purok '));
            const data = infantsByPurok.map(p => Number(p.total ?? 0));

            const ctx = document.getElementById('infantsPurokChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Infants',
                        data,
                        backgroundColor: labels.map((_,i)=>`rgba(16,185,129, ${0.85 - i*0.03})`),
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

    </script>
</body>
</html>