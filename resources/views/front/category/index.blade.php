@extends('front.layout.template')

@section('title', 'Category ' . $category . ' - StudyGroup')
@section('content')
    <div class="container">

        <p class="mb-4">Showing Postingans with Category : <b>{{ $category }}</b></p>


        <div class="row post justify-content-center"> {{-- Tambahkan kelas 'post' dan 'justify-content-center' --}}
            @forelse ($postingans as $item)
                {{-- Gunakan col-xl-4, col-md-6, col-sm-10 agar responsif seperti di halaman utama --}}
                <div class="col-xl-4 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                    <div class="card konten shadow-sm h-100" data-aos="zoom-in-up"> {{-- Tambahkan data-aos --}}
                        {{-- Bagian Profil Pengguna --}}
                        <div class="d-flex align-items-center p-3">
                            <img src="{{ $item->User->profile_photo ? asset('storage/profile/' . $item->User->profile_photo) : asset('front/assets/face.svg') }}"
                                alt="Foto Profil Pengguna" class="rounded-circle me-2"
                                style="width: 32px; height: 32px; object-fit: cover;">
                            <span class="fw-bold">{{ $item->User->name }}</span>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body card-height pt-0"> {{-- Tambahkan card-height dan pt-0 --}}
                            <h2 class="card-title h5">{{ $item->title }}</h2>
                            <p class="card-text">{{ Str::limit(strip_tags($item->desc), 250, '...') }}</p>
                            {{-- Tingkatkan limit seperti di utama --}}
                            <div class="small text-muted mt-2">
                                {{ $item->created_at->format('d-m-Y') }} |
                                <a class="unstyle-list-categories" href="{{ url('category/' . $item->Category->slug) }}">
                                    {{ $item->Category->name }}
                                </a>
                            </div>
                            <br> {{-- Biarkan <br> atau ganti dengan margin di CSS --}}
                            <a class="btn btn-detail mt-auto" href="{{ url('p/' . $item->slug) }}">Detail →</a>
                            {{-- Gunakan btn-detail dan mt-auto --}}
                        </div>
                    </div>
                </div>
            @empty
                <h3 class="mb-5 text-center col-12">Tidak ada Postingan di Kategori Ini.</h3> {{-- Tambahkan col-12 dan text-center --}}
            @endforelse
        </div>
        <div class="pagination justify-content-center my-4"> {{-- Bungkus pagination dalam div untuk centering --}}
            {{ $postingans->links() }}
        </div>

    </div>
@endsection
