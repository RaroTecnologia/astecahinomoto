<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $usuarios = User::all();
        $roles = Role::all();
        return view('web-admin.usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        return view('web-admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'avatar' => 'nullable|image',
        ]);

        $usuario = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
        ]);

        $usuario->syncRoles($request->input('roles'));

        return redirect()->route('web-admin.usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit($id)
    {
        $this->authorize('users.edit');
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        return view('web-admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('users.edit');
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image',
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $usuario->update($data);

        // Apenas usuários com permissão podem gerenciar roles
        if ($request->user()->can('users.manage_roles')) {
            $usuario->syncRoles($request->input('roles', []));
        }

        return redirect()->route('web-admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('web-admin.usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
