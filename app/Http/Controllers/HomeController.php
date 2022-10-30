<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }

    public function profile()
    {
        return view('profile');
    }

    public function change_psd(Request $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->oldpassword, $user->password))
            return redirect('/profile')->with('message', 'Old password Incorrect!');
        if ($request->newpassword != $request->passwordrpt)
            return redirect('/profile')->with('message', "New passwords don't match!");

        $update = [
            'password' => Hash::make($request->newpassword)
        ];
        User::where('id', $user->id)->update($update);

        return redirect('/profile')->with('success', 'Success!');
    }
}
