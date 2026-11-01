<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'full_name', 'email', 'password', 'created_at'];

    // The single demo user.
    public function getDemoUser(): ?array
    {
        return $this->first();
    }

    // Finds one user by username (used by the login page).
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}
