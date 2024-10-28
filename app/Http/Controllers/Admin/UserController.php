<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('web-admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('web-admin.usuarios.create');
    }

    public function store(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
            'avatar' => 'nullable|image',
        ]);

        // Criar o usuário
        $usuario = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
        ]);

        return redirect()->route('web-admin.usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('web-admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        // Validações
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
            'avatar' => 'nullable|image',
        ]);

        // Atualizar o usuário
        $usuario = User::findOrFail($id);
        $usuario->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $usuario->password,
            'role' => $request->input('role'),
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : $usuario->avatar,
        ]);

        return redirect()->route('web-admin.usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('web-admin.usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
