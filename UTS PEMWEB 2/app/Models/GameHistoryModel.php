<?php

namespace App\Models;

use CodeIgniter\Model;

class GameHistoryModel extends Model
{
    protected $table = 'game_histories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'game_id',
        'title',
        'genre',
        'platform',
        'thumbnail',
        'fetched_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
