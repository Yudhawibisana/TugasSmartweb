<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Postingan;
use App\Models\User; // ✅ Tambahkan ini
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total
        $total_articles   = Postingan::count();
        $total_categories = Category::count();
        $total_users      = User::count(); // ✅ Hitung user
        $latest_article   = Postingan::with('Category')->whereStatus(1)->latest()->take(5)->get();
        $popular_article  = Postingan::with('Category')->whereStatus(1)->orderBy('views', 'desc')->take(5)->get();

        // Grafik per hari - 7 hari terakhir
        $startDate = now()->subDays(6)->startOfDay();
        $endDate   = now()->endOfDay();

        $data = Postingan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereStatus(1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = [];
        $totals = [];

        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $tgl = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('d M');
            $totals[] = $data->firstWhere('tanggal', $tgl)->total ?? 0;
        }

        return view('back.dashboard.index', [
            'total_articles'   => $total_articles,
            'total_categories' => $total_categories,
            'total_users'      => $total_users,
            'latest_article'   => $latest_article,
            'popular_article'  => $popular_article,
            'chart_labels'     => $labels,
            'chart_totals'     => $totals,
        ]);
    }
}
