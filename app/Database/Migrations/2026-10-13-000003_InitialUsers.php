<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialUsers extends Migration
{
    public function up()
    {
        // The two first users from the client interview: Kat and Sol (both admin).
        // Passwords are saved as hashes, never as plain text.
        $users = [
            [
                'username' => 'KittyKat16',
                'password' => '$2b$10$Gyvaul9tOXTQo7Cbko7bAuFUEjUPVoq0qxTSPc5fbkjvoc1CgW566',
                'role'     => 'admin',
            ],
            [
                'username' => 'admin',
                'password' => '$2b$10$qAP..5dTgyfk27fZx1UnmeVqnJO1Jemsw64RU5IxRYCg5sqpk1J7.',
                'role'     => 'admin',
            ],
        ];

        foreach ($users as $user) {
            $exists = $this->db->table('users')->where('username', $user['username'])->countAllResults();

            if ($exists === 0) {
                $this->db->table('users')->insert($user);
            }
        }
    }

    public function down()
    {
        $this->db->table('users')->whereIn('username', ['KittyKat16', 'admin'])->delete();
    }
}
