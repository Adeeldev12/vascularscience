<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Availability {{ ucfirst($action) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 30px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
            padding: 25px;
        }
        h2 {
            color: #004aad;
            margin-bottom: 15px;
        }
        .info {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        p {
            color: #333;
            margin: 6px 0;
        }
        .footer {
            font-size: 13px;
            color: #777;
            text-align: center;
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Availability {{ ucfirst($action) }}</h2>

    <div class="info">
        <p><strong>Scientist:</strong> {{ $scientist->name ?? 'Unknown Scientist' }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($availability->date)->format('d M Y') }}</p>
        <p><strong>Time:</strong> {{ $availability->start_time }} - {{ $availability->end_time }}</p>
        <p><strong>Status:</strong> {{ ucfirst($availability->status) }}</p>
        @if(!empty($availability->note))
            <p><strong>Note:</strong> {{ $availability->note }}</p>
        @endif
    </div>

    <p>This is an automated message from the <strong>Vascular Science System</strong>.</p>

    <div class="footer">
        © {{ date('Y') }} Vascular Science. All rights reserved.
    </div>
</div>
</body>
</html>
