<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use function Sodium\compare;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNotNull('name')->orderBy('created_at', 'DESC')->paginate(100);
        return view('pages.gebruikeraanmaken', compact('users'));
    }

    public function register(Request $request)
    {
        $this->rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $hashedEmail = trim($user->hashValue(strtolower($user->cleanAttribute($request->get('email')))));

        if ($user->where('hashedemail', $hashedEmail)->exists()) {
            $validator->errors()->add('email', 'email_in_use');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->hashedemail = trim($user->hashValue(strtolower($user->cleanAttribute($request->get('email')))));
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect('/gebruikers');
    }

    public function delete($id)
    {

        $user = User::findOrFail($id);

        if (auth()->user()->id == $user->id) {
            return redirect('/gebruikeraanmaken')->with('alert', 'Je kan je eigen gebruiker niet verwijderen!');
        } else {
            $user->delete();
            return redirect('/gebruikeraanmaken', compact('user'));
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('pages.user', compact('user'));

    }

    public function update(Request $request, $id)
    {
        $this->rules = [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->save();

        return redirect('/gebruikers');
    }
}
