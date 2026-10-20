<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table         = 'customers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'first_name', 'middle_name', 'last_name', 'birthdate', 'gender',
        'email', 'phone', 'address', 'username', 'password', 'is_active', 'created_at',
    ];
}
