<?php

namespace App\Database;

/**
 * Columns shared by customers, employees and admins (per the client interview).
 * Each user table gets its own primary key, `id`.
 */
class UserColumns
{
    public static function base(): array
    {
        return [
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'first_name'    => ['type' => 'VARCHAR', 'constraint' => 60],
            'middle_name'   => ['type' => 'VARCHAR', 'constraint' => 60],
            'last_name'     => ['type' => 'VARCHAR', 'constraint' => 60],
            'birthdate'     => ['type' => 'DATE'],
            'gender'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 120],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'address'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'username'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
    }

    public static function timestamps(): array
    {
        return [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
    }
}
