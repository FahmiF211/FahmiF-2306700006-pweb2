<?php

namespace App\Controllers;

use App\Services\FreeToGameService;

class GameController extends BaseController
{
    private FreeToGameService $freeToGameService;

    public function __construct()
    {
        $this->freeToGameService = new FreeToGameService();
    }

    public function index(): string
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

        $allGames = $result['data'] ?? [];
        $totalApiGames = count($allGames);

        if ($genre !== '') {
            $allGames = array_values(array_filter($allGames, static function (array $game) use ($genre): bool {
                return isset($game['genre']) && strtolower((string) $game['genre']) === $genre;
            }));
        }

        if ($platform !== 'all') {
            $platformNeedle = $platform === 'pc' ? 'pc (windows)' : 'web browser';
            $allGames = array_values(array_filter($allGames, static function (array $game) use ($platformNeedle): bool {
                return isset($game['platform']) && stripos((string) $game['platform'], $platformNeedle) !== false;
            }));
        }

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $allGames = array_values(array_filter($allGames, static function (array $game) use ($needle): bool {
                return isset($game['title']) && str_contains(mb_strtolower((string) $game['title']), $needle);
            }));
        }

        $totalFilteredGames = count($allGames);

        $perPage = 30;
        $totalPages = max(1, (int) ceil($totalFilteredGames / $perPage));
        $page = max(1, (int) $this->request->getGet('page'));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;
        $games = array_slice($allGames, $offset, $perPage);

        return view('games/list', [
            'title' => 'NexaGames - Daftar Game',
            'games' => $games,
            'errorApi' => ! $result['ok'] && empty($games),
            'apiMessage' => $result['message'] ?? '',
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

    public function detail($id)
    {
        $result = $this->freeToGameService->getGameDetail((int) $id);
        $game = $result['data'] ?? null;

        if (! $game || empty($game['id'])) {
            return redirect()->to(site_url('/'))->with('error', 'Game tidak ditemukan atau server game sedang bermasalah.');
        }

        return view('games/detail', [
            'game' => $game,
            'title' => $game['title'] ?? 'Detail Game',
            'reviews' => [],
            'userReview' => null,
            'ratingSummary' => ['avg_rating' => 0, 'total_review' => 0],
            'isLoggedIn' => (bool) session()->get('user_id'),
            'isFavorited' => false,
        ]);
    }
}
