@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Fasilitas</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/fasilitas/import') }}')" class="btn btn-info">Import Fasilitas</button>
                <a href="{{ url('/fasilitas/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export (Excel)</a>
                <a href="{{ url('/fasilitas/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
                <button onclick="modalAction('{{ url('/fasilitas/create_ajax') }}')" class="btn btn-success">Tambah Data</button>           
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_ruang" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_ruang" class="form-control form-control-sm filter_ruang">
                                    <option value="">- Semua Ruang -</option>
                                    @foreach($ruang as $r)
                                    <option value="{{ $r->ruang_id }}">{{ $r->ruang_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Ruang</small>
                            </div>
                            <div class="col-md-3">
                                <select name="filter_kategori" class="form-control form-control-sm filter_kategori">
                                    <option value="">- Semua Kategori -</option>
                                    @foreach($kategori as $k)
                                    <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori</small>
                            </div>
                            <div class="col-md-3">
                                <select name="filter_status" class="form-control form-control-sm filter_status">
                                    <option value="">- Semua Status -</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                                <small class="form-text text-muted">Status</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-sm table-striped table-hover" id="table_fasilitas">
                <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">Kode</th>
                        <th style="text-align: center;">Ruang</th>
                        <th style="text-align: center;">Kategori</th>
                        <th style="text-align: center;">Deskripsi</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
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
    function modalAction(url = ''){
        $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
    }
             
    var dataFasilitas;
    $(document).ready(function() {
        dataFasilitas = $('#table_fasilitas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('fasilitas/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter_ruang = $('.filter_ruang').val();
                    d.filter_kategori = $('.filter_kategori').val();
                    d.filter_status = $('.filter_status').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "5%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "fasilitas_kode",
                    className: "text-center",
                    width: "10%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "ruang.ruang_nama",
                    className: "",
                    width: "15%",
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "kategori.kategori_nama",
                    className: "",
                    width: "15%",
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "deskripsi",
                    className: "",
                    width: "25%",
                    orderable: true,
                    searchable: true,
                    render: function(data) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "status",
                    className: "text-center",
                    width: "10%",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        if(data == 'baik') {
                            return '<span class="badge badge-success">Baik</span>';
                        } else if(data == 'rusak_ringan') {
                            return '<span class="badge badge-warning">Rusak Ringan</span>';
                        } else {
                            return '<span class="badge badge-danger">Rusak Berat</span>';
                        }
                    }
                },
                {
                    data: "aksi",
                    className: "text-center",
                    width: "20%",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <button onclick="modalAction('${row.show_url}')" class="btn btn-sm btn-info">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button onclick="modalAction('${row.edit_url}')" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button onclick="modalAction('${row.delete_url}')" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ]
        });

        $('#table_fasilitas_filter input').unbind().bind().on('keyup', function(e){
            if(e.keyCode == 13){ // enter key
                dataFasilitas.search(this.value).draw();
            }           
        });

        $('.filter_ruang, .filter_kategori, .filter_status').change(function(){
            dataFasilitas.draw();
        });
    });
</script>
@endpush