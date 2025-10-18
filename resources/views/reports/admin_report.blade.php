<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard Report</title>
    <style>
        @page { margin: 60px 40px; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #111827;
            background: #fff;
            font-size: 13px;
            position: relative;
        }

        /* === WATERMARK === */
        body::before {
            content: "";
            position: fixed;
            top: 45%;
            left: 50%;
            width: 300px;
            height: 300px;
            background: url('{{ public_path('images/logo.png') }}') no-repeat center;
            background-size: 60%;
            opacity: 0.07;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        /* === HEADER === */
        .header {
            text-align: center;
            border-bottom: 3px solid #b91c1c;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header img {
            width: 70px;
            height: 70px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 22px;
            color: #b91c1c;
            margin: 0;
        }
        .header h2 {
            font-size: 16px;
            color: #374151;
            margin: 5px 0;
        }
        .header small {
            color: #6b7280;
        }

        /* === SUMMARY CARDS === */
        .summary {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 30px;
        }
        .card {
            flex: 1;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            text-align: center;
            padding: 15px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .card h3 {
            font-size: 13px;
            color: #991b1b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .card p {
            font-size: 26px;
            font-weight: bold;
            color: #111827;
            margin: 0;
        }
        .card small {
            color: #6b7280;
        }

        /* === SECTIONS === */
        .section {
            margin-top: 25px;
        }
        .section h3 {
            font-size: 14px;
            background: #b91c1c;
            color: #fff;
            padding: 6px 10px;
            border-radius: 5px;
        }
        .purok-title {
            font-weight: bold;
            color: #b91c1c;
            margin: 10px 0 5px;
        }

        /* === TABLES === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
        }
        th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
        }
        tr:nth-child(even) td {
            background: #fafafa;
        }

        /* === FOOTER === */
        .footer {
            position: fixed;
            bottom: 25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <!-- HEADER WITH LOGO -->
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Barangay Anolid Logo">
        <h1>Barangay Anolid Health Management System</h1>
        <h2>Admin Dashboard Report</h2>
        <small>Generated on {{ now()->format('F d, Y h:i A') }}</small>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <div class="card">
            <h3>Pregnant Women</h3>
            <p>{{ $totalPregnants }}</p>
            <small>{{ $pregnantBelow18 }} under 18 • {{ $pregnantAbove18 }} 18+</small>
        </div>
        <div class="card">
            <h3>Infants</h3>
            <p>{{ $totalInfants }}</p>
            <small>{{ $infantMale }} male • {{ $infantFemale }} female</small>
        </div>
    </div>

    <!-- PREGNANTS BY PUROK -->
    <div class="section">
        <h3>Pregnant Women by Purok</h3>
        <table>
            <thead>
                <tr>
                    <th>Purok</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pregnantsByPurok as $p)
                <tr>
                    <td>{{ ucfirst(str_replace('purok','Purok ',$p->purok)) }}</td>
                    <td>{{ $p->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- INFANTS BY PUROK -->
    <div class="section">
        <h3>Infants by Purok</h3>
        <table>
            <thead>
                <tr>
                    <th>Purok</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($infantsByPurok as $p)
                <tr>
                    <td>{{ ucfirst(str_replace('purok','Purok ',$p->purok)) }}</td>
                    <td>{{ $p->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- MEDICINES SECTION -->
    <div class="section">
        <h3>Top Requested Medicines by Purok (This Month)</h3>

        @if(isset($topMedicinesByPurok) && count($topMedicinesByPurok) > 0)
            @foreach($topMedicinesByPurok as $purok => $items)
                <p class="purok-title">{{ ucfirst(str_replace('purok','Purok ',$purok)) }}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Total Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items->sortByDesc('total_quantity')->take(5) as $item)
                        <tr>
                            <td>{{ $item->medicine_name }}</td>
                            <td>{{ $item->total_quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <p style="color:#6b7280;">No medicine request data available.</p>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Barangay Anolid Health Management System • {{ now()->format('Y') }} © All Rights Reserved</p>
    </div>

</body>
</html>
