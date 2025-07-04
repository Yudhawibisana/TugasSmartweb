@extends('front.layout.template')

@section('title', 'StudyGroup')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="row post justify-content-center mt-4">
                    @foreach ($postingans as $item)
                        <div class="col-12 mb-4 d-flex justify-content-center">
                            {{-- Tambahkan kelas shadow-sm pada card untuk shadow tipis --}}
                            <div class="card konten shadow-sm h-100">
                                {{-- Pindahkan bagian profil ke atas card-body, dan berikan padding --}}
                                <div class="d-flex align-items-center p-3">
                                    <img src="{{ $item->User->profile_photo ? asset('storage/profile/' . $item->User->profile_photo) : asset('front/assets/face.svg') }}"
                                        alt="Foto Profil Pengguna" class="rounded-circle me-2"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                    {{-- Menggunakan $item->User->name untuk mengambil nama pengguna dari postingan --}}
                                    <span class="fw-bold">{{ $item->User->name }}</span>
                                </div>

                                <div class="card-body card-height pt-0"> {{-- pt-0 untuk menghilangkan padding-top tambahan --}}
                                    <h2 class="card-title h5">{{ $item->title }}</h2>
                                    <p class="card-text">{{ Str::limit(strip_tags($item->desc), 250, '...') }}</p>
                                    <div class="small text-muted mt-2"> {{-- mt-2 untuk sedikit margin atas --}}
                                        {{ $item->created_at->format('d-m-Y') }} |
                                        <a class="unstyle-list-categories"
                                            href="{{ url('category/' . $item->Category->slug) }}">
                                            {{ $item->Category->name }}
                                        </a>
                                    </div>
                                    <br>
                                    <a class="btn btn-detail mt-auto" href="{{ url('p/' . $item->slug) }}">Detail →</a>
                                    {{-- mt-auto untuk push tombol ke bawah --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination justify-content-center my-4">
                    {{ $postingans->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
