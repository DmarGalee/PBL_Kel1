@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/gedung/import') }}')" class="btn btn-info">Import Gedung</button>
                <a href="{{ url('/gedung/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Gedung</a>
                <a href="{{ url('/gedung/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Gedung</a>
                <button onclick="modalAction('{{ url('/gedung/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Ajax
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="level_id" name="level_id" required>
                                <option value="">- Semua -</option>
                                @foreach($level as $item)
                                    <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_gedung">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Gedung</th>
                        <th>Nama Gedung</th>
                        <th>Waktu Dibuat</th>
                        <th>Waktu Diperbarui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="modal-crud" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url) {
            $("#modal-crud .modal-content").html("");
            $.get(url, function (response) {
                $("#modal-crud .modal-content").html(response);
                $("#modal-crud").modal("show");
            });
        }
        $('#modal-crud').on('hidden.bs.modal', function () {
            $("#modal-crud .modal-content").html("");
        });
        var dataGedung;
        $(document).ready(function () {
                dataGedung = $('#table_gedung').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('gedung/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function (d) {
                        d.level_id = $('#level_id').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "gedung_kode", className: "", orderable: true, searchable: true },
                    { data: "gedung_nama", className: "", orderable: true, searchable: true },
                    { data: "created_at", className: "", orderable: false, searchable: false },
                    { data: "updated_at", className: "", orderable: false, searchable: false },
                    { data: "aksi", className: "", orderable: false, searchable: false }
                ]
            });
            $('#level_id').on('change', function () {
                dataGedung.ajax.reload();
            });
        });
    </script>
@endpush
