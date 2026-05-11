<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Google\Client;
use Google\Service\Oauth2;

class Auth extends BaseController
{
    private const GOOGLE_REDIRECT_URI = 'http://localhost/NexaGames/public/index.php/auth/google/callback';

    public function login(): string|ResponseInterface
    {
        if (session()->get('user_id')) {
            return redirect()->to(site_url('profile'));
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

            if (! $user || empty($user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Akun tidak ditemukan atau gunakan Login dengan Google.');
            }

            if (! password_verify((string) $this->request->getPost('password'), (string) $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
            }

            $this->setUserSession($user);

            return redirect()->to(site_url('/'))->with('success', 'Berhasil login. Selamat datang kembali!');
        }

        return view('auth/login', [
            'title' => 'NexaGames - Login',
        ]);
    }

    public function google(): ResponseInterface
    {
        try {
            $client = $this->buildGoogleClient();
            $authUrl = $client->createAuthUrl();

            return redirect()->to($authUrl);
        } catch (\Throwable $e) {
            log_message('error', 'Google OAuth init gagal: ' . $e->getMessage());

            $message = 'Login Google gagal dijalankan. Periksa konfigurasi OAuth.';
            if (ENVIRONMENT === 'development') {
                $message .= ' Detail: ' . $e->getMessage();
            }

            return redirect()->to(site_url('login'))->with('error', $message);
        }
    }

    public function googleCallback(): ResponseInterface
    {
        $code = (string) $this->request->getGet('code');
        if ($code === '') {
            return redirect()->to(site_url('login'))->with('error', 'Login Google gagal: kode autentikasi tidak ditemukan.');
        }

        try {
            $client = $this->buildGoogleClient();
            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                $message = (string) ($token['error_description'] ?? $token['error']);
                return redirect()->to(site_url('login'))->with('error', 'Login Google gagal: ' . $message);
            }

            $client->setAccessToken($token);
            $oauth2 = new Oauth2($client);
            $googleUser = $oauth2->userinfo->get();

            $email = trim((string) $googleUser->email);
            $name = trim((string) $googleUser->name);
            $googleId = trim((string) $googleUser->id);
            $photo = trim((string) $googleUser->picture);

            if ($email === '' || $googleId === '') {
                return redirect()->to(site_url('login'))->with('error', 'Login Google gagal: data akun Google tidak lengkap.');
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if (! $user) {
                $userId = $userModel->insert([
                    'name' => $name !== '' ? $name : 'Pengguna Google',
                    'email' => $email,
                    'password' => null,
                    'google_id' => $googleId,
                    'avatar' => $photo !== '' ? $photo : null,
                    'photo' => $photo !== '' ? $photo : null,
                    'login_provider' => 'google',
                    'role' => 'user',
                ], true);

                $user = $userModel->find($userId);
            } else {
                $update = [];
                if (($user['name'] ?? '') !== $name && $name !== '') {
                    $update['name'] = $name;
                }
                if (($user['avatar'] ?? '') !== $photo && $photo !== '') {
                    $update['avatar'] = $photo;
                    $update['photo'] = $photo;
                }
                if (($user['google_id'] ?? '') !== $googleId) {
                    $update['google_id'] = $googleId;
                }
                if (($user['login_provider'] ?? '') !== 'google') {
                    $update['login_provider'] = 'google';
                }

                if ($update !== []) {
                    $userModel->update((int) $user['id'], $update);
                    $user = $userModel->find((int) $user['id']);
                }
            }

            if (! is_array($user) || empty($user['id'])) {
                return redirect()->to(site_url('login'))->with('error', 'Login Google gagal: akun tidak dapat diproses.');
            }

            $this->setUserSession($user);

            return redirect()->to(site_url('/'))->with('success', 'Login Google berhasil. Selamat datang!');
        } catch (\Throwable $e) {
            log_message('error', 'Google OAuth callback gagal: ' . $e->getMessage());

            $message = 'Login Google gagal. Silakan coba lagi.';
            if (ENVIRONMENT === 'development') {
                $message .= ' Detail: ' . $e->getMessage();
            }

            return redirect()->to(site_url('login'))->with('error', $message);
        }
    }

    public function logout(): ResponseInterface
    {
        session()->destroy();

        return redirect()->to(site_url('login'))->with('success', 'Berhasil logout.');
    }

    private function buildGoogleClient(): Client
    {
        $clientId = (string) env('GOOGLE_CLIENT_ID', '');
        $clientSecret = (string) env('GOOGLE_CLIENT_SECRET', '');
        $redirectUri = (string) env('GOOGLE_REDIRECT_URI', '');

        if ($clientId === '' || $clientSecret === '' || $redirectUri === '') {
            throw new \RuntimeException('Konfigurasi Google OAuth di .env belum lengkap.');
        }

        if ($redirectUri !== self::GOOGLE_REDIRECT_URI) {
            throw new \RuntimeException('GOOGLE_REDIRECT_URI harus bernilai: ' . self::GOOGLE_REDIRECT_URI);
        }

        $client = new Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(self::GOOGLE_REDIRECT_URI);
        $client->setAccessType('online');
        $client->setPrompt('select_account');
        $client->addScope('email');
        $client->addScope('profile');

        return $client;
    }

    private function setUserSession(array $user): void
    {
        session()->set([
            'user_id' => (int) $user['id'],
            'user_name' => (string) $user['name'],
            'user_email' => (string) $user['email'],
            'user_role' => (string) ($user['role'] ?? 'user'),
            'user_photo' => (string) ($user['avatar'] ?? $user['photo'] ?? ''),
            'is_logged_in' => true,
        ]);
    }
}
