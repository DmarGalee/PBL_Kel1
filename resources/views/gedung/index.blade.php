@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Gedung</h3>
            <div class="card-tools">
<<<<<<< HEAD
                <button onclick="modalAction('{{ url('/gedung/import') }}')" class="btn btn-info">Import Gedung</button>
                <a href="{{ url('/gedung/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Gedung Excel</a> 
                <a href="{{ url('/gedung/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Gedung Pdf</a>
                <button onclick="modalAction('{{ url('gedung/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>
=======
                <button onclick="modalAction('{{ url('/gedung/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
<<<<<<< HEAD
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_gedung">
                <thead>
                    <tr>
                        <th>NO</th>
=======
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-gedung">
                <thead>
                    <tr>
                        <th>No</th>
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
                        <th>Kode Gedung</th>
                        <th>Nama Gedung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
<<<<<<< HEAD
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
=======
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div> 
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
@endsection

@push('js')
<<<<<<< HEAD
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataGedung;

    $(document).ready(function () {
        dataGedung = $('#table_gedung').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('gedung/list') }}",
                "dataType": "json",
                "type": "POST"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'gedung_kode',
                    className: '',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'gedung_nama',
                    className: '',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'aksi',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#table_gedung_filter input').unbind().bind().on('keyup', function(e){
            if(e.keyCode == 13){
                dataGedung.search(this.value).draw();
            }
        });
    });
</script>
@endpush
=======
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var tableGedung;
        $(document).ready(function () {
            tableGedung = $('#table-gedung').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('gedung/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                    { data: "gedung_kode", name: "gedung_kode" },
                    { data: "gedung_nama", name: "gedung_nama" },
                    {
                        data: "aksi",
                        className: "text-center",
                        width: "14%",
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'asc']] // Urutkan berdasarkan kode gedung
            });

            // Menambahkan event listener untuk pencarian
            $('#table-gedung_filter input').unbind().bind().on('keyup', function (e) {
                if (e.keyCode == 13) { // Tombol enter
                    tableGedung.search(this.value).draw();
                }
            });
        });
    </script>
@endpush
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
