<?php

namespace App\Database\Seeds;

use App\Models\AdminModel;
use App\Models\CustomerModel;
use CodeIgniter\Database\Seeder;

/**
 * Initial users from the client interviews.
 * Passwords are stored as bcrypt hashes only (this repository is public, never commit plain text passwords).
 */
class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        $customers = new CustomerModel();
        $admins    = new AdminModel();

        // Kat Sinagaraw (founder), registered as a CUSTOMER account.
        if (! $customers->where('username', 'KittyKat16')->first()) {
            $customers->insert([
                'first_name'    => 'Katherine',
                'middle_name'   => 'Olap',
                'last_name'     => 'Sinagaraw',
                'birthdate'     => '1990-07-01',
                'gender'        => 'Female',                          // ASSUMPTION: not stated in the interview, confirm
                'email'         => 'katherine.sinagaraw@sunsonsolar.com',
                'phone'         => '09291230983',                     // company number, per Kat
                'address'       => 'Pasig City',                      // ASSUMPTION: only the first office location was given, confirm
                'username'      => 'KittyKat16',
                'password_hash' => '$2y$10$wX0yLP9aUPDAyWR6JMIC2O8hncFjQrcBbnO0vWCYNAwOOIP7DaPVC',
            ]);
        }

        // Sol Solis (IT head), registered as an ADMIN account. He will change the password after handover.
        if (! $admins->where('username', 'admin')->first()) {
            $admins->insert([
                'first_name'    => 'Sol',
                'middle_name'   => 'Sun',                              // second interview says "Sol Sun Solis", confirm
                'last_name'     => 'Solis',
                'birthdate'     => '1967-01-08',
                'gender'        => 'Male',
                'email'         => 'sol.solis@sunsonsolar.com',        // ASSUMPTION: transcript cuts off at "sol.solis", confirm domain
                'phone'         => '09291230983',                      // company number, "same with Sol"
                'address'       => 'Pasig City',                       // ASSUMPTION, confirm
                'username'      => 'admin',
                'password_hash' => '$2y$10$bzTqupXkew8fhV.qj/ZrGeTNTCmnrMpY9AEs44d1C/a1/L6m4ABwG',
            ]);
        }
    }
}
