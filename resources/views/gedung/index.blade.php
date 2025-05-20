@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/gedung/import') }}')" class="btn btn-info">Import Gedung</button>
                <a href="{{ url('/gedung/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Gedung Excel</a> 
                <a href="{{ url('/gedung/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Gedung Pdf</a>
                <button onclick="modalAction('{{ url('gedung/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
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
                        <th>Kode Gedung</th>
                        <th>Nama Gedung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
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
