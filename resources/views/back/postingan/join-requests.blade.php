@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body bg-login">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Permintaan Bergabung</h4>
                            <p class="text-muted">Kelola permintaan untuk <strong>{{ $postingan->title }}</strong></p>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($postingan->joinRequests->isEmpty())
                            <div class="text-center text-muted">Belum ada permintaan join.</div>
                        @else
                            <div class="list-group">
                                @foreach ($postingan->joinRequests as $user)
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <small>Status:
                                                @if ($user->pivot->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @elseif ($user->pivot->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex flex-row gap-1">
                                            @if ($user->pivot->status === 'pending')
                                                <form class="approve-form" data-username="{{ $user->name }}" action="{{ route('postingan.approveJoin', [$postingan->id, $user->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Terima</button>
                                                </form>
                                                <form class="reject-form" data-username="{{ $user->name }}" action="{{ route('postingan.rejectJoin', [$postingan->id, $user->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                                </form>
                                            @else
                                                <span class="text-muted">Selesai</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <a href="{{ url('p/' . $postingan->slug) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali ke Postingan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Terima
    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const userName = form.dataset.username;

            Swal.fire({
                title: 'Terima Permintaan?',
                text: `Terima permintaan bergabung dari ${userName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Konfirmasi Tolak
    document.querySelectorAll('.reject-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const userName = form.dataset.username;

            Swal.fire({
                title: 'Tolak Permintaan?',
                text: `Tolak permintaan bergabung dari ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection