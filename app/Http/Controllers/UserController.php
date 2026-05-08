<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->paginate(15);
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $supervisors = User::where('role', 'supervisor')->count();
        $operators = User::where('role', 'operator')->count();
        $inactive = User::where('is_active', false)->count();

        return view('users.index', compact('users', 'totalUsers', 'admins', 'supervisors', 'operators', 'inactive'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Intento de registro de usuario detectado', ['data' => $request->except(['_token'])]);

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'role' => ['required', 'string', 'in:supervisor,operator'],
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ], [
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password.required' => 'La contraseña es obligatoria.'
            ]);

            \Log::info('Validación exitosa, procediendo a crear usuario');

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            \Log::info('Usuario creado en base de datos', ['user_id' => $user->id]);

            return redirect()->route('users.index')->with('success', 'Usuario registrado con éxito.');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::warning('Error de validación al registrar usuario', ['errors' => $ve->errors()]);
            throw $ve;
        } catch (\Throwable $e) {
            \Log::error("Error crítico al crear usuario: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        \Log::info('Intento de actualización de usuario detectado', [
            'user_id' => $user->id,
            'data' => $request->except(['_token', '_method'])
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:supervisor,operator'],
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            // Prevent self-deactivation
            if ($user->id === auth()->id() && !$validated['is_active']) {
                return redirect()->back()->withErrors(['error' => 'No puedes inhabilitar tu propia cuenta.']);
            }

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
            ]);

            \Log::info('Usuario actualizado con éxito', ['user_id' => $user->id]);

            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            \Log::error("Error crítico al actualizar usuario: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
