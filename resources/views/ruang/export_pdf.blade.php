<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
        }
        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1 {
            padding: 5px 1px 5px 1px;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                @php
                    $imagePath = public_path('logopolinema.webp');
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $src = 'data: ' . mime_content_type($imagePath) . ';base64,' . $imageData;
                @endphp
                <img src="{{ $src }}" width="100">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101- 105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA RUANG</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">ID Ruang</th>
                <th class="text-center">Nama Ruang</th>
                <th class="text-center">Lantai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ruang as $r)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $r->ruang_id }}</td>
                    <td>{{ $r->ruang_nama }}</td>
                    <td class="text-center">Lantai {{ $r->lantai_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 30px; float: right; text-align: center; width: 200px;">
        <div>Malang, {{ date('d F Y') }}</div>
        <div style="margin-top: 60px;">(_______________________)</div>
    </div>
</body>
</html>