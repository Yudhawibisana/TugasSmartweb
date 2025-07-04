@extends('front.layout.profile')

@section('title', 'Profil Saya')

@section('content')

<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4 mb-4">
            <div class="card d-flex justify-content-center">
                <div class="card-body d-flex align-items-center ms-3">
                    <div>
                        <!-- Foto Profil -->
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/profile/' . Auth::user()->profile_photo) : asset('front/assets/face.svg') }}"
                            alt="Foto Profil"
                            class="rounded-circle me-3"
                            style="width: 64px; height: 64px; object-fit: cover;">

                        <form action="{{ route('profile.uploadPhoto') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="upload-photo" name="photo" accept="image/*" class="d-none" required>
                            <input type="hidden" name="cropped_image" id="cropped_image">
                            <label for="upload-photo" class="text-primary" style="cursor: pointer;">Ubah foto</label>
                            <button type="submit" id="submit-photo" class="d-none"></button>
                        </form>
                    </div>

                    <div>
                        <h5 class="card-title mb-1">{{ Auth::user()->name }}</h5>
                        <p class="card-text mb-1">{{ Auth::user()->email }}</p>
                        <span class="badge bg-secondary">Belum Ada Pengalaman</span>
                    </div>
                </div>
            </div>

            <div class="list-group mt-4">
                <a class="list-group-item list-group-item-action {{ session('active_tab', 'profil') === 'profil' ? 'active' : '' }}" 
                    id="profil-tab" 
                    data-bs-toggle="tab" 
                    href="#profil" 
                    role="tab" 
                    aria-controls="profil" 
                    aria-selected="{{ session('active_tab', 'profil') === 'profil' ? 'true' : 'false' }}">Profil
                </a>
                <a class="list-group-item list-group-item-action {{ session('active_tab') === 'akun' ? 'active' : '' }}" 
                    id="akun-tab" 
                    data-bs-toggle="tab" 
                    href="#akun" 
                    role="tab" 
                    aria-controls="akun" 
                    aria-selected="{{ session('active_tab', 'profil') === 'akun' ? 'true' : 'false' }}">Akun
                </a>
                <a href="#" class="list-group-item list-group-item-action"></a>
                <a href="#" class="list-group-item list-group-item-action"></a>
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Konten -->
        <div class="col-md-8">
            <div class="tab-content">

                <!-- Tab Profil -->
                <div class="tab-pane fade {{ session('active_tab', 'profil') === 'profil' ? 'show active' : '' }}" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Profil</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Nama:</strong> {{ Auth::user()->name }}</li>
                                <li class="list-group-item"><strong>Umur:</strong> {{ Auth::user()->age ?? '-' }}</li>
                                <li class="list-group-item"><strong>Kelas:</strong> {{ Auth::user()->class ?? '-' }}</li>
                                <li class="list-group-item"><strong>Sekolah/Universitas:</strong> {{ Auth::user()->school_or_university ?? '-' }}</li>
                            </ul>

                            <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#editBiodataModal">Edit Profil</button>
                        </div>

                        <!-- Modal edit biodata -->
                        <div class="modal fade" id="editBiodataModal" tabindex="-1" aria-labelledby="editBiodataModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('profile.updateBiodata') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editBiodataModalLabel">Edit Profil</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="age" class="form-label">Umur</label>
                                                <input type="number" class="form-control" id="age" name="age" value="{{ Auth::user()->age }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="class" class="form-label">Kelas</label>
                                                <input type="text" class="form-control" id="class" name="class" value="{{ Auth::user()->class }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="school_or_university" class="form-label">Sekolah/Universitas</label>
                                                <input type="text" class="form-control" id="school_or_university" name="school_or_university" value="{{ Auth::user()->school_or_university }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title">Kategori Favorit</h4>

                            <!-- Tampilkan kategori -->
                            <div class="mb-3">
                                @forelse ($user->categories as $category)
                                    <span class="badge rounded-pill bg-light border text-dark me-2 mb-2 d-inline-flex align-items-center">
                                        {{ $category->name }}
                                        <form id="delete-category-form-{{ $category->id }}" action="{{ route('profile.remove-category', $category->id) }}" method="POST" class="d-inline ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-close btn-close-sm" aria-label="Hapus" onclick="confirmDelete({{ $category->id }})"></button>
                                        </form>
                                    </span>
                                @empty
                                    <span class="text-muted">Belum ada kategori yang dipilih.</span>
                                @endforelse
                            </div>

                            <!-- Form tambah kategori -->
                            <form action="{{ route('profile.updateCategories') }}" method="POST">
                                @csrf
                                <h5>Kategori yang Anda sukai:</h5>
                                <div class="row">
                                    @foreach ($categories as $category)
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    name="categories[]" 
                                                    value="{{ $category->id }}"
                                                    id="cat{{ $category->id }}"
                                                    {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cat{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Simpan Kategori</button>
                            </form>

                            <p id="max-warning" class="text-danger" style="display: none;">Kamu sudah mencapai batas maksimal pilihan</p>

                            <div class="card-body">
                                <h4 class="card-title">Prestasi & Sertifikat</h4>

                                <!-- Tampilkan Sertifikat -->
                                @forelse (Auth::user()->certificates as $certificate)
                                    <div class="mb-2">
                                        <strong>{{ $certificate->title }}</strong>
                                        @if($certificate->year)
                                            ({{ $certificate->year }})
                                        @endif
                                        <a href="{{ asset('storage/certificates/' . $certificate->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Lihat</a>
                                    </div>
                                @empty
                                    <p class="text-muted">Belum ada sertifikat yang diunggah.</p>
                                @endforelse

                                <!-- Form Upload Sertifikat -->
                                <form action="{{ route('profile.uploadCertificate') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    <div class="mb-2">
                                        <label for="title" class="form-label">Judul Sertifikat</label>
                                        <input type="text" name="title" id="title" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="certificate_file" class="form-label">File Sertifikat (PDF/JPG/PNG, max 2MB)</label>
                                        <input type="file" name="certificate_file" id="certificate_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="year" class="form-label">Tahun</label>
                                        <input type="number" name="year" id="year" class="form-control" min="1900" max="{{ date('Y') }}">
                                    </div>
                                    <button type="submit" class="btn btn-success">Upload Sertifikat</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Tab Akun -->
                <div class="tab-pane fade {{ session('active_tab', 'profil') === 'akun' ? 'show active' : '' }}" id="akun" role="tabpanel" aria-labelledby="akun-tab">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Akun</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                                <li class="list-group-item"><strong>Tanggal Dibuat:</strong> {{ Auth::user()->created_at->format('d M Y') }}</li>
                                
                                <!-- Tombol Ubah Password -->
                                <button class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">
                                    Ubah Password
                                </button>

                                <!-- Modal Ubah Password -->
                                <div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('profile.updatePassword') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updatePasswordModalLabel">Ubah Password</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="current_password" class="form-label">Password Lama</label>
                                                        <div class="input-group">
                                                            <input id="current_password" type="password" name="current_password"
                                                                class="form-control @error('current_password') is-invalid @enderror"
                                                                required>
                                                            <span class="input-group-text">
                                                                <i class="bi bi-eye-slash toggle-password" data-target="current_password" style="cursor: pointer;"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="new_password" class="form-label">Password Baru</label>
                                                        <div class="input-group">
                                                            <input id="new_password" type="password"
                                                                class="form-control @error('new_password') is-invalid @enderror"
                                                                name="new_password" required>
                                                            <span class="input-group-text">
                                                                <i class="bi bi-eye-slash toggle-password" data-target="new_password" style="cursor: pointer;"></i>
                                                            </span>
                                                        </div>
                                                        <small id="password-hint" class="text-muted">
                                                            Gunakan 8 atau lebih karakter, dengan perpaduan huruf, angka & simbol.
                                                        </small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                                        <div class="input-group">
                                                            <input id="new_password_confirmation" type="password"
                                                                class="form-control"
                                                                name="new_password_confirmation" required>
                                                            <span class="input-group-text">
                                                                <i class="bi bi-eye-slash toggle-password" data-target="new_password_confirmation" style="cursor: pointer;"></i>
                                                            </span>
                                                        </div>
                                                        <small id="confirm-hint" class="text-muted">
                                                            Harus sama dengan password baru yang dimasukkan sebelumnya.
                                                        </small>
                                                    </div>
                                                    <p class="mt-3">
                                                        <a href="{{ route('password.request') }}">Lupa password? Kirim link reset ke email</a>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </ul>
                            @if($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    .datepicker.dropdown-menu {
        z-index: 9999 !important; /* pastikan muncul di atas modal */
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    function confirmDelete(categoryId) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Kategori akan dihapus dari profil Anda.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-category-form-' + categoryId).submit();
            }
        });
    }

    let cropper;
    const input = document.getElementById('upload-photo');
    const croppedInput = document.getElementById('cropped_image');

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            Swal.fire({
                title: 'Sesuaikan Foto',
                html: '<img id="image-crop" src="' + event.target.result + '" style="max-width:100%;">',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                didOpen: () => {
                    const image = document.getElementById('image-crop');
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        cropBoxResizable: false,
                        cropBoxMovable: false,
                        background: false,
                        guides: false,
                    });
                },
                preConfirm: () => {
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                    });
                    croppedInput.value = canvas.toDataURL('image/png');
                    setTimeout(() => {
                        document.getElementById('submit-photo').click();
                    }, 200);
                }
            });
        };
        reader.readAsDataURL(file);
    });

    document.addEventListener("DOMContentLoaded", function () {

        // Toggle icon password (tetap dipertahankan)
        const toggleIcons = document.querySelectorAll('.toggle-password');
        toggleIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                if (!input) return;
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });

        // Validasi password (jika ada new_password)
        const passwordInput = document.getElementById('new_password');
        const passwordHint = document.getElementById('password-hint');
        const confirmInput = document.getElementById('new_password_confirmation');
        const confirmHint = document.getElementById('confirm-hint');

        if (passwordInput && confirmInput) {
            function validatePasswordFields() {
                const passwordValue = passwordInput.value;
                const confirmValue = confirmInput.value;

                const isValidPassword = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/.test(passwordValue);
                passwordInput.classList.toggle('is-invalid', !isValidPassword);
                passwordHint?.classList.toggle('text-danger', !isValidPassword);
                passwordHint?.classList.toggle('text-muted', isValidPassword);

                const confirmMismatch = confirmValue && confirmValue !== passwordValue;
                confirmInput.classList.toggle('is-invalid', confirmMismatch);
                confirmHint?.classList.toggle('text-danger', confirmMismatch);
                confirmHint?.classList.toggle('text-muted', !confirmMismatch);
            }

            passwordInput.addEventListener('input', validatePasswordFields);
            confirmInput.addEventListener('input', validatePasswordFields);
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonColor: '#198754',
                confirmButtonText: 'Oke'
            });
        @endif

        // SweetAlert: Pesan error untuk ubah password
        @if (session('error') && session('active_tab') === 'akun')
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonColor: '#d33',
                confirmButtonText: 'Tutup'
            });
        @endif

        // Konfirmasi hapus kategori
        window.confirmDelete = function(categoryId) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Kategori akan dihapus dari profil Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-category-form-' + categoryId).submit();
                }
            });
        };

        // Batas maksimal pilihan kategori
        const maxAllowed = 5;
        const checkboxes = document.querySelectorAll('input[name="categories[]"]');
        const warning = document.getElementById('max-warning');

        function updateCheckboxState() {
            const checkedCount = document.querySelectorAll('input[name="categories[]"]:checked').length;

            if (checkedCount >= maxAllowed) {
                warning.style.display = 'block';
                checkboxes.forEach(cb => {
                    if (!cb.checked) cb.disabled = true;
                });
            } else {
                warning.style.display = 'none';
                checkboxes.forEach(cb => cb.disabled = false);
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateCheckboxState);
        });

        updateCheckboxState();
    });

    // Year picker
    $('#yearPicker').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    });
</script>
@endpush
@endsection