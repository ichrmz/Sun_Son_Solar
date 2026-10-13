<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table         = 'admins';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'first_name', 'middle_name', 'last_name', 'birthdate', 'gender',
        'email', 'phone', 'address', 'username', 'password_hash',
    ];
}
