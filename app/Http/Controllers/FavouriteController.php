<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\Profile;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Auth::user()->profile;
        $favourites = $profile->favourites;
        return Inertia::render('Favourites/Index', compact('favourites'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store($id)
     {
        $profile = Auth::user()->profile;
        $profile->favourites()->attach($id);
        return redirect()->route('favourites.index');
     }

    /**
     * Display the specified resource.
     */

        public function show($id)
        {
            $user = Auth::user()->profile;// Mengambil pengguna yang sedang login
            $favourite = $user->favourites()->findOrFail($id); // Menampilkan detail favorit
            return Inertia::render('Favourite/Show', ['favourite' => $favourite]);
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favourite $favourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favourite $favourite)
    {
        //
    }
    public function destroy($id)//+
{
    $profile = Auth::user()->profile;
    $profile->favourites()->detach($id);
    return redirect()->route('favourite.index');
}
}
