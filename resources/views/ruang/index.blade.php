@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Ruang</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/ruang/import') }}')" class="btn btn-info">Import Ruang</button>
                <a href="{{ url('/ruang/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Ruang (Excel)</a>
                <a href="{{ url('/ruang/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Ruang (PDF)</a>
                <button onclick="modalAction('{{ url('/ruang/create_ajax') }}')" class="btn btn-success">Tambah Data Ajax</button>           
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_lantai" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_lantai" class="form-control form-control-sm filter_lantai">
                                    <option value="">- Semua Lantai -</option>
                                    @foreach($lantai as $l)
                                    <option value="{{ $l->lantai_id }}">Lantai {{ $l->lantai_id }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Lantai</small>
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
            
            <table class="table table-bordered table-sm table-striped table-hover" id="table_ruang">
                <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">ID Ruang</th>
                        <th style="text-align: center;">Nama Ruang</th>
                        <th style="text-align: center;">Lantai</th>
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
             
    var dataRuang;
    $(document).ready(function() {
        dataRuang = $('#table_ruang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('ruang/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter_lantai = $('.filter_lantai').val();
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
                    data: "ruang_id",
                    className: "text-center",
                    width: "10%",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "ruang_nama",
                    className: "",
                    width: "60%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "lantai_id",
                    className: "text-center",
                    width: "15%",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return 'Lantai ' + data;
                    }
                },
                {
                    data: "aksi",
                    className: "text-center",
                    width: "10%",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#table_ruang_filter input').unbind().bind().on('keyup', function(e){
            if(e.keyCode == 13){ // enter key
                dataRuang.search(this.value).draw();
            }           
        });

        $('.filter_lantai').change(function(){
            dataRuang.draw();
        });
    });
</script>
@endpush