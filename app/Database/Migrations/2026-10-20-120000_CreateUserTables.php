<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTables extends Migration
{
    public function up()
    {
        $common = [
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'first_name'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'middle_name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'last_name'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'birthdate'   => ['type' => 'DATE'],
            'gender'      => ['type' => 'VARCHAR', 'constraint' => 20],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone'       => ['type' => 'VARCHAR', 'constraint' => 11],
            'address'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'username'    => ['type' => 'VARCHAR', 'constraint' => 30],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        foreach (['customers', 'employees'] as $table) {
            $fields = $common;

            if ($table === 'employees') {
                $fields['department'] = ['type' => 'VARCHAR', 'constraint' => 30];
                $fields['role']       = ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'employee'];
            }

            $fields['is_active']  = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1];
            $fields['created_at'] = ['type' => 'DATETIME'];

            $this->forge->addField($fields);
            $this->forge->addKey('id', true);          // PRIMARY KEY
            $this->forge->addUniqueKey('email');
            $this->forge->addUniqueKey('username');
            $this->forge->createTable($table);
        }
    }

    public function down()
    {
        $this->forge->dropTable('employees', true);
        $this->forge->dropTable('customers', true);
    }
}
