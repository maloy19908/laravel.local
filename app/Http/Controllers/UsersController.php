<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Redirect;

class UsersController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('slug', 'admin');
        })->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        if (User::count() == 1) {
            // If this is the first user, assign them the admin role
            $role = Role::where('slug', 'admin')->first();
            $user->roles()->attach($role);
        } else {
            // Otherwise, assign them the client role
            $role = Role::where('slug', 'client')->first();
            $user->roles()->attach($role);
        }
        return redirect(route('users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::find($id);
        if (!$user) return abort(404, 'Нет такого пользователя');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::find($id);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|min:3|max:60' . Rule::unique('users')->ignore($user->id),
        ]);

        $user->update($request->all());
        $request->session()->flash('success', 'успешно');
        return redirect(route('users.edit', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Успех');
    }

    public function switchUser($userId) {
        // Не реализованно
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            Auth::loginUsingId($userId);
            return redirect()->back();
        } else {
           
            return redirect()->route('home')->with('error', 'У вас нет прав для выполнения этого действия');
        }
    }
}
