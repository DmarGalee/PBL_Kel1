@empty($gedung)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
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
                    Data gedung yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/gedung') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Gedung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th width="25%">ID Gedung</th>
                        <td>{{ $gedung->gedung_id }}</td>
                    </tr>
                    <tr>
                        <th>Kode Gedung</th>
                        <td>{{ $gedung->gedung_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Gedung</th>
                        <td>{{ $gedung->gedung_nama }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>
                            @if($gedung->description)
                                {{ $gedung->description }}
                            @else
                                <span class="text-muted">Tidak ada deskripsi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $gedung->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Diupdate Pada</th>
                        <td>{{ $gedung->updated_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty