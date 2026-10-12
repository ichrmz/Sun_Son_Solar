<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $allowedFields = [
        'user_id', 'firstname', 'middlename', 'lastname', 'birthdate',
        'gender', 'email', 'phonenum', 'address'
    ];
}
