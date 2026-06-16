<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Customization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // Login Formulario
    public function showLoginForm()
    {
        // Verificar se o usuário já está autenticado
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Redirecionar para a dashboard se já estiver autenticado
        } elseif (User::count() <= 0) {
            // Verificar se existe algum usuario cadastrado
            return redirect()->route('register'); // Redirecionar para registrar usuario
        } else {
            $currentRoute = Route::current();
            return view('auth.login', [
                'customization' => fn($config, $else = null) => $this->search($config, $else),
                'routeName' => $currentRoute->getName()
            ]);
        }
    }

    // Registrar Formulario
    public function showRegisterForm()
    {
        // Verificar se e o primeiro acesso ou Verificar se o usuário já está autenticado
        if (User::count() <= 0 || Auth::check()) {
            $currentRoute = Route::current();
            return view('auth/register', [
                'customization' => fn($config, $else = null) => $this->search($config, $else),
                'routeName' => $currentRoute->getName()
            ]);
        } else return redirect()->route('login'); // Redirecionar para o login caso não esteja logado
    }

    // Registar
    public function register(Request $request)
    {
        $user_data = new User();

        $user_data->email = $request->email;
        $user_data->password = Hash::make($request->password);
        $user_data->name = $request->username;

        $user_data->save();

        return redirect()->route('login'); // Ir para a tela de login
    }

    // Login
    public function login(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Verificar se o email existe
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Não encontramos uma conta com esse email.',
            ])->withInput($request->except('password'));
        }

        // Verificar se a senha está correta
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'password' => 'A senha fornecida está incorreta.',
            ])->withInput($request->except('password'))->withInput($request->only('email'));
        }

        // Autenticação bem-sucedida
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Desconectar conta
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Deletar usuário
    public function delete($id)
    {
        User::where('id', $id)->delete();

        // Redirecionar ou retornar uma resposta
        return redirect()->route('users')->with('success', 'Usuário deletado com sucesso!');
    }

    // Atualizar dados

    public function update(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->email = $request->input('email');
        $user->name = $request->input('name');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();
        return redirect()->back()->with('success', 'Dados de login atualizados com sucesso.');
    }

    // Procurar configuração
    private function search($config, $else = null)
    {
        $customizations = Customization::where('config', $config);
        $customization = $customizations->first();

        if ($customizations->count() > 0) return $customization->value;
        else return $else;
    }
}
