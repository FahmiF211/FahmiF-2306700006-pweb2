<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoriteGameModel extends Model
{
    protected $table = 'favorite_games';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'user_id',
        'game_id',
        'game_title',
        'game_thumbnail',
        'game_genre',
        'game_platform',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
