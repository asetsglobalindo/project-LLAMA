<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('users.profile-page');
    }

    public function performance()
    {
        if (Auth::user()->email !== 'pertare.asets@gmail.com') {
            return redirect()->route('home');
        }

        return view('performance-calc');
    }

    public function wishlist()
    {
        return view('users.wishlist-page');
    }

    public function addFavorite(Request $request, $spaceID) {}

    public function removeFavorite(Request $request, $spaceID) {}

    public function favorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();
        $spaces = [];
        foreach ($favorites as $favorite) {
            $response = Http::get('http://localhost:8000/api/spaces/' . $favorite->space_id);
            $space = $response->json();
            array_push($spaces, $space);
        }
        return view('users.favorites-page', ['spaces' => $spaces]);
    }

    public function editProfile()
    {
        return view('');
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return redirect('/profile');
    }

    public function deleteProfile()
    {
        $user = User::find(Auth::id());
        $user->delete();
        return redirect('/');
    }
}
