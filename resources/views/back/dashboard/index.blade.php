@extends('back/layouts/template')

@section('title', 'Dashboard - Admin')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
    .card-stat {
        border-radius: 1rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .card-stat:hover {
        transform: translateY(-5px);
    }

    .card-stat h1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }

    .card-stat .icon {
        font-size: 3rem;
        opacity: 0.2;
        position: absolute;
        right: 1.5rem;
        top: 1.2rem;
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .btn-view {
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
        <h1 class="h3 fw-bold">📊 Dashboard Admin</h1>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card-stat" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="icon"><i class="bi bi-journal-richtext"></i></div>
                <h5>Total Postingan</h5>
                <h1>{{ $total_articles }}</h1>
                <a href="{{ url('article') }}" class="btn btn-outline-light btn-sm btn-view mt-2">Lihat Semua</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-stat" style="background: linear-gradient(135deg, #ff5858, #f857a6);">
                <div class="icon"><i class="bi bi-tags-fill"></i></div>
                <h5>Total Kategori</h5>
                <h1>{{ $total_categories }}</h1>
                <a href="{{ url('categories') }}" class="btn btn-outline-light btn-sm btn-view mt-2">Lihat Semua</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-stat" style="background: linear-gradient(135deg, #00c9ff, #92fe9d);">
                <div class="icon"><i class="bi bi-people-fill"></i></div>
                <h5>Total Pengguna</h5>
                <h1>{{ $total_users }}</h1>
                <a href="{{ url('users') }}" class="btn btn-outline-light btn-sm btn-view mt-2">Lihat Semua</a>
            </div>
        </div>
    </div>

    {{-- Grafik Postingan Per Hari --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3">📈 Grafik Postingan per Hari (7 Hari Terakhir)</h5>
            <canvas id="grafikHarian" height="100"></canvas>
        </div>
    </div>

    {{-- Postingan Terbaru --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-3">🆕 Postingan Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Kategori</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latest_article as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->title }}</td>
                            <td><span class="badge bg-secondary">{{ $item->Category->name }}</span></td>
                            <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                <a href="{{ url('article/' . $item->id) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada postingan terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikHarian').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart_labels) !!},
            datasets: [{
                label: 'Total Postingan',
                data: {!! json_encode($chart_totals) !!},
                backgroundColor: 'rgba(102, 126, 234, 0.7)',
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endpush
