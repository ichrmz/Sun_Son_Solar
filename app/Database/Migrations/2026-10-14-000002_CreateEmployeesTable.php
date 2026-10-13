<?php

namespace App\Database\Migrations;

use App\Database\UserColumns;
use CodeIgniter\Database\Migration;

class CreateEmployeesTable extends Migration
{
    public function up(): void
    {
        $fields = UserColumns::base();
        // Employees also have a department (Administration, IT, Dispatch, Accounting, HR, Marketing, Sales, Customer Service).
        $fields = array_merge($fields, ['department' => ['type' => 'VARCHAR', 'constraint' => 40]]);
        $fields = array_merge($fields, UserColumns::timestamps());

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);          // primary key
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('employees');
    }

    public function down(): void
    {
        $this->forge->dropTable('employees', true);
    }
}
