@empty($ruang)
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
                    Data ruang yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/ruang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Ruang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="25%">ID Ruang</th>
                        <td>{{ $ruang->ruang_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ruang</th>
                        <td>{{ $ruang->ruang_nama }}</td>
                    </tr>
                    <tr>
                        <th>Lantai</th>
                        <td>Lantai {{ $ruang->lantai_id }}</td>
                    </tr>
                </table>
                
                <!-- Tambahan informasi lain jika diperlukan -->
                <div class="mt-3">
                    <h5>Informasi Tambahan</h5>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Ruang ini terletak di Lantai {{ $ruang->lantai_id }} dengan nama {{ $ruang->ruang_nama }}.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty