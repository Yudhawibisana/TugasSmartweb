@extends('front.layout.template')

@section('title', $postingan->title . ' - Postingan StudyGroup')
@section('content')
    <div class="container mt-3">
        <div class="row show">
            <div class="col-lg-6">
                <div class="card mb-4 shadow">
                    {{-- Gambar utama postingan --}}
                    {{-- Di sini gambar utama postingan dikomentari, saya asumsikan memang tidak ditampilkan di detail ini --}}
                    {{-- Namun, jika Anda ingin menambahkannya kembali, posisinya harus di sini, sebelum profil user --}}
                    {{-- <a href="{{ url('p/' . $postingan->slug) }}">
                        <img class="card-img-top single-img" src="{{ asset('storage/back/' . $postingan->img) }}"
                            alt="{{ $postingan->title }}" />
                    </a> --}}

                    {{-- Bagian Profil Pengguna yang Membuat Postingan --}}
                    {{-- Hapus mt-3 dari sini karena p-3 sudah cukup untuk padding atas --}}
                    <div class="d-flex align-items-center p-3">
                        <img src="{{ $postingan->User->profile_photo ? asset('storage/profile/' . $postingan->User->profile_photo) : asset('front/assets/face.svg') }}"
                            alt="Foto Profil Pengguna" class="rounded-circle me-2"
                            style="width: 32px; height: 32px; object-fit: cover;">
                        <span class="fw-bold">{{ $postingan->User->name }}</span>
                    </div>
                    {{-- END Bagian Profil Pengguna --}}

                    <div class="card-body pt-0 pb-0"> {{-- Tambahkan pt-0 dan pb-0 untuk mengatur padding card-body dari CSS --}}
                        <div class="small text-muted mb-2"> {{-- Kurangi mb-3 jadi mb-2 atau mb-1 --}}
                            <span>
                                {{ $postingan->created_at->format('d-m-Y') }}
                            </span>
                            <span class="ms-3"> {{-- Tambahkan ms-3 untuk spasi antara tanggal dan kategori --}}
                                <a
                                    href="{{ url('category/' . $postingan->Category->slug) }}">{{ $postingan->Category->name }}</a>
                            </span>
                            <span class="ms-3"> {{-- Tambahkan ms-3 untuk spasi antara kategori dan views --}}
                                {{ $postingan->views }}x <i class="bi bi-eye"></i>
                            </span>
                            @if ($statusBadge)
                                <span class="badge {{ $statusBadge['class'] }} ms-auto"> {{-- ms-auto dorong ke kanan --}}
                                    Status: {{ $statusBadge['label'] }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-muted mb-3"> {{-- Kurangi mt-3 jadi mt-2 dan atur mb-3 --}}
                            Kuota: {{ $joinedCount }}/{{ $postingan->max_participants }}
                        </p>
                        <h1 class="card-title mt-0 mb-3"> {{-- Atur margin judul --}}
                            {{ $postingan->title }}
                        </h1>
                        <p class="card-text mb-4">{!! $postingan->desc !!} <br> {{-- Atur margin bawah deskripsi --}}
                            @auth
                                @if (Auth::id() !== $postingan->user_id)
                                    @if ($joinStatus === 'approved')
                                        @if ($postingan->group)
                                            <a href="{{ $postingan->group }}" target="_blank"
                                                class="btn btn-success btn-sm me-2 mb-2"> {{-- Tambah me-2 dan mb-2 --}}
                                                Join Group WhatsApp
                                            </a>
                                        @else
                                            <div class="alert alert-warning mt-2 mb-0">Link WhatsApp belum tersedia.</div>
                                        @endif
                                    @elseif($joinStatus === 'pending')
                                        <button class="btn btn-secondary btn-sm me-2 mb-2" disabled>Menunggu
                                            Persetujuan</button>
                                    @elseif($joinStatus === 'rejected')
                                        <form id="joinForm" action="{{ route('postingan.requestJoin', $postingan->id) }}"
                                            method="POST" class="d-inline"> {{-- Tambah d-inline untuk form --}}
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm me-2 mb-2">
                                                Ajukan Join Ulang
                                            </button>
                                        </form>
                                    @else
                                        <form id="joinForm" action="{{ route('postingan.requestJoin', $postingan->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm me-2 mb-2">
                                                Join Study Group
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endauth
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-success btn-sm me-2 mb-2">
                                    Login untuk Join
                                </a>
                            @endguest
                        </p>
                        {{-- Group tombol aksi dan lihat lainnya --}}
                        <div class="d-flex flex-wrap"> {{-- Bungkus tombol dalam flex container --}}
                            @if (Auth::id() === $postingan->user_id)
                                <a href="{{ route('postingan.joinRequests', $postingan->id) }}"
                                    class="btn btn-sm btn-warning me-2 mb-2">
                                    Lihat Permintaan Join
                                </a>
                            @endif
                            <a href="{{ url('/') }}" class="btn btn-secondary btn-sm mb-2">Lihat Lainnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        {{-- ... script JS tetap sama ... --}}
    @endpush
@endsection
