@empty($fasilitas)
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
                    Data fasilitas yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/fasilitas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Fasilitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="25%">ID Fasilitas</th>
                        <td>{{ $fasilitas->fasilitas_id }}</td>
                    </tr>
                    <tr>
                        <th>Kode Fasilitas</th>
                        <td>{{ $fasilitas->fasilitas_kode }}</td>
                    </tr>
                    <tr>
                        <th>Ruang</th>
                        <td>{{ $fasilitas->ruang->ruang_nama }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $fasilitas->kategori->kategori_nama }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($fasilitas->status == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($fasilitas->status == 'rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                            @else
                                <span class="badge badge-danger">Rusak Berat</span>
                            @endif
                        </td>
                    </tr>
                </table>
                
                <div class="mt-3">
                    <h5>Informasi Tambahan</h5>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Fasilitas ini terdaftar dengan kode <strong>{{ $fasilitas->fasilitas_kode }}</strong> 
                        dan saat ini berstatus 
                        @if($fasilitas->status == 'baik')
                            <span class="text-success">Baik</span>.
                        @elseif($fasilitas->status == 'rusak_ringan')
                            <span class="text-warning">Rusak Ringan</span>.
                        @else
                            <span class="text-danger">Rusak Berat</span>.
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty