@php
function getBase64Image($path) {
if (!file_exists($path)) return '';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
return 'data:image/' . $type . ';base64,' . base64_encode($data);
}
function getBase64Font($path) {
if (!file_exists($path)) return '';
$data = file_get_contents($path);
return 'data:font/truetype;base64,' . base64_encode($data);
}

$bgBase64 = getBase64Image(public_path('template/sertifikat_botania_v2.jpeg'));
$fontMontB = getBase64Font(public_path('fonts/Montserrat-ExtraBold.ttf'));
$fontMontSB = getBase64Font(public_path('fonts/Montserrat-SemiBold.ttf'));

// Variabel Posisi
$jenisProgram = strtolower(trim($peserta->jenis_program ?? ''));
$institusi = strtoupper($peserta->institusi ?? 'INSTITUSI');

if (
str_contains($jenisProgram, 'prakerin') ||
str_contains($jenisProgram, 'smk')
) {
$prefix = 'SISWA';
} else {
$prefix = 'MAHASISWA';
}

$posisi = $prefix . ' DARI ' . $institusi;

//Predikat
if ($nilai >= 85) {
$predikat = 'A (Sangat Baik)';
} elseif ($nilai >= 70) {
$predikat = 'B (Baik)';
} elseif ($nilai >= 55) {
$predikat = 'C (Cukup)';
} elseif ($nilai >= 40) {
$predikat = 'D (Kurang)';
} else {
$predikat = 'E (Sangat Kurang)';
}

// Deskripsi Kegiatan (Periode - format lengkap dari database)
$periodeStr = '';
if ($peserta->periode_mulai && $peserta->periode_selesai) {
$start = $peserta->periode_mulai;
$end = $peserta->periode_selesai;
$periodeStr = 'tanggal ' . $start->translatedFormat('d F Y') . ' – ' . $end->translatedFormat('d F Y');
}

// Tanggal terbit
$tglTerbit = \Carbon\Carbon::parse($tanggal)
->translatedFormat('d F Y');

@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sertifikat Magang</title>
    <style>
        @font-face {
            font-family: 'MontserratEB';
            font-weight: 800;
            src: url('{{ $fontMontB }}') format('truetype');
        }

        @font-face {
            font-family: 'MontserratSB';
            font-weight: 600;
            src: url('{{ $fontMontSB }}') format('truetype');
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 297mm;
            height: 210mm;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .sertifikat {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }

        /* Posisi elemen - semua pakai mm untuk presisi DomPDF */
        .no-cert {
            position: absolute;
            z-index: 1;
            top: 2mm;
            right: 15mm;
            font-family: 'MontserratSB', 'Arial', sans-serif;
            font-size: 10pt;
            font-weight: 600;
            color: #111;
        }

        .nama {
            position: absolute;
            z-index: 1;
            top: 63mm;
            left: 0;
            width: 297mm;
            text-align: center;
            font-family: 'MontserratEB', 'Arial', sans-serif;
            font-size: 37pt;
            font-weight: 800;
            color: #000;
            letter-spacing: 1.5px;
        }

        .posisi {
            position: absolute;
            z-index: 1;
            top: 98mm;
            left: 0;
            width: 297mm;
            text-align: center;
            font-family: 'MontserratEB', 'Arial', sans-serif;
            font-size: 20pt;
            font-weight: 800;
            color: #111;
        }

        .deskripsi {
            position: absolute;
            z-index: 1;
            top: 122mm;
            left: 0;
            width: 297mm;
            text-align: center;
            font-family: 'MontserratSB', 'Arial', sans-serif;
            font-size: 15pt;
            font-weight: 600;
            color: #222;
        }

        .hasil {
            position: absolute;
            z-index: 1;
            top: 134mm;
            left: 0;
            width: 297mm;

            text-align: center;

            font-family: 'MontserratSB', Arial, sans-serif;
            font-size: 14pt;
            font-weight: 600;
            line-height: 1.15;
            color: #111;
        }

        .tanggal {
            position: absolute;
            z-index: 1;
            top: 152mm;
            left: 140mm;
            font-size: 13pt;
            color: #111;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="sertifikat">
        <img src="{{ $bgBase64 }}" class="bg" alt="">

        <!-- Nomor saja, karena "No." sudah ada di gambar -->
        <div class="no-cert">{{ $nomor }}</div>

        <div class="nama">{{ strtoupper($peserta->user?->name) }}</div>

        <div class="posisi">{{ $posisi }}</div>

        <!-- Hanya menampilkan tanggal karena teks pengantar sudah ada di gambar -->
        <div class="deskripsi">{{ $periodeStr }}</div>

        <div class="hasil">
            <div>Dengan hasil evaluasi memperoleh Predikat {{ $predikat }}</div>
        </div>

        <!-- Hanya menampilkan tanggal publis karena "Batam," sudah ada di gambar -->
        <div class="tanggal">{{ $tglTerbit }}</div>
    </div>

</body>

</html>