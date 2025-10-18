<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Infant Vaccination Certificate</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #fff;
            margin: 0;
            color: #333;
        }
        .certificate {
            border: 8px double #b30000;
            padding: 20px 30px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 8px;
        }
        h1 {
            color: #b30000;
            font-size: 20px;
            margin-bottom: 3px;
        }
        h2 {
            color: #222;
            font-size: 16px;
            margin-bottom: 15px;
        }
        p {
            font-size: 12px;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #999;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f9f2f2;
            color: #b30000;
        }
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" alt="Barangay Logo" class="logo">
            <h1>Barangay Anolid Health Management System</h1>
            <h2>Infant Vaccination Certificate</h2>
        </div>

        <p>This is to certify that the following infant has received the vaccinations as recorded below:</p>

        <p>
            <strong>Child's Name:</strong> {{ $infant->child_name }}<br>
            <strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($infant->child_bday)->format('F d, Y') }}<br>
            <strong>Mother's Name:</strong> {{ $infant->child_mother }}<br>
            <strong>Father's Name:</strong> {{ $infant->child_father }}<br>
            <strong>Address:</strong> {{ $infant->child_address }}, {{ ucfirst($infant->purok) }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Vaccine</th>
                    <th>Dose(s)</th>
                    <th>Date(s) Given</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>BCG</td><td>1</td><td>{{ $infant->immunization->bcg_date ?? '—' }}</td></tr>
                <tr><td>Hepatitis B</td><td>1</td><td>{{ $infant->immunization->hepatitis_b_date ?? '—' }}</td></tr>
                <tr><td>Pentavalent</td><td>3</td><td>
                    {{ $infant->immunization->pentavalent_date_1 ?? '—' }},
                    {{ $infant->immunization->pentavalent_date_2 ?? '—' }},
                    {{ $infant->immunization->pentavalent_date_3 ?? '—' }}
                </td></tr>
                <tr><td>OPV</td><td>3</td><td>
                    {{ $infant->immunization->opv_date_1 ?? '—' }},
                    {{ $infant->immunization->opv_date_2 ?? '—' }},
                    {{ $infant->immunization->opv_date_3 ?? '—' }}
                </td></tr>
                <tr><td>IPV</td><td>2</td><td>
                    {{ $infant->immunization->ipv_date_1 ?? '—' }},
                    {{ $infant->immunization->ipv_date_2 ?? '—' }}
                </td></tr>
                <tr><td>PCV</td><td>3</td><td>
                    {{ $infant->immunization->pcv_date_1 ?? '—' }},
                    {{ $infant->immunization->pcv_date_2 ?? '—' }},
                    {{ $infant->immunization->pcv_date_3 ?? '—' }}
                </td></tr>
                <tr><td>MMR</td><td>2</td><td>
                    {{ $infant->immunization->mmr_date_1 ?? '—' }},
                    {{ $infant->immunization->mmr_date_2 ?? '—' }}
                </td></tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Issued on {{ now()->format('F d, Y') }}</p>
            <p>_________________________<br>Barangay Health Worker</p>
        </div>
    </div>
</body>
</html>
