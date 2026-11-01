<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('users')->truncate();
        $this->db->table('users')->insert([
            'username'   => 'jdelacruz',
            'full_name'  => 'Juan Dela Cruz',
            'email'      => 'juan.delacruz@example.com',
            // Demo login password is "password123". Only the hash is stored.
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
