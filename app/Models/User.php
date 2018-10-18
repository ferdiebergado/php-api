<?php

namespace App\Models;

use Core\BaseModel;
use Core\SessionManager;
use PDO;
use App\Services\UserService;

class User extends BaseModel
{
    protected $guarded = [
        'password'
    ];

    // public function find($id) {
    //     return $this->guard($this->db->row('SELECT * FROM users WHERE id = ?', $id));
    // }

    // public function update($id, array $fields)
    // {
    //     $updated = $this->db->update('users', $fields, ['id' => $id]);
    //     if ($updated) {
    //         cache_remember('user_' . $id, 30, $this->find($id));
    //         $this->updateSession($this->guard($fields));
    //         logger("User $id updated.", 1);
    //     }
    //     return $updated;
    // }

    public function login($email, $password) {
        $user = $this->db->row("SELECT * FROM users WHERE email = :email AND active = true", $email);
        $hash = $user['password'];
        if(isset($user['id']) && password_verify($password, $hash)) {
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $newhash = password_hash($password, PASSWORD_DEFAULT);
                $this->update($user['id'], ['password' => $newhash]);
            }
            $this->update($user['id'], ['last_login' => date(DATE_FORMAT_SHORT)]);
            $user = $this->guard($this->find($user['id']));
            UserService::updateSession($user);
            cache_remember('user_'.$user['id'], 30, $user);
            return true;
        }
        else {
            return false;
        }
    }
}
