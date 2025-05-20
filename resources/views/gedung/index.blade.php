@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Gedung</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/gedung/import') }}')" class="btn btn-info">Import Gedung</button>
                <a href="{{ url('/gedung/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Gedung (Excel)</a>
                <a href="{{ url('/gedung/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Gedung (PDF)</a>
                <button onclick="modalAction('{{ url('/gedung/create_ajax') }}')" class="btn btn-success">Tambah Data Ajax</button>           
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_gedung" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_gedung" class="form-control form-control-sm filter_gedung">
                                    <option value="">- Semua Gedung -</option>
                                    @foreach($gedung as $g)
                                    <option value="{{ $g->gedung_id }}">{{ $g->gedung_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Gedung</small>
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
            
            <table class="table table-bordered table-sm table-striped table-hover" id="table_gedung">
                <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">Kode Gedung</th>
                        <th style="text-align: center;">Nama Gedung</th>
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
             
    var dataGedung;
    $(document).ready(function() {
        dataGedung = $('#table_gedung').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('gedung/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter_gedung = $('.filter_gedung').val();
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
                    data: "gedung_kode",
                    className: "text-center",
                    width: "15%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "gedung_nama",
                    className: "",
                    width: "40%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "text-center",
                    width: "30%",
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[1, 'asc']] // Urutkan berdasarkan kode gedung
        });

        $('#table_gedung_filter input').unbind().bind().on('keyup', function(e){
            if(e.keyCode == 13){ // enter key
                dataGedung.search(this.value).draw();
            }           
        });

        $('.filter_gedung').change(function(){
            dataGedung.draw();
        });
    });
</script>
@endpush