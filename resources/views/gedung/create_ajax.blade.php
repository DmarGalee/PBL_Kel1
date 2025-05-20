<form action="{{ url('/gedung/ajax') }}" method="POST" id="form-create"> 
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Gedung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- gedung kode -->
                <div class="form-group">
                    <label>Kode Gedung</label>
                    <input type="text" name="gedung_kode" id="gedung_kode" class="form-control" required>
                    <small id="error-gedung_kode" class="error-text form-text text-danger"></small>
                </div>
                <!-- gedung nama -->
                <div class="form-group">
                    <label>Nama Gedung</label>
                    <input type="text" name="gedung_nama" id="gedung_nama" class="form-control" required>
                    <small id="error-gedung_nama" class="error-text form-text text-danger"></small>
                </div>
                <!-- description -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
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
    $(document).ready(function() {
        $("#form-create").validate({  <!-- Changed from form-tambah to form-create to match form ID -->
            rules: {
                gedung_kode: { required: true, minlength: 2, maxlength: 50 },
                gedung_nama: { required: true, minlength: 3, maxlength: 100 },
                description: { maxlength: 500 }  <!-- Added validation for description -->
            },
            messages: {  <!-- Added custom messages -->
                description: {
                    maxlength: "Deskripsi tidak boleh lebih dari 500 karakter"
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#form-create')[0].reset();  <!-- Changed from form-tambah to form-create -->
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            if (typeof dataGedung !== 'undefined') {
                                dataGedung.ajax.reload();
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
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server'
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
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>