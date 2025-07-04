@extends('back/layouts/template')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
    <style>
        .card-custom {
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e5e5;
            background: #ffffff;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table thead th {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #495057;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
            cursor: pointer;
        }

        .btn-custom {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-right: 4px;
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
        }
    </style>
@endpush

@section('title', 'List Postingan - Admin')

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center pt-4 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-semibold">📚 Daftar Postingan</h1>
        <a href="{{ url('postingan/create') }}" class="btn btn-secondary btn-custom">
            <i data-feather="plus" class="me-1"></i> Buat Postingan
        </a>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Alert --}}
    <div class="swal" data-swal="{{ session('success') }}"></div>

    {{-- Table Card --}}
    <div class="card card-custom p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>NO</th>
                        <th>Nama</th>
                        <th>Mata Pelajaran</th>
                        <th>Kategori</th>
                        <th>Views</th>
                        <th>Maks. Anggota</th>
                        <th>Status</th>
                        <th>Tanggal Post</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Feather Icons
        feather.replace();

        // Success Alert
        const swal = $('.swal').data('swal');
        if (swal) {
            Swal.fire({
                title: 'Berhasil!',
                text: swal,
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }

        // Delete Postingan
        function deletePostingan(e) {
            let id = e.getAttribute('data-id');
            Swal.fire({
                title: 'Hapus Postingan?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        url: '/postingan/' + id,
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                title: 'Dihapus!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                $('#dataTable').DataTable().ajax.reload();
                            });
                        },
                        error: function(xhr) {
                            alert(xhr.status + "\n" + xhr.responseText);
                        }
                    });
                }
            });
        }

        // DataTables Init
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url()->current() }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'title', name: 'title' },
                    { data: 'category_id', name: 'category_id' },
                    { data: 'views', name: 'views' },
                    { data: 'max_participants', name: 'max_participants' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'publish_date' },
                    { data: 'button', name: 'button', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
