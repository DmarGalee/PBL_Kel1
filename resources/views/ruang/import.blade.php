<form action="{{ url('/ruang/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Ruang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template_ruang.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i> Download
                    </a>
                    <small id="error-template" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_ruang" id="file_ruang" class="form-control" required>
                    <small id="error-file_ruang" class="error-text form-text text-danger"></small>
                </div>
                <div class="alert alert-info">
                    <strong>Petunjuk:</strong>
                    <ul>
                        <li>File harus berformat .xlsx (Excel)</li>
                        <li>Kolom harus sesuai dengan template yang disediakan</li>
                        <li>Data lantai harus sudah tersedia di sistem</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-import").validate({
            rules: {
                file_ruang: {
                    required: true, 
                    extension: "xlsx"
                },
            },
            messages: {
                file_ruang: {
                    required: "File import wajib diisi",
                    extension: "Hanya file Excel (.xlsx) yang diperbolehkan"
                }
            },
            submitHandler: function(form) {
    var formData = new FormData(form);
    console.log('FormData:', formData.get('file_ruang')); // Check if file is included
    Swal.fire({
        title: 'Mengunggah...',
        text: 'Harap tunggu, file sedang diproses.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({
        url: form.action,
        type: form.method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.close();
            if (response.status) {
                $('#myModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                dataRuang.ajax.reload();
            } else {
                $('.error-text').text('');
                if (response.msgField) {
                    $.each(response.msgField, function(prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                }
                let errorMessage = response.message;
                if (response.errors && response.errors.length > 0) {
                    errorMessage += '<ul>';
                    $.each(response.errors, function(index, error) {
                        errorMessage += '<li>' + error + '</li>';
                    });
                    errorMessage += '</ul>';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: errorMessage
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengupload file'
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