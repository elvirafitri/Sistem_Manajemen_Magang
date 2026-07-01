<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Penilaian Magang - {{ $profile->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #00B1C0;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #00B1C0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 13px;
        }

        .info-table .label {
            width: 150px;
            font-weight: bold;
            color: #555;
        }

        .scores-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .scores-table th,
        .scores-table td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: left;
            font-size: 13px;
        }

        .scores-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: bold;
        }

        .scores-table .text-center {
            text-align: center;
        }

        .scores-table .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }

        .footer p {
            margin: 3px 0;
            font-size: 13px;
        }

        .signature-box {
            display: inline-block;
            text-align: left;
            width: 250px;
            margin-top: 10px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 60px;
            margin-bottom: 5px;
        }

        .comment-box {
            border: 1px solid #ddd;
            background-color: #fafafa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .comment-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }

        .legend-box {
            margin-top: 15px;
            font-size: 11px;
            color: #555;
        }

        .legend-box table {
            border-collapse: collapse;
            width: 100%;
            max-width: 250px;
        }

        .legend-box th,
        .legend-box td {
            border: 1px solid #ddd;
            padding: 3px 5px;
            text-align: left;
        }

        .legend-box th {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo-rs-awalbros.png') }}" alt="Logo RS Awal Bros" style="height: 50px; margin-bottom: 5px;">
        <h1>Laporan Penilaian Magang</h1>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Peserta</td>
            <td>: {{ $profile->user->name }}</td>
            <td class="label">Nama Pembimbing</td>
            <td>: {{ $evaluation->pembimbingProfile->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td>: {{ $profile->nim }}</td>
            <td class="label">Tanggal Penilaian</td>
            <td>: {{ $evaluation->finalized_at ? $evaluation->finalized_at->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Institusi/Universitas</td>
            <td colspan="3">: {{ $profile->institusi ?? '-' }}</td>
        </tr>
    </table>

    <table class="scores-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 55%">Aspek Penilaian</th>
                <th class="text-center" style="width: 20%">Nilai Angka</th>
                <th class="text-center" style="width: 20%">Nilai Huruf (Predikat)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluation->rubricScores as $index => $score)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $score->rubric->nama }}</td>
                <td class="text-center">{{ $score->nilai }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $score->predikat }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-right" style="font-weight: bold; padding-right: 15px;">TOTAL NILAI AKHIR (RATA-RATA)</td>
                <td class="text-center" style="font-weight: bold; font-size: 14px;">{{ $evaluation->total_nilai }}</td>
                <td class="text-center" style="font-weight: bold; font-size: 14px; color: #00B1C0;">{{ $evaluation->predikat }}</td>
            </tr>
        </tbody>
    </table>

    <div style="display: table; width: 100%;">
        <div style="display: table-cell; vertical-align: top; width: 60%; padding-right: 20px;">
            <div class="comment-box" style="margin-bottom: 0;">
                <div class="comment-title">Komentar & Umpan Balik Pembimbing:</div>
                <p style="margin: 0; min-height: 50px;">{{ $evaluation->komentar_final ?: 'Tidak ada komentar.' }}</p>
            </div>
        </div>
        <div style="display: table-cell; vertical-align: top; width: 40%;">
            <div class="legend-box" style="margin-top: 0;">
                <strong>Keterangan Nilai (Predikat):</strong>
                <table style="margin-top: 5px;">
                    <tr>
                        <th style="width: 30px;">Huruf</th>
                        <th style="width: 80px;">Rentang</th>
                        <th>Kategori</th>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: center;">A</td>
                        <td>85 - 100</td>
                        <td>Sangat Baik</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: center;">B</td>
                        <td>70 - 84.99</td>
                        <td>Baik</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: center;">C</td>
                        <td>55 - 69.99</td>
                        <td>Cukup</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: center;">D</td>
                        <td>40 - 54.99</td>
                        <td>Kurang</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: center;">E</td>
                        <td>0 - 39.99</td>
                        <td>Gagal</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>Batam, {{ $evaluation->finalized_at ? $evaluation->finalized_at->format('d F Y') : date('d F Y') }}</p>
            <p>Pembimbing Magang,</p>
            <div class="signature-line"></div>
            <p style="font-weight: bold;">{{ $evaluation->pembimbingProfile->user->name ?? '-' }}</p>
        </div>
    </div>

</body>

</html>