<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
        .info { margin-bottom: 15px; }
    </style>
</head>
<body>

<h2>RAPOR SEMESTER</h2>

<div class="info">
    <p><strong>Nama Siswa:</strong> {{ $student->name }}</p>
    <p><strong>NIS:</strong> {{ $student->student->nis ?? '-' }}</p>
    <p><strong>Kelas:</strong> {{ $student->student->classroom->name ?? '-' }}</p>
</div>

    @foreach($reportCards as $yearId => $reports)
        @php $year = $reports->first()->academicYear; @endphp
        <h3>{{ $year->name }} (Semester {{ $year->semester }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Nilai Formatif</th>
                    <th>PTS</th>
                    <th>PAS</th>
                    <th>Nilai Akhir</th>
                    <th>Predikat</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $r)
                <tr>
                    <td>{{ $r->subject->name }}</td>
                    <td style="text-align:center;">{{ $r->formative_score }}</td>
                    <td style="text-align:center;">{{ $r->mid_term_score }}</td>
                    <td style="text-align:center;">{{ $r->final_term_score }}</td>
                    <td style="text-align:center;"><strong>{{ $r->final_grade }}</strong></td>
                    <td style="text-align:center;">{{ $r->predicate }}</td>
                    <td>{{ $r->comments }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endforeach

<br><br>

<table style="border: none; margin-top:40px;">
    <tr style="border:none;">
        <td style="border:none; width:50%;">
            <p>Wali Kelas</p>
            <br><br><br>
            <p>_______________________</p>
        </td>

        <td style="border:none; width:50%; text-align:right;">
            <p>Kepala Sekolah</p>
            <br><br><br>
            <p>_______________________</p>
        </td>
    </tr>
</table>

</body>
</html>
