<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // Skip if the table is already there (for example, imported from the SQL file)
        if ($this->db->tableExists('users')) {
            return;
        }

        $this->forge->addField([
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['customer', 'employee', 'admin'],
                'default'    => 'customer',
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
