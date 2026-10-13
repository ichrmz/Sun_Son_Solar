<?php

namespace App\Database\Migrations;

use App\Database\UserColumns;
use CodeIgniter\Database\Migration;

class CreateAdminsTable extends Migration
{
    public function up(): void
    {
        $fields = UserColumns::base();
        $fields = array_merge($fields, UserColumns::timestamps());

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);          // primary key
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('admins');
    }

    public function down(): void
    {
        $this->forge->dropTable('admins', true);
    }
}
