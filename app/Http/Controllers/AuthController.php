<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home.index');
        }
        return view('auth.login');
    }

    /**
     * Authentifier l'utilisateur
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Mettre à jour la date de dernière connexion
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Vérifier si l'utilisateur est actif
            if (!$user->is_active || !$user->status) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.',
                ])->onlyInput('email');
            }

            // Rediriger vers le dashboard
            return redirect()->intended(route('home.index'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation du mot de passe
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
