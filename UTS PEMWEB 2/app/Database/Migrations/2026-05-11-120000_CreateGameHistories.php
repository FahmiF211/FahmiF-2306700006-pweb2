<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGameHistories extends Migration
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
            'game_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'genre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'platform' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'thumbnail' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fetched_at' => [
                'type' => 'DATETIME',
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
        $this->forge->createTable('game_histories');
    }

    public function down()
    {
        $this->forge->dropTable('game_histories');
    }
}
