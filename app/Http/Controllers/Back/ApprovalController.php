<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Postingan;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        // Ambil semua postingan yang statusnya pending
        $postingans = Postingan::with('User', 'Category')
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        return view('back.approval.index', compact('postingans'));
    }

    public function approve($id)
    {
        $postingan = Postingan::findOrFail($id);
        $postingan->approval_status = 'approved';
        $postingan->save();

        return back()->with('success', 'Postingan berhasil disetujui.');
    }

    public function reject($id)
    {
        $postingan = Postingan::findOrFail($id);
        $postingan->approval_status = 'rejected';
        $postingan->save();

        return back()->with('success', 'Postingan berhasil ditolak.');
    }
}