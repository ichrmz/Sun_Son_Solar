<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('customers')) {
            return;
        }

        $this->forge->addField([
            'customer_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'firstname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'middlename' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'birthdate' => [
                'type' => 'DATE',
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female', 'Prefer not to say'],
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'phonenum' => [
                'type'       => 'VARCHAR',
                'constraint' => 11,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
        ]);

        $this->forge->addKey('customer_id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', '', 'CASCADE');
        $this->forge->createTable('customers', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('customers', true);
    }
}
