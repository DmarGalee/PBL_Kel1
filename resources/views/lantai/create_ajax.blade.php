<form action="{{ url('/lantai/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Lantai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nomor Lantai</label>
                    <input value="" type="text" name="lantai_nomor" id="lantai_nomor" class="form-control" required>
                    <small id="error-lantai_nomor" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi Lantai</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Gedung</label>
                    <select name="gedung_id" id="gedung_id" class="form-control" required>
                        <option value="">Pilih Gedung</option>
                        @foreach ($gedungs as $item)
                            <option value="{{ $item->gedung_id }}">{{ $item->gedung_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-gedung_id" class="error-text form-text text-danger"></small>
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
        $("#form-tambah").validate({
            rules: {
                lantai_nomor: { required: true, minlength: 1, maxlength: 50 },
                deskripsi: { required: true, minlength: 5 },
                gedung_id: { required: true }
            },
            submitHandler: function(form, event) {
                event.preventDefault(); // Mencegah reload halaman
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#form-tambah')[0].reset(); // Reset form setelah sukses
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataLantai.ajax.reload(); // Reload DataTables
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
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>