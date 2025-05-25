@empty($user)
    <div id="modal-profile" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Profile User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="text-center mb-3">
            <img src="{{ asset(auth()->user()->profile_picture ? 'storage/' . auth()->user()->profile_picture : 'img/user.png') }}"
                class="rounded-circle border border-2 border-primary shadow bg-white p-1"
                style="width: 160px; height: 160px; object-fit: cover;" alt="Foto Profil">
        </div>

        <!-- Form Upload Foto Profil -->
        <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Upload Foto Profil</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="form-control" required>
            </div>

            @if(auth()->user()->profile_picture)
                <div class="mb-3">
                    <label class="form-check-label" for="remove_picture">
                        <input type="checkbox" class="form-check-input" name="remove_picture" value="1" id="remove_picture">
                        Hapus foto saat ini
                    </label>
                </div>
            @endif

            <!-- Data Lainnya -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ auth()->user()->nama }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" value="{{ auth()->user()->username }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru (Opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
            </div>

            <div class="mb-3">
                <label for="level" class="form-label">Level Pengguna</label>
                <input type="text" value="{{ auth()->user()->level->level_nama }}" class="form-control" disabled>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>

        <!-- Form untuk Menghapus Foto Profil -->
        <form action="{{ route('profile.delete-picture') }}" method="POST" class="mt-3">
            @csrf
            @method('DELETE')
            @if(auth()->user()->profile_picture)
                <button type="submit" class="btn btn-danger btn-sm">Hapus Foto Profil</button>
            @endif
        </form>
    </div>

    <div class="modal-footer">
        <button onclick="modalAction('{{ url('/profile/edit') }}')" class="btn btn-success btn-sm">Edit</button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">Close</button>
    </div>
@endempty