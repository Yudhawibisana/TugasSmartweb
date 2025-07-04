<?php

namespace App\Http\Controllers\Front;

use App\Models\Postingan;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostinganController extends Controller
{
    public function show($slug) {
        $postingan = Postingan::whereSlug($slug)->firstOrFail();
        $postingan->increment('views');

        $user = auth()->user();
        $joinStatus = null;
        $statusBadge = null;

        $joinedCount = $postingan->joinRequests()
            ->where('status', 'approved')
            ->count();

        if ($user && $user->id !== $postingan->user_id) {
            $existing = $postingan->joinRequests()
                ->where('user_id', $user->id)
                ->orderBy('pivot_updated_at', 'desc')
                ->first();

            $joinStatus = $existing ? $existing->pivot->status : null;

            if ($joinStatus) {
                $map = [
                    'pending' => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                    'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
                    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
                ];
                $statusBadge = $map[$joinStatus] ?? ['label' => ucfirst($joinStatus), 'class' => 'bg-secondary'];
            }
        }

        return view('front.postingan.show', [
            'postingan' => $postingan,
            'categories' => Category::latest()->get(),
            'category_navbar' => Category::latest()->take(3)->get(),
            'joinStatus' => $joinStatus,
            'statusBadge' => $statusBadge,
            'joinedCount' => $joinedCount,
        ]);
    }

    public function requestJoin($id) {
        $user = auth()->user();
        $postingan = Postingan::findOrFail($id);

        // Cegah user join ke postingan sendiri
        if ($postingan->user_id === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat bergabung dengan study group milik Anda sendiri.');
        }

        // Hitung jumlah peserta aktif (approved atau pending)
        $approvedCount = $postingan->joinRequests()->where('status', 'approved')->count();

        // Cek apakah sudah penuh
        if ($approvedCount >= $postingan->max_participants) {
            return back()->with('error', 'Study group ini sudah penuh.');
        }

        // Cek apakah sudah pernah request
        $existingRequest = $postingan->joinRequests()
            ->where('user_id', $user->id)
            ->first();

        if ($existingRequest) {
            if (in_array($existingRequest->pivot->status, ['pending', 'approved'])) {
                return redirect()->back()->with('error', 'Anda sudah mengajukan permintaan.');
            }

            // Jika sebelumnya ditolak, perbarui status menjadi pending
            $postingan->joinRequests()->updateExistingPivot($user->id, ['status' => 'pending']);
            return redirect()->back()->with('success', 'Permintaan join ulang berhasil dikirim. Menunggu persetujuan.');
        }

        // Belum pernah request sebelumnya
        $postingan->joinRequests()->attach($user->id, ['status' => 'pending']);
        return redirect()->back()->with('success', 'Permintaan join berhasil dikirim. Menunggu persetujuan.');
    }
}
