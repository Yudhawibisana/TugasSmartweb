@extends('back/layouts/template')

@section('title', 'Persetujuan Postingan')

@section('content')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Approval</h1>
    </div>

    <div class="swal" data-swal="{{ session('success') }}"></div>

    @if($postingans->isEmpty())
        <div class="alert alert-info">Tidak ada postingan yang menunggu persetujuan.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Pengguna</th>
                    <th>Foto</th>
                    <th>Deskripsi</th>
                    <th>Persetujuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($postingans as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->Category->name }}</td>
                        <td>{{ $post->User->name }}</td>
                        <td>
                            <a href="#" class="text-primary lihat-foto" data-img="{{ asset('storage/back/' . $post->img) }}">
                                Lihat Foto
                            </a>
                        </td>
                        <td>{!! Str::limit(strip_tags($post->desc), 100) !!}</td>
                        <td>
                            <form action="{{ route('approval.approve', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('approval.reject', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</main>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const swal = document.querySelector('.swal').dataset.swal;
    if (swal) {
        Swal.fire({
            title: 'Success',
            text: swal,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('.lihat-foto');
        const modalImage = document.getElementById('modalImage');

        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const imgUrl = this.getAttribute('data-img');
                modalImage.setAttribute('src', imgUrl);
                new bootstrap.Modal(document.getElementById('fotoModal')).show();
            });
        });
    });
</script>

<!-- Modal Bootstrap untuk Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="" id="modalImage" class="img-fluid rounded" alt="Foto Postingan">
      </div>
    </div>
  </div>
</div>

@endpush