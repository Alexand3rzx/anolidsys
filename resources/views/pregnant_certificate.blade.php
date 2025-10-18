<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maternal Health Certificate</title>
    <style>
        @page { margin: 0; } /* Ensure PDF uses full page */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .certificate {
            border: 8px double #b30000;
            padding: 30px 40px;
            margin: 15px;
            border-radius: 5px;
            text-align: center;
            box-sizing: border-box;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        h1 {
            color: #b30000;
            font-size: 22px;
            margin-bottom: 5px;
        }
        h2 {
            color: #222;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .name {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            color: #b30000;
        }
        p {
            font-size: 13px;
            line-height: 1.5;
            margin: 5px 0;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <img src="{{ public_path('images/logo.png') }}" alt="Barangay Logo" class="logo">
        <h1>Barangay Anolid Health Management System</h1>
        <h2>Maternal Health Certificate</h2>

        <p>This is to certify that</p>
        <p class="name">{{ $woman->prgname }}</p>
        <p>of {{ $woman->prgaddress }}, Purok {{ ucfirst($woman->purok) }}</p>

        <p>has successfully completed all required prenatal checkups and immunizations under the Maternal Health Program of Barangay Anolid.</p>
        <p>Congratulations on maintaining a healthy pregnancy!</p>

        <div class="footer">
            <p><strong>Date Issued:</strong> {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
            <p><strong>Barangay Health Office - Anolid, Mangaldan, Pangasinan</strong></p>
        </div>
    </div>
</body>
</html>
