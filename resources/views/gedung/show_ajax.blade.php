@empty($gedung)
    <div id="modal-delete" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/gedung') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data Gedung</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-3">Kode Gedung :</th>
                <td class="col-9">{{ $gedung->gedung_kode }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Nama Gedung :</th>
                <td class="col-9">{{ $gedung->gedung_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Waktu Dibuat :</th>
                <td class="col-9">{{ $gedung->created_at }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Waktu Diperbarui :</th>
                <td class="col-9">{{ $gedung->updated_at }}</td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button onclick="modalAction('{{ url('/gedung/' . $gedung->gedung_id . '/edit_ajax') }}')" 
            class="btn btn-success btn-sm">Edit
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
    </div>
@endempty
