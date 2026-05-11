<?php

namespace App\Controllers;

use App\Models\FavoriteGameModel;
use App\Models\ReviewModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index(): string
    {
        $userId = (int) session()->get('user_id');

        $userModel = new UserModel();
        $favoriteModel = new FavoriteGameModel();
        $reviewModel = new ReviewModel();

        $user = $userModel->find($userId);
        $favorites = $favoriteModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
        $reviews = $reviewModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return view('auth/profile', [
            'title' => 'NexaGames - Profil',
            'user' => $user,
            'favorites' => $favorites,
            'reviews' => $reviews,
        ]);
    }

    public function update(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'photo' => 'permit_empty|is_image[photo]|max_size[photo,2048]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]',
            'new_password' => 'permit_empty|min_length[6]',
            'confirm_password' => 'permit_empty|matches[new_password]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'photo' => [
                'is_image' => 'File foto harus berupa gambar.',
                'max_size' => 'Ukuran foto maksimal 2MB.',
                'mime_in' => 'Format foto harus JPG, PNG, atau WEBP.',
            ],
            'new_password' => [
                'min_length' => 'Password baru minimal 6 karakter.',
            ],
            'confirm_password' => [
                'matches' => 'Konfirmasi password tidak sama.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', 'Perbarui profil gagal. Periksa kembali form kamu.');
        }

        $email = (string) $this->request->getPost('email');
        $emailOwner = $userModel->where('email', $email)->where('id !=', $userId)->first();
        if ($emailOwner) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan akun lain.');
        }

        $updateData = [
            'name' => (string) $this->request->getPost('name'),
            'email' => $email,
        ];

        $newPassword = (string) $this->request->getPost('new_password');
        if ($newPassword !== '') {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $targetDir = FCPATH . 'uploads/profiles';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $newPhotoName = $photo->getRandomName();
            $photo->move($targetDir, $newPhotoName);
            $updateData['photo'] = 'uploads/profiles/' . $newPhotoName;
        }

        $userModel->update($userId, $updateData);

        $updatedUser = $userModel->find($userId);
        session()->set([
            'user_name' => (string) $updatedUser['name'],
            'user_email' => (string) $updatedUser['email'],
            'user_photo' => (string) ($updatedUser['photo'] ?? ''),
        ]);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
