<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::with('products', 'orders')->paginate(10);

        return response()
            ->view('dashboard.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()
            ->view('dashboard.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create(request()->only(['name', 'email', 'password']));

        // TODO: send email verification to email user

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'User successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        abort_if($user->role === Role::ADMIN, 403);

        return response()
            ->view('dashboard.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user): RedirectResponse
    {
        abort_if($user->role === Role::ADMIN, 403);

        $rules = [
            'name' => 'required',
            'email' => "required|email|unique:users,email,$user->id"
        ];
        if (request()->input('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }
        request()->validate($rules);

        $user->name = request()->input('name');
        $user->email = request()->input('email');
        if (request()->input('password')) {
            $user->password = Hash::make(request()->input('password'));
        }
        $user->save();

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->role === Role::ADMIN || auth()->id() === $user->id, 403);

        $user->delete();
        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'User successfully deleted.');
    }
}
