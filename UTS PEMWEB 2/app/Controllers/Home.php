<?php

namespace App\Controllers;

use App\Models\GameHistoryModel;
use App\Models\FavoriteGameModel;
use App\Models\ReviewModel;
use App\Models\BannerModel;
use App\Services\FreeToGameService;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    private FreeToGameService $freeToGameService;

    public function __construct()
    {
        $this->freeToGameService = new FreeToGameService();
    }

    public function index(): string
    {
        $result = $this->freeToGameService->getGames();
        $games = $result['data'] ?? [];
        $allGames = $games;
        $perPage = 30;
        $currentPage = max(1, (int) $this->request->getGet('page'));
        $totalGame = count($allGames);
        $totalPages = max(1, (int) ceil($totalGame / $perPage));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $perPage;
        $games = array_slice($allGames, $offset, $perPage);
        $bannerModel = new BannerModel();
        $banners = $bannerModel->where('is_active', 1)->orderBy('id', 'DESC')->findAll();

        return view('games/index', [
            'games' => $games,
            'banners' => $banners,
            'totalGame' => $totalGame,
            'displayedGame' => count($games),
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'errorApi' => ! $result['ok'] && empty($games),
            'apiMessage' => $result['message'] ?? '',
            'title' => 'NexaGames - Katalog Game Gratis',
        ]);
    }

    public function detail(int $id): string|ResponseInterface
    {
        $result = $this->freeToGameService->getGameDetail($id);
        $game = $result['data'];

        if (empty($game) || isset($game['status'])) {
            return redirect()->to(site_url('/'))->with('error', 'Game tidak ditemukan atau server game sedang bermasalah.');
        }

        $historyModel = new GameHistoryModel();
        $historyModel->insert([
            'game_id' => (int) $game['id'],
            'title' => (string) $game['title'],
            'genre' => (string) $game['genre'],
            'platform' => (string) $game['platform'],
            'thumbnail' => (string) $game['thumbnail'],
            'fetched_at' => date('Y-m-d H:i:s'),
        ]);

        $reviewModel = new ReviewModel();
        $reviews = $reviewModel
            ->select('reviews.*, users.name as user_name')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->where('game_id', (int) $game['id'])
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        $userReview = null;
        $userId = (int) session()->get('user_id');
        if ($userId > 0) {
            $userReview = $reviewModel
                ->where('user_id', $userId)
                ->where('game_id', (int) $game['id'])
                ->first();
        }

        $ratingSummary = $reviewModel
            ->select('AVG(rating) as avg_rating, COUNT(*) as total_review')
            ->where('game_id', (int) $game['id'])
            ->first();

        $isFavorited = false;
        if ($userId > 0) {
            $favoriteModel = new FavoriteGameModel();
            $isFavorited = (bool) $favoriteModel
                ->where('user_id', $userId)
                ->where('game_id', (int) $game['id'])
                ->first();
        }

        return view('games/detail', [
            'game' => $game,
            'reviews' => $reviews,
            'userReview' => $userReview,
            'ratingSummary' => $ratingSummary,
            'isLoggedIn' => (bool) session()->get('user_id'),
            'isFavorited' => $isFavorited,
            'title' => 'NexaGames - ' . $game['title'],
        ]);
    }

    public function addFavorite(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $gameId = (int) $this->request->getPost('game_id');

        if ($userId <= 0) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu untuk menambahkan favorit.');
        }

        $favoriteModel = new FavoriteGameModel();
        $existing = $favoriteModel
            ->where('user_id', $userId)
            ->where('game_id', $gameId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('success', 'Game sudah ada di daftar favorit kamu.');
        }

        $favoriteModel->insert([
            'user_id' => $userId,
            'game_id' => $gameId,
            'game_title' => (string) $this->request->getPost('game_title'),
            'game_thumbnail' => (string) $this->request->getPost('game_thumbnail'),
            'game_genre' => (string) $this->request->getPost('game_genre'),
            'game_platform' => (string) $this->request->getPost('game_platform'),
        ]);

        return redirect()->back()->with('success', 'Game berhasil ditambahkan ke favorit.');
    }

    public function removeFavorite(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $gameId = (int) $this->request->getPost('game_id');

        if ($userId <= 0) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $favoriteModel = new FavoriteGameModel();
        $favoriteModel
            ->where('user_id', $userId)
            ->where('game_id', $gameId)
            ->delete();

        return redirect()->back()->with('success', 'Game berhasil dihapus dari favorit.');
    }

    public function favorites(): string
    {
        $userId = (int) session()->get('user_id');
        $favoriteModel = new FavoriteGameModel();

        $favorites = $favoriteModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('favorites/index', [
            'title' => 'NexaGames - Game Favorit',
            'favorites' => $favorites,
        ]);
    }

    public function addReview(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');

        if ($userId <= 0) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu untuk memberi ulasan.');
        }

        $validation = service('validation');
        $validation->setRules([
            'game_id' => 'required|integer',
            'game_title' => 'required|max_length[255]',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'comment' => 'permit_empty|max_length[2000]',
        ], [
            'game_id' => [
                'required' => 'ID game wajib diisi.',
                'integer' => 'ID game tidak valid.',
            ],
            'game_title' => [
                'required' => 'Judul game wajib diisi.',
                'max_length' => 'Judul game terlalu panjang.',
            ],
            'rating' => [
                'required' => 'Rating wajib dipilih.',
                'integer' => 'Rating harus berupa angka.',
                'greater_than_equal_to' => 'Rating minimal 1.',
                'less_than_equal_to' => 'Rating maksimal 5.',
            ],
            'comment' => [
                'max_length' => 'Komentar maksimal 2000 karakter.',
            ],
        ]);

        $postData = $this->request->getPost();
        if (! $validation->run($postData)) {
            return redirect()->back()->with('error', 'Ulasan gagal disimpan. Periksa data yang kamu isi.');
        }

        $reviewModel = new ReviewModel();
        $existing = $reviewModel
            ->where('user_id', $userId)
            ->where('game_id', (int) $postData['game_id'])
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Kamu sudah memberi review untuk game ini. Gunakan fitur edit review.');
        }

        $reviewModel->insert([
            'user_id' => $userId,
            'game_id' => (int) $postData['game_id'],
            'game_title' => (string) $postData['game_title'],
            'rating' => (int) $postData['rating'],
            'comment' => (string) $postData['comment'],
        ]);

        return redirect()->back()->with('success', 'Terima kasih, ulasan kamu berhasil dikirim.');
    }

    public function updateReview(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $gameId = (int) $this->request->getPost('game_id');

        $validation = service('validation');
        $validation->setRules([
            'game_id' => 'required|integer',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'comment' => 'permit_empty|max_length[2000]',
        ], [
            'rating' => [
                'required' => 'Rating wajib dipilih.',
                'greater_than_equal_to' => 'Rating minimal 1.',
                'less_than_equal_to' => 'Rating maksimal 5.',
            ],
            'comment' => [
                'max_length' => 'Komentar maksimal 2000 karakter.',
            ],
        ]);

        $postData = $this->request->getPost();
        if (! $validation->run($postData)) {
            return redirect()->back()->with('error', 'Ulasan gagal diperbarui. Periksa data yang kamu isi.');
        }

        $reviewModel = new ReviewModel();
        $review = $reviewModel->where('user_id', $userId)->where('game_id', $gameId)->first();

        if (! $review) {
            return redirect()->back()->with('error', 'Review tidak ditemukan atau bukan milik kamu.');
        }

        $reviewModel->update((int) $review['id'], [
            'rating' => (int) $postData['rating'],
            'comment' => (string) $postData['comment'],
        ]);

        return redirect()->back()->with('success', 'Review berhasil diperbarui.');
    }

    public function deleteReview(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $gameId = (int) $this->request->getPost('game_id');

        $reviewModel = new ReviewModel();
        $review = $reviewModel->where('user_id', $userId)->where('game_id', $gameId)->first();
        if (! $review) {
            return redirect()->back()->with('error', 'Review tidak ditemukan atau bukan milik kamu.');
        }

        $reviewModel->delete((int) $review['id']);

        return redirect()->back()->with('success', 'Review berhasil dihapus.');
    }

    public function games(): string
    {
        $allowedGenres = [
            'mmorpg', 'shooter', 'strategy', 'moba', 'racing', 'sports',
            'social', 'sandbox', 'open-world', 'survival', 'battle-royale',
        ];
        $allowedPlatforms = ['all', 'pc', 'browser'];
        $allowedSorts = ['release-date', 'popularity', 'alphabetical', 'relevance'];

        $search = trim((string) $this->request->getGet('search'));
        $genre = strtolower(trim((string) $this->request->getGet('genre')));
        $platform = strtolower(trim((string) $this->request->getGet('platform')));
        $sort = strtolower(trim((string) $this->request->getGet('sort')));

        if (! in_array($genre, $allowedGenres, true)) {
            $genre = '';
        }
        if (! in_array($platform, $allowedPlatforms, true)) {
            $platform = 'all';
        }
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'relevance';
        }

        $result = $sort === 'relevance'
            ? $this->freeToGameService->getGames()
            : $this->freeToGameService->getGamesSorted($sort);

        $games = $result['data'] ?? [];
        $totalApiGames = count($games);

        if ($genre !== '') {
            $games = array_values(array_filter($games, static function (array $game) use ($genre): bool {
                return isset($game['genre']) && strtolower((string) $game['genre']) === $genre;
            }));
        }

        if ($platform !== 'all') {
            $platformNeedle = $platform === 'pc' ? 'pc (windows)' : 'web browser';
            $games = array_values(array_filter($games, static function (array $game) use ($platformNeedle): bool {
                return isset($game['platform']) && stripos((string) $game['platform'], $platformNeedle) !== false;
            }));
        }

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $games = array_values(array_filter($games, static function (array $game) use ($needle): bool {
                return isset($game['title']) && str_contains(mb_strtolower((string) $game['title']), $needle);
            }));
        }

        $totalFilteredGames = count($games);
        $perPage = 30;
        $totalPages = max(1, (int) ceil($totalFilteredGames / $perPage));
        $page = max(1, (int) $this->request->getGet('page'));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;
        $games = array_slice($games, $offset, $perPage);

        return view('games/list', [
            'title' => 'NexaGames - Daftar Game',
            'games' => $games,
            'errorApi' => ! $result['ok'] && empty($games),
            'apiMessage' => $result['message'],
            'totalApiGames' => $totalApiGames,
            'totalFilteredGames' => $totalFilteredGames,
            'pagination' => [
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages,
            ],
            'filters' => [
                'search' => $search,
                'genre' => $genre,
                'platform' => $platform,
                'sort' => $sort,
            ],
            'genres' => $allowedGenres,
            'platforms' => $allowedPlatforms,
            'sorts' => $allowedSorts,
        ]);
    }
}
