<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBannersTable extends Migration
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
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'subtitle' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'button_text' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'button_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
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
        $this->forge->createTable('banners');
    }

    public function down()
    {
        $this->forge->dropTable('banners');
    }
}
