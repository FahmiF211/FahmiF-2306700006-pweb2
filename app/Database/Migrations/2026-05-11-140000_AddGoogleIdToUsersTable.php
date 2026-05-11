<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleIdToUsersTable extends Migration
{
    public function up()
    {
        $fields = [];

        if (! $this->db->fieldExists('google_id', 'users')) {
            $fields['google_id'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'password',
            ];
        }

        if (! $this->db->fieldExists('photo', 'users')) {
            $fields['photo'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'google_id',
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('users', $fields);
        }

        $this->forge->modifyColumn('users', [
            'password' => [
                'name' => 'password',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('google_id', 'users')) {
            $this->forge->dropColumn('users', 'google_id');
        }

        $this->forge->modifyColumn('users', [
            'password' => [
                'name' => 'password',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
        ]);
    }
}
