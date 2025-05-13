@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/periode/import') }}')" class="btn btn-info">Import Periode</button>
                <a href="{{ url('/periode/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Periode</a> 
                <a href="{{ url('/periode/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Periode</a>
                <button onclick="modalAction('{{ url('periode/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_periode">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Periode</th>
                        <th>Nama Periode</th>
                        <th>Status</th>
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
    var dataPeriode;
        $(document).ready(function () {
             dataPeriode = $('#table_periode').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('periode/list') }}",
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
                        data: 'periode_id',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'periode_name',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'is_active',
                        className: 'text-center',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        className: '',
                        orderable: false,
                        searchable: false
                    }]
            });

        $('#table_periode_filter input').unbind().bind().on('keyup', function(e){
        if(e.keyCode == 13){ // enter key
            dataPeriode.search(this.value).draw();
        }
    });
});
</script>
@endpush