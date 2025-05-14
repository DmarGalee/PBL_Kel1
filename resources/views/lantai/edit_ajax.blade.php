@empty($lantai)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/lantai') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/lantai/' . $lantai->lantai_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Lantai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nomor Lantai</label>
                        <input type="text" name="lantai_nomor" id="lantai_nomor" class="form-control"
                            value="{{ $lantai->lantai_nomor }}" required maxlength="50">
                        <small id="error-lantai_nomor" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control">{{ $lantai->deskripsi }}</textarea>
                        <small id="error-deskripsi" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Gedung</label>
                        <select name="gedung_id" id="gedung_id" class="form-control" required>
                            <option value="">Pilih Gedung</option>
                            @foreach ($gedungs as $item)
                                <option value="{{ $item->gedung_id }}" {{ $lantai->gedung_id == $item->gedung_id ? 'selected' : '' }}>
                                    {{ $item->gedung_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-gedung_id" class="error-text text-danger"></small>
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
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    lantai_nomor: { required: true, minlength: 3, maxlength: 50 },
                    deskripsi: { minlength: 5 },
                    gedung_id: { required: true }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();

                    $.ajax({
                        url: form.action,
                        type: "POST",
                        data: $(form).serialize() + "&_method=PUT",
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                if (typeof dataLantai !== 'undefined' && typeof dataLantai.ajax !== 'undefined') {
                                    dataLantai.ajax.reload();
                                }
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
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
                                text: 'Gagal memperbarui data, periksa kembali inputan Anda.'
                            });
                        }
                    });

                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endif