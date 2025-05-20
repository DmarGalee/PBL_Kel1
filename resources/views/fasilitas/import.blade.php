<form action="{{ url('/fasilitas/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Fasilitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('templates/template_fasilitas.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i> Download Template
                    </a>
                    <small id="error-template" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pilih File Excel</label>
                    <input type="file" name="file_fasilitas" id="file_fasilitas" class="form-control" required>
                    <small id="error-file_fasilitas" class="error-text form-text text-danger"></small>
                </div>
                <div class="alert alert-info">
                    <strong>Petunjuk Import Data Fasilitas:</strong>
                    <ul class="mb-1">
                        <li>File harus berformat .xlsx (Excel)</li>
                        <li>Gunakan template yang telah disediakan</li>
                        <li>Kolom harus sesuai urutan berikut:</li>
                    </ul>
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>Kolom</th>
                            <th>Keterangan</th>
                            <th>Contoh</th>
                        </tr>
                        <tr>
                            <td>fasilitas_kode</td>
                            <td>Kode unik fasilitas (wajib)</td>
                            <td>F-001</td>
                        </tr>
                        <tr>
                            <td>ruang_id</td>
                            <td>ID ruang yang valid (wajib)</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>kategori_id</td>
                            <td>ID kategori yang valid (wajib)</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>deskripsi</td>
                            <td>Deskripsi fasilitas (opsional)</td>
                            <td>Proyektor Epson</td>
                        </tr>
                        <tr>
                            <td>status</td>
                            <td>baik/rusak_ringan/rusak_berat (default: baik)</td>
                            <td>baik</td>
                        </tr>
                    </table>
                    <div class="mt-2">
                        <strong>Catatan:</strong>
                        <ul>
                            <li>Pastikan ruang_id dan kategori_id sudah ada di database</li>
                            <li>Status akan default 'baik' jika dikosongkan</li>
                            <li>fasilitas_kode harus unik</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Import Data</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-import").validate({
            rules: {
                file_fasilitas: {
                    required: true, 
                    extension: "xlsx|xls"
                },
            },
            messages: {
                file_fasilitas: {
                    required: "File import wajib diisi",
                    extension: "Hanya file Excel (.xlsx/.xls) yang diperbolehkan"
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            dataFasilitas.ajax.reload();
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