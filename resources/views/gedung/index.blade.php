@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Gedung</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/gedung/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-gedung">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Gedung</th>
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
                if (e.keyCode == 13) { 
                    tableGedung.search(this.value).draw();
                }
            });
        });
    </script>
@endpush
