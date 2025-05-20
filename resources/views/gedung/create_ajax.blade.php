<form action="{{ url('/gedung/ajax') }}" method="POST" id="form-create"> 
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Gedung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- gedung_kode -->
                <div class="form-group">
                    <label>Kode Gedung</label>
                    <input type="text" name="gedung_kode" id="gedung_kode" class="form-control" required>
                    <small id="error-gedung_kode" class="error-text form-text text-danger"></small>
                </div>
                <!-- gedung_nama -->
                <div class="form-group">
                    <label>Nama Gedung</label>
                    <input type="text" name="gedung_nama" id="gedung_nama" class="form-control" required>
                    <small id="error-gedung_nama" class="error-text form-text text-danger"></small>
                </div>
                <!-- description -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                    <small id="error-description" class="error-text form-text text-danger"></small>
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
        $("#form-create").validate({
            rules: {
                gedung_kode: { required: true, minlength: 3 },
                gedung_nama: { required: true, minlength: 3 },
                description: { required: true, minlength: 5 }
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
                            // Jika kamu menggunakan datatable atau ajax reload
                            if(typeof dataGedung !== 'undefined'){
                                dataGedung.ajax.reload();
                            }
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
