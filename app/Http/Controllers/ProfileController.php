<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Inertia\Inertia;


class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Profile/Index', [
            'user' => $user,
        ]);
        
    }


    public function create()
    {

    }


    public function store(Request $request)
    {

    }


    public function show(Profile $profile, $id)
    {
        $data = Profile::where('id', $id)->firstOrFail();

        return Inertia::render('Profile/Show', [
            'data' => $data,
        ]);
    }


    public function edit($id)
    {
        $user = Auth::user();


        return Inertia::render('Profile/EditProfile', [
            'user' => $user,
        ]);
    }

    public function editPassword() {
        return Inertia::render('Profile/EditPassword');
    }


    public function update(ProfileUpdateRequest $request, $id)
    {
        $user = Profile::findOrFail($id);
        $user->update($request->validated());
        return Inertia::render('Profile/Show', [
            'profile' => $user,
            'success' => 'Profile updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile, $id)
    {$profile = Profile::find($id);

        if ($profile) {
            $profile->delete();
            // Mengarahkan ke halaman dengan pesan sukses
            return Inertia::render('Profile/Index', [
                'success' => 'Data sudah dihapus.',
                // Jika Anda ingin mengirimkan data lain ke halaman
                'profiles' => Profile::all() // Menyediakan data terbaru
            ]);
        } else {
            // Mengarahkan ke halaman dengan pesan error
            return Inertia::render('Profile/Index', [
                'error' => 'Data gagal dihapus.',
                'profiles' => Profile::all() // Menyediakan data terbaru
            ]);
        }
    }

    public function trash() {
        $profiles = Profile::onlyTrashed()->get();
        return Inertia::render('Profile/Trash', [
            'profiles' => $profiles,
        ]);
    }

    public function restore($id)
    {
        // Mencari profil yang di-soft delete
        $profile = Profile::onlyTrashed()->find($id);

        if ($profile) {
            $profile->restore(); // Mengembalikan data
            // Mengarahkan ke halaman dengan pesan sukses
            return Inertia::render('Profile/Index', [
                'success' => 'Data sudah dikembalikan.',
                // Jika Anda ingin mengirimkan data lain ke halaman
                'profiles' => Profile::all() // Menyediakan data terbaru
            ]);
        } else {
            // Mengarahkan ke halaman dengan pesan error
            return Inertia::render('Profile/Index', [
                'error' => 'Data tidak bisa dikembalikan.',
                'profiles' => Profile::all() // Menyediakan data terbaru
            ]);
        }
    }

    public function permanentDelete($id) {
        $profile = Profile::onlyTrashed()->find($id);

        if ($profile) {
            $profile->forceDelete(); // Menghapus data secara permanen
            // Mengarahkan ke halaman dengan pesan sukses
            return Inertia::render('Profile/Index', [
                'success' => 'Data yang tersimpan dihapus permanen.',
                // Menyediakan data terbaru jika diperlukan
                'profiles' => Profile::all() // Menyediakan data terbaru jika diperlukan
            ]);
        } else {
            // Mengarahkan ke halaman dengan pesan error
            return Inertia::render('Profile/Index', [
                'error' => 'Data gagal dihapus permanen.',
                'profiles' => Profile::all() // Menyediakan data terbaru jika diperlukan
            ]);
        }

    }


}
