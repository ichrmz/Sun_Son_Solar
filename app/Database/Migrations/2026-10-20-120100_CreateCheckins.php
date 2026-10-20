<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCheckins extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'employee_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 101],
            'username'      => ['type' => 'VARCHAR', 'constraint' => 30],
            'latitude'      => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'longitude'     => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'accuracy'      => ['type' => 'FLOAT', 'null' => true],
            'location_note' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'client_ref'    => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'checked_in_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('employee_id');
        $this->forge->addUniqueKey(['employee_id', 'client_ref']);
        $this->forge->createTable('checkins');
    }

    public function down()
    {
        $this->forge->dropTable('checkins', true);
    }
}
