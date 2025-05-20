<form action="{{ url('/gedung/ajax') }}" method="POST" id="form-create"> 
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Gedung</h5>
<<<<<<< HEAD
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
=======
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
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
<<<<<<< HEAD
                <!-- description -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                    <small id="error-description" class="error-text form-text text-danger"></small>
                </div>
=======
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
<<<<<<< HEAD
    $(document).ready(function () {
        $("#form-create").validate({
            rules: {
                gedung_kode: { required: true, minlength: 3 },
                gedung_nama: { required: true, minlength: 3 },
                description: { required: true, minlength: 5 }
            },

            submitHandler: function (form) {
=======
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                gedung_kode: { required: true, minlength: 2, maxlength: 50 },
                gedung_nama: { required: true, minlength: 3, maxlength: 100 }
            },
            submitHandler: function(form, event) {
                event.preventDefault(); // Mencegah reload halaman
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
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
<<<<<<< HEAD
                            // Jika kamu menggunakan datatable atau ajax reload
                            if(typeof dataGedung !== 'undefined'){
                                dataGedung.ajax.reload();
                            }
=======
                            dataGedung.ajax.reload(); // Reload DataTables
>>>>>>> b47c49d9e955a2fba2c9563f97c5a260dfe314ac
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