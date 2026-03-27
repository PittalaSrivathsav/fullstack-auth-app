<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'auth_user';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'email',
        'first_name',
        'last_name',
        'password'
    ];
}