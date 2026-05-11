<?php

namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\FavoriteGameModel;
use App\Models\ReviewModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboard(): string
    {
        $userModel = new UserModel();
        $favoriteModel = new FavoriteGameModel();
        $reviewModel = new ReviewModel();

        $totalUsers = $userModel->countAllResults();
        $totalFavorites = $favoriteModel->countAllResults();
        $totalReviews = $reviewModel->countAllResults();
        $rating = $reviewModel->select('AVG(rating) as avg_rating')->first();

        $latestReviews = $reviewModel
            ->select('reviews.*, users.name as user_name')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll(5);

        $latestUsers = $userModel->orderBy('created_at', 'DESC')->findAll(5);

        return view('admin/dashboard', [
            'title' => 'Admin - Dashboard',
            'totalUsers' => $totalUsers,
            'totalFavorites' => $totalFavorites,
            'totalReviews' => $totalReviews,
            'avgRating' => (float) ($rating['avg_rating'] ?? 0),
            'latestReviews' => $latestReviews,
            'latestUsers' => $latestUsers,
        ]);
    }

    public function users(): string
    {
        $userModel = new UserModel();

        return view('admin/users', [
            'title' => 'Admin - Users',
            'users' => $userModel->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function updateUserRole(): ResponseInterface
    {
        $userId = (int) $this->request->getPost('user_id');
        $role = (string) $this->request->getPost('role');

        if (! in_array($role, ['admin', 'user'], true)) {
            return redirect()->back()->with('error', 'Role tidak valid.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (! $user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $userModel->update($userId, ['role' => $role]);

        return redirect()->back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function deleteUser(): ResponseInterface
    {
        $userId = (int) $this->request->getPost('user_id');
        $currentUserId = (int) session()->get('user_id');

        if ($userId === $currentUserId) {
            return redirect()->back()->with('error', 'Kamu tidak bisa menghapus akun admin yang sedang login.');
        }

        $userModel = new UserModel();
        $userModel->delete($userId);

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function reviews(): string
    {
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel
            ->select('reviews.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        return view('admin/reviews', [
            'title' => 'Admin - Reviews',
            'reviews' => $reviews,
        ]);
    }

    public function deleteReview(): ResponseInterface
    {
        $reviewId = (int) $this->request->getPost('review_id');
        $reviewModel = new ReviewModel();
        $reviewModel->delete($reviewId);

        return redirect()->back()->with('success', 'Review berhasil dihapus.');
    }

    public function banners(): string
    {
        $bannerModel = new BannerModel();

        return view('admin/banners', [
            'title' => 'Admin - Banners',
            'banners' => $bannerModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function createBanner(): ResponseInterface
    {
        $bannerModel = new BannerModel();
        $data = $this->buildBannerPayload();
        $bannerModel->insert($data);

        return redirect()->to('/admin/banners')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function updateBanner(int $id): ResponseInterface
    {
        $bannerModel = new BannerModel();
        $banner = $bannerModel->find($id);
        if (! $banner) {
            return redirect()->back()->with('error', 'Banner tidak ditemukan.');
        }

        $data = $this->buildBannerPayload($banner);
        $bannerModel->update($id, $data);

        return redirect()->to('/admin/banners')->with('success', 'Banner berhasil diperbarui.');
    }

    public function deleteBanner(): ResponseInterface
    {
        $bannerId = (int) $this->request->getPost('banner_id');
        $bannerModel = new BannerModel();
        $bannerModel->delete($bannerId);

        return redirect()->to('/admin/banners')->with('success', 'Banner berhasil dihapus.');
    }

    private function buildBannerPayload(array $existing = []): array
    {
        $image = $this->request->getFile('image');
        $imagePath = $existing['image'] ?? null;

        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $targetDir = FCPATH . 'uploads/banners';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $newName = $image->getRandomName();
            $image->move($targetDir, $newName);
            $imagePath = 'uploads/banners/' . $newName;
        }

        return [
            'title' => (string) $this->request->getPost('title'),
            'subtitle' => (string) $this->request->getPost('subtitle'),
            'image' => $imagePath,
            'button_text' => (string) $this->request->getPost('button_text'),
            'button_url' => (string) $this->request->getPost('button_url'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
    }
}
