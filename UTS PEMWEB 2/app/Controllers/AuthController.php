<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function login(): string|ResponseInterface
    {
        if (session()->get('user_id')) {
            return redirect()->to('/profile');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required',
            ];
            $messages = [
                'email' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
                'password' => [
                    'required' => 'Password wajib diisi.',
                ],
            ];

            if (! $this->validate($rules, $messages)) {
                return view('auth/login', [
                    'title' => 'NexaGames - Login',
                    'validation' => $this->validator,
                ]);
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', (string) $this->request->getPost('email'))->first();

            if (! $user || ! password_verify((string) $this->request->getPost('password'), (string) $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
            }

            session()->set([
                'user_id' => (int) $user['id'],
                'user_name' => (string) $user['name'],
                'user_email' => (string) $user['email'],
                'user_role' => (string) $user['role'],
                'user_photo' => (string) ($user['photo'] ?? ''),
                'is_logged_in' => true,
            ]);

            return redirect()->to('/profile')->with('success', 'Berhasil login. Selamat datang kembali!');
        }

        return view('auth/login', [
            'title' => 'NexaGames - Login',
        ]);
    }

    public function register(): string|ResponseInterface
    {
        if (session()->get('user_id')) {
            return redirect()->to('/profile');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
            ];
            $messages = [
                'name' => [
                    'required' => 'Nama wajib diisi.',
                    'min_length' => 'Nama minimal 3 karakter.',
                ],
                'email' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique' => 'Email sudah terdaftar.',
                ],
                'password' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.',
                ],
                'password_confirm' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak sama.',
                ],
            ];

            if (! $this->validate($rules, $messages)) {
                return view('auth/register', [
                    'title' => 'NexaGames - Register',
                    'validation' => $this->validator,
                ]);
            }

            $userModel = new UserModel();
            $userModel->insert([
                'name' => (string) $this->request->getPost('name'),
                'email' => (string) $this->request->getPost('email'),
                'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'user',
            ]);

            return redirect()->to('/login')->with('success', 'Registrasi berhasil. Silakan login.');
        }

        return view('auth/register', [
            'title' => 'NexaGames - Register',
        ]);
    }

    public function logout(): ResponseInterface
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}
