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
    <form action="{{ url('/fasilitas/' . $fasilitas->fasilitas_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Fasilitas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Fasilitas</label>
                        <input value="{{ $fasilitas->fasilitas_kode }}" type="text" name="fasilitas_kode" id="fasilitas_kode" 
                               class="form-control" required>
                        <small id="error-fasilitas_kode" class="error-text form-text text-danger"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Ruang</label>
                        <select name="ruang_id" id="ruang_id" class="form-control" required>
                            <option value="">- Pilih Ruang -</option>
                            @foreach($ruang as $r)
                                <option value="{{ $r->ruang_id }}" {{ $fasilitas->ruang_id == $r->ruang_id ? 'selected' : '' }}>
                                    {{ $r->ruang_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-ruang_id" class="error-text form-text text-danger"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                            <option value="">- Pilih Kategori -</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->kategori_id }}" {{ $fasilitas->kategori_id == $k->kategori_id ? 'selected' : '' }}>
                                    {{ $k->kategori_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ $fasilitas->deskripsi }}</textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="baik" {{ $fasilitas->status == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ $fasilitas->status == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ $fasilitas->status == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        <small id="error-status" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $("#form-edit").validate({
                rules: {
                    fasilitas_kode: { 
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    ruang_id: { 
                        required: true 
                    },
                    kategori_id: { 
                        required: true 
                    },
                    status: { 
                        required: true 
                    }
                },
                messages: {
                    fasilitas_kode: {
                        required: "Kode fasilitas harus diisi",
                        minlength: "Kode fasilitas minimal 3 karakter",
                        maxlength: "Kode fasilitas maksimal 20 karakter"
                    },
                    ruang_id: {
                        required: "Ruang harus dipilih"
                    },
                    kategori_id: {
                        required: "Kategori harus dipilih"
                    },
                    status: {
                        required: "Status harus dipilih"
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataFasilitas.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
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
                                text: 'Gagal mengupdate data fasilitas'
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