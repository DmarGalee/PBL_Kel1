@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Lantai</h3>
            <div class="card-tools">
                <a href="{{ url('/lantai/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Lantai</a> 
                <button onclick="modalAction('{{ url('/lantai/import') }}')" class="btn btn-info">Import Lantai</button>
                <a href="{{ url('/lantai/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Lantai</a>
                <button onclick="modalAction('{{ url('/lantai/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_gedung" class="form-control form-control-sm filter_gedung">
                                    <option value="">- Semua -</option>
                                    @foreach($gedungs as $g)
                                        <option value="{{ $g->gedung_id }}">{{ $g->gedung_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Gedung</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-lantai">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Lantai</th>
                        <th>Deskripsi</th>
                        <th>Nama Gedung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div> 
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var tableLantai;
        $(document).ready(function () {
            tableLantai = $('#table-lantai').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('lantai/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.filter_gedung = $('.filter_gedung').val();
                    }
                },
                columns: [
    {data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false},
    {data: "lantai_nomor", name: "lantai_nomor"},
    {data: "deskripsi", name: "deskripsi"},
    {data: "nama_gedung", name: "gedung.gedung_nama"}, // Menggunakan nama_gedung dari addColumn
    {
        data: "aksi",
        className: "text-center",
        width: "14%",
        orderable: false,
        searchable: false
    }
],
columnDefs: [
    {
        targets: 3,
        render: function (data, type, row) {
            return row.nama_gedung; // Sesuaikan dengan addColumn
        },
        name: 'gedung.gedung_nama'
    }
],
                order: [[1, 'asc']], // Urutkan berdasarkan nomor lantai
            });

            // Menambahkan event listener untuk pencarian
            $('#table-lantai_filter input').unbind().bind().on('keyup', function (e) {
                if (e.keyCode == 13) { // Tombol enter
                    tableLantai.search(this.value).draw();
                }
            });

            // Menambahkan event listener untuk filter gedung
            $('.filter_gedung').change(function () {
                tableLantai.draw();
            });
        });
    </script>
@endpush