<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAdminController extends Controller
{
   public function create()
{
    $useradmins = \App\Models\User::where('usertype', 'useradmin')->get();
    return view('create_useradmin', compact('useradmins'));
}

    public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'purok' => 'required|string|max:50',
    ]);

    // Auto-generate a random 8-character password
    $generatedPassword = Str::random(8);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($generatedPassword),
         'password_plain' => $generatedPassword, // store un-hashed
        'usertype' => 'useradmin',
        'purok'    => $request->purok,
    ]);

    // ⚡ Option 1: Flash generated password to session (to show in Blade)
    return redirect()->route('useradmin.create')
        ->with('success', 'User Admin created successfully! Temporary Password: ' . $generatedPassword);
}

    public function update(Request $request, $id)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'purok' => 'required|string|max:50',
    ]);

    $user = User::findOrFail($id);
    $user->name  = $request->name;
    $user->email = $request->email;
    $user->purok = $request->purok;

    if ($request->filled('password')) {
        $request->validate(['password' => 'string|min:6|confirmed']);
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('useradmin.create')->with('success', 'User Admin updated successfully!');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('useradmin.create')->with('success', 'User Admin deleted successfully!');
}
}
