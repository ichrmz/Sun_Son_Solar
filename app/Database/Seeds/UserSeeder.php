<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Initial users from the client interview (Kat + Sol, both admins).
        // Passwords are stored HASHED, never plain text.
        $this->db->table('employees')->ignore(true)->insertBatch([
            [
                'first_name'  => 'Katherine',
                'middle_name' => 'Olap',
                'last_name'   => 'Sinagaraw',
                'birthdate'   => '1990-07-01',
                'gender'      => 'Female',
                'email'       => 'katherine.sinagaraw@sunsonsolar.com',
                'phone'       => '09291230983',
                'address'     => 'Sun Son Solar Office, Pasig City',
                'username'    => 'KittyKat16',
                'password'    => password_hash('K@tSunShine16', PASSWORD_DEFAULT),
                'department'  => 'Administration',
                'role'        => 'admin',
                'is_active'   => 1,
                'created_at'  => $now,
            ],
            [
                'first_name'  => 'Sol',
                'middle_name' => 'Sun',
                'last_name'   => 'Solis',
                'birthdate'   => '1967-01-08',
                'gender'      => 'Male',
                'email'       => 'sol.solis@sunsonsolar.com',
                'phone'       => '09291230983',
                'address'     => 'Sun Son Solar Office, Pasig City',
                'username'    => 'admin',
                'password'    => password_hash('admin123', PASSWORD_DEFAULT),
                'department'  => 'IT',
                'role'        => 'admin',
                'is_active'   => 1,
                'created_at'  => $now,
            ],
        ]);
    }
}
