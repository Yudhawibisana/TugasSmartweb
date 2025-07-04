<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();
        $selectedCategories = $user->categories->pluck('id')->toArray();

        return view('front.profile.index', compact('user', 'categories', 'selectedCategories'));
    }

    public function updateCategories(Request $request) {
        $user = Auth::user();
        $user->categories()->sync($request->categories);
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function removeCategory(Category $category) {
        $user = Auth::user();
        $user->categories()->detach($category->id);
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function updateBiodata(Request $request) {
        $request->validate([
            'age' => 'nullable|integer|min:0|max:100',
            'class' => 'nullable|string|max:50',
            'school_or_university' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->only('age', 'class', 'school_or_university'));

        return redirect()->back()->with('success', 'Biodata berhasil diperbarui.');
    }

    public function updatePassword(Request $request) {
        $user = Auth::user();

        // Cek apakah password lama cocok terlebih dahulu
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password lama tidak sesuai.')
                ->withInput()
                ->with('active_tab', 'akun');
        }

        // Validasi password baru
        $validator = Validator::make($request->all(), [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\'\\:"|,.<>\/?]).+$/',
                'confirmed'
            ],
        ], [
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.regex' => 'Password harus mengandung huruf, angka, dan simbol.',
            'new_password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', $validator->errors()->first())
                ->withInput()
                ->with('active_tab', 'akun');
        }

        // Cek apakah password baru sama dengan password lama
        if (Hash::check($request->new_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password baru tidak boleh sama dengan password lama.')
                ->withInput()
                ->with('active_tab', 'akun');
        }

        // Simpan password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with([
            'success' => 'Password berhasil diperbarui!',
            'active_tab' => 'akun'
        ]);
    }

    public function uploadPhoto(Request $request) {
        $request->validate([
            'cropped_image' => 'required|string',
        ]);

        $user = Auth::user();
        $cropped = $request->input('cropped_image');

        $manager = new ImageManager(new GdDriver());

        $image = $manager->read($cropped)->toPng();

        $filename = 'profile_' . $user->id . '.png';
        Storage::disk('public')->put("profile/{$filename}", (string) $image);

        $user->profile_photo = $filename;
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }

    public function uploadCertificate(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'certificate_file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'year' => 'required|date_format:Y|before_or_equal:' . date('Y'),
        ]);

        $user = Auth::user();

        $file = $request->file('certificate_file');
        $filename = 'cert_' . time() . '_' . $file->getClientOriginalName();
        $file->storeAs('certificates', $filename, 'public');

        Certificate::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'file_path' => $filename,
            'year' => $request->year
        ]);

        return redirect()->back()->with('success', 'Sertifikat berhasil diunggah.');
    }
}