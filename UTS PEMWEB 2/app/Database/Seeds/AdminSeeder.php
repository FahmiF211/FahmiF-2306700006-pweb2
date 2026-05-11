<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $email = 'admin@nexagames.test';

        $existing = $this->db->table('users')->where('email', $email)->get()->getRowArray();
        if ($existing) {
            return;
        }

        $this->db->table('users')->insert([
            'name' => 'Administrator NexaGames',
            'email' => $email,
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'photo' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
