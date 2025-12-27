<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('admin.auth.boxed.sign-in', [
            'title' => 'Login - AquaIncuba',
            'catName' => 'auth',
            'simplePage' => true,
        ]);
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'contraseña' => ['required'],
        ]);

        $usuario = Usuario::where('correo', $credentials['correo'])->first();

        if (!$usuario || !Hash::check($credentials['contraseña'], $usuario->contraseña)) {
            throw ValidationException::withMessages([
                'correo' => __('auth.failed'),
            ]);
        }

        Auth::login($usuario, $request->boolean('remember'));

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
