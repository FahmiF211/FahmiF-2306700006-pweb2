<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleAuthColumnsToUsersTable extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('google_id', 'users')) {
            $this->forge->addColumn('users', [
                'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'password',
                ],
            ]);
        }

        if (! $this->db->fieldExists('avatar', 'users')) {
            $this->forge->addColumn('users', [
                'avatar' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'google_id',
                ],
            ]);
        }

        if (! $this->db->fieldExists('login_provider', 'users')) {
            $this->forge->addColumn('users', [
                'login_provider' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'avatar',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('login_provider', 'users')) {
            $this->forge->dropColumn('users', 'login_provider');
        }

        if ($this->db->fieldExists('avatar', 'users')) {
            $this->forge->dropColumn('users', 'avatar');
        }

        if ($this->db->fieldExists('google_id', 'users')) {
            $this->forge->dropColumn('users', 'google_id');
        }
    }
}
