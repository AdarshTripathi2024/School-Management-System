<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Card</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 13px; 
            color: #222; 
        }
        .container {
            width: 100%;
            padding: 10px 25px;
        }
        .school-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .school-header h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .school-header p {
            margin: 2px 0;
            font-size: 14px;
        }
        .title-box {
            text-align: center;
            margin: 12px 0;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
        }
        .info-section {
            width: 100%;
            margin-bottom: 12px;
        }
        .info-table td {
            padding: 4px 8px;
            font-size: 13px;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }
        .marks-table th, .marks-table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }
        .marks-table th {
            background: #e8e8e8;
            font-weight: bold;
        }
        .summary-box {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #444;
            width: 45%;
            font-size: 14px;
        }
        .summary-box p {
            margin: 5px 0;
        }
        .signature-section {
            margin-top: 35px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            margin-top: 35px;
        }
        .signature-table td {
            text-align: center;
            padding-top: 40px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- SCHOOL HEADER -->
    <div class="school-header">
        <h1>School Management System</h1>
        <p>Location, City - ZIP code</p>
        <p>Phone: +91-9876543210 | Email: info@gschoolmanagementsystem.com</p>
    </div>

    <!-- TITLE -->
    <div class="title-box">
        Report Card – {{ $result->exam->exam_name }}
    </div>

    <!-- STUDENT INFORMATION -->
    <table class="info-table" width="100%">
        <tr>
            <td><strong>Student Name:</strong> {{ $result->student->user->name }}</td>
            <td><strong>Class:</strong> {{ $result->class->class_name }}</td>
        </tr>
        <tr>
            <td><strong>Roll No:</strong> {{ $result->student->roll_number ?? '-' }}</td>
            <td><strong>Gender:</strong> {{ strtoupper($result->student->gender) ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Date of Birth:</strong> {{ $result->student->dateofbirth ?? '-' }}</td>
            <td><strong>Address:</strong> {{ $result->student->permanent_address ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Parents:</strong> {{ $result->student->parent->user->name ?? '-' }}</td>
            <td><strong>Admission No:</strong> {{ $result->student->admission_no ?? '-' }}</td>
        </tr>
    </table>

    <!-- MARKS TABLE -->
    <table class="marks-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Theory Max</th>
                <th>Theory Obt.</th>
                <th>Practical Max</th>
                <th>Practical Obt.</th>
                <th>Total Marks</th>
                <th>Obtained</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result->subjectMarks as $mark)
            <tr>
                <td>{{ optional($mark->subject)->name ?? 'N/A' }}</td>
                <td>{{ $mark->theory_total }}</td>
                <td>{{ $mark->obtained_theory }}</td>
                <td>{{ $mark->practical_total }}</td>
                <td>{{ $mark->obtained_practical }}</td>
                <td>{{ $mark->total_marks }}</td>
                <td>{{ $mark->obtained_total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SUMMARY BOX -->
    <div class="summary-box">
        <p><strong>Total Marks:</strong> {{ $result->total }}</p>
        <p><strong>Marks Obtained:</strong> {{ $result->grandtotal }}</p>
        <p><strong>Percentage:</strong> {{ number_format($result->percentage, 2) }}%</p>
        <p><strong>Result:</strong> 
            @if($result->percentage >= 33)
                Pass
            @else
                Fail
            @endif
        </p>
    </div>

    <!-- SIGNATURE SECTION -->
    <table class="signature-table">
        <tr>
            <td>___________________ <br> Class Teacher</td>
            <td>___________________ <br> Principal</td>
        </tr>
    </table>

</div>

</body>
</html>
