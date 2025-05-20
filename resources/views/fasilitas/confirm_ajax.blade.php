@empty($fasilitas)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="fas fa-ban"></i> Kesalahan!!!</h5>
                    Data fasilitas yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/fasilitas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/fasilitas/' . $fasilitas->fasilitas_id.'/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Fasilitas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data fasilitas berikut?
                    </div>
                    <table class="table-sm table-bordered table-striped">
                        <tr><th class="text-right col-3">ID Fasilitas :</th><td class="col-9">{{ $fasilitas->fasilitas_id }}</td></tr>
                        <tr><th class="text-right col-3">Kode Fasilitas :</th><td class="col-9">{{ $fasilitas->fasilitas_kode }}</td></tr>
                        <tr><th class="text-right col-3">Ruang :</th><td class="col-9">{{ $fasilitas->ruang->ruang_nama ?? '-' }} (ID: {{ $fasilitas->ruang_id }})</td></tr>
                        <tr><th class="text-right col-3">Kategori :</th><td class="col-9">{{ $fasilitas->kategori->kategori_nama ?? '-' }} (ID: {{ $fasilitas->kategori_id }})</td></tr>
                        <tr><th class="text-right col-3">Deskripsi :</th><td class="col-9">{{ $fasilitas->deskripsi ?? '-' }}</td></tr>
                        <tr><th class="text-right col-3">Status :</th><td class="col-9">
                            @if($fasilitas->status == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($fasilitas->status == 'rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                            @else
                                <span class="badge badge-danger">Rusak Berat</span>
                            @endif
                        </td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if(response.status){
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataFasilitas.ajax.reload(); // Ganti dengan nama variabel DataTables Anda
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-'+prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menghapus data fasilitas'
                            });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty