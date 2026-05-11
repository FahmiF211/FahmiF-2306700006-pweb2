<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFavoriteGamesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'game_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'game_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'game_thumbnail' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'game_genre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'game_platform' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey(['user_id', 'game_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('favorite_games');
    }

    public function down()
    {
        $this->forge->dropTable('favorite_games');
    }
}
