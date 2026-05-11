<?php

namespace App\Services;

use CodeIgniter\HTTP\CURLRequest;

class FreeToGameService
{
    private CURLRequest $client;
    private int $cacheTtl = 600;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest([
            'baseURI' => 'https://www.freetogame.com/api/',
            'timeout' => 20,
            'verify' => false,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'NexaGames-CI4',
            ],
        ]);

        try {
            $cache = cache();
            if (method_exists($cache, 'deleteMatching')) {
                $cache->deleteMatching('freetogame_*');
            }
        } catch (\Throwable $e) {
            log_message('error', 'FreeToGame cache cleanup error: ' . $e->getMessage());
        }
    }

    public function getGames(): array
    {
        $result = $this->request('games');

        return [
            'ok' => $result['ok'],
            'message' => $result['ok'] ? 'Berhasil mengambil data game.' : $result['message'],
            'data' => $result['data'],
        ];
    }

    public function getGameDetail(int $id): array
    {
        return $this->request('game', ['id' => $id]);
    }

    public function getGamesByCategory(string $category): array
    {
        return $this->request('games', ['category' => trim($category)]);
    }

    public function getGamesByPlatform(string $platform): array
    {
        return $this->request('games', ['platform' => trim($platform)]);
    }

    public function getGamesSorted(string $sort): array
    {
        return $this->request('games', ['sort-by' => trim($sort)]);
    }

    private function request(string $endpoint, array $query = []): array
    {
        $cacheKey = $this->buildCacheKey($endpoint, $query);
        $cached = cache()->get($cacheKey);

        if (is_array($cached)) {
            return [
                'ok' => true,
                'message' => 'Berhasil mengambil data game.',
                'data' => $cached,
            ];
        }

        try {
            $response = $this->client->get($endpoint, ['query' => $query]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('Status server game tidak valid: ' . $response->getStatusCode());
            }

            $decoded = json_decode($response->getBody(), true);
            if (! is_array($decoded)) {
                throw new \RuntimeException('Format respons server game tidak valid.');
            }

            cache()->save($cacheKey, $decoded, $this->cacheTtl);

            return [
                'ok' => true,
                'message' => 'Berhasil mengambil data game.',
                'data' => $decoded,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'FreeToGame server error: ' . $e->getMessage());

            $fallback = $this->getFallbackData($endpoint, $query);

            return [
                'ok' => false,
                'message' => 'Data game dari server utama gagal dimuat, menampilkan data cadangan.',
                'data' => $fallback,
            ];
        }
    }

    private function buildCacheKey(string $endpoint, array $query = []): string
    {
        return 'freetogame_v2_' . md5($endpoint . '_' . json_encode($query));
    }

    private function getFallbackData(string $endpoint, array $query): array
    {
        $games = [
            [
                'id' => 540,
                'title' => 'Overwatch 2',
                'thumbnail' => 'https://www.freetogame.com/g/540/thumbnail.jpg',
                'short_description' => 'Hero shooter cepat dengan pertarungan tim 5v5.',
                'genre' => 'Shooter',
                'platform' => 'PC (Windows)',
                'publisher' => 'Activision Blizzard',
                'developer' => 'Blizzard Entertainment',
                'release_date' => '2022-10-04',
                'game_url' => 'https://www.freetogame.com/open/overwatch-2',
            ],
            [
                'id' => 516,
                'title' => 'PUBG: BATTLEGROUNDS',
                'thumbnail' => 'https://www.freetogame.com/g/516/thumbnail.jpg',
                'short_description' => 'Battle royale realistis dengan skala pertempuran besar.',
                'genre' => 'Battle Royale',
                'platform' => 'PC (Windows)',
                'publisher' => 'KRAFTON',
                'developer' => 'KRAFTON',
                'release_date' => '2017-12-20',
                'game_url' => 'https://www.freetogame.com/open/pubg',
            ],
            [
                'id' => 452,
                'title' => 'Call Of Duty: Warzone',
                'thumbnail' => 'https://www.freetogame.com/g/452/thumbnail.jpg',
                'short_description' => 'Battle royale modern dengan mode squad yang intens.',
                'genre' => 'Shooter',
                'platform' => 'PC (Windows)',
                'publisher' => 'Activision',
                'developer' => 'Infinity Ward',
                'release_date' => '2020-03-10',
                'game_url' => 'https://www.freetogame.com/open/call-of-duty-warzone',
            ],
            [
                'id' => 57,
                'title' => 'Fortnite',
                'thumbnail' => 'https://www.freetogame.com/g/57/thumbnail.jpg',
                'short_description' => 'Battle royale bergaya kartun dengan mekanik bangun unik.',
                'genre' => 'Battle Royale',
                'platform' => 'PC (Windows)',
                'publisher' => 'Epic Games',
                'developer' => 'Epic Games',
                'release_date' => '2017-09-26',
                'game_url' => 'https://www.freetogame.com/open/fortnite',
            ],
            [
                'id' => 3,
                'title' => 'Warframe',
                'thumbnail' => 'https://www.freetogame.com/g/3/thumbnail.jpg',
                'short_description' => 'Aksi co-op sci-fi cepat dengan karakter ninja luar angkasa.',
                'genre' => 'Shooter',
                'platform' => 'PC (Windows)',
                'publisher' => 'Digital Extremes',
                'developer' => 'Digital Extremes',
                'release_date' => '2013-03-25',
                'game_url' => 'https://www.freetogame.com/open/warframe',
            ],
            [
                'id' => 515,
                'title' => 'Lost Ark',
                'thumbnail' => 'https://www.freetogame.com/g/515/thumbnail.jpg',
                'short_description' => 'MMORPG action dengan dungeon dan raid epik.',
                'genre' => 'MMORPG',
                'platform' => 'PC (Windows)',
                'publisher' => 'Amazon Games',
                'developer' => 'Smilegate RPG',
                'release_date' => '2022-02-11',
                'game_url' => 'https://www.freetogame.com/open/lost-ark',
            ],
        ];

        if ($endpoint === 'game' && isset($query['id'])) {
            foreach ($games as $game) {
                if ((int) $game['id'] === (int) $query['id']) {
                    $game['description'] = $game['short_description'];
                    return $game;
                }
            }

            $first = $games[0];
            $first['id'] = (int) $query['id'];
            $first['description'] = $first['short_description'];

            return $first;
        }

        return $games;
    }
}
