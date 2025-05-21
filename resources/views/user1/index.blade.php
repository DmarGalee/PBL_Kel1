@extends('layouts1.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-info">Import User</button>
                <a href="{{ url('/user/export_excel') }}" class="btn btn-primary"><i class="bi bi-file-excel"></i> Export
                    User</a>
                <a href="{{ url('/user/export_pdf') }}" class="btn btn-warning"><i class="bi bi-file-pdf"></i> Export
                    User</a>
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-success">
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

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table_user">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Level Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="modal-crud" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('css')
    <!-- Add any additional CSS here -->
    <link rel="stylesheet"
        href="{{ asset('dist/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/css/pages/datatables.css') }}">
@endpush

@push('js')
    <!-- Add DataTables and other JS dependencies -->
    <script src="{{ asset('dist/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        function modalAction(url) {
            $.get(url, function (response) {
                // Clear and set modal content
                var modalContent = document.querySelector('#modal-crud .modal-content');
                modalContent.innerHTML = response;

                // Initialize the modal
                var modalEl = document.getElementById('modal-crud');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
        }

        $('#modal-crud').on('hidden.bs.modal', function () {
            $("#modal-crud .modal-content").html("");
        });

        var dataUser;
        $(document).ready(function () {
            dataUser = $('#table_user').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('user/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function (d) {
                        d.level_id = $('#level_id').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "username", className: "", orderable: true, searchable: true },
                    { data: "nama", className: "", orderable: true, searchable: true },
                    { data: "level.level_nama", className: "", orderable: false, searchable: false },
                    { data: "aksi", className: "", orderable: false, searchable: false }
                ]
            });

            $('#level_id').on('change', function () {
                dataUser.ajax.reload();
            });
        });

        // Initialize tooltips and popovers
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>
@endpush