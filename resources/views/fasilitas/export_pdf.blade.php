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
            font-size: 10pt;
        }
        th {
            text-align: left;
            background-color: #f2f2f2;
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
            border: 1px solid #ddd;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 5px;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 10pt;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-warning {
            background-color: #ffc107;
        }
        .badge-danger {
            background-color: #dc3545;
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

    <h3 class="text-center">LAPORAN DATA FASILITAS</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Kode</th>
                <th>Ruang</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fasilitas as $f)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $f->fasilitas_kode }}</td>
                    <td>{{ $f->ruang->ruang_nama ?? '-' }}</td>
                    <td>{{ $f->kategori->kategori_nama ?? '-' }}</td>
                    <td>{{ $f->deskripsi ?? '-' }}</td>
                    <td class="text-center">
                        @if($f->status == 'baik')
                            <span class="badge badge-success">Baik</span>
                        @elseif($f->status == 'rusak_ringan')
                            <span class="badge badge-warning">Rusak Ringan</span>
                        @else
                            <span class="badge badge-danger">Rusak Berat</span>
                        @endif
                    </td>
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