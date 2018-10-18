<?php
namespace App\Services;

use App\Models\User;
use Core\SessionManager;

class UserService
{
    private static $user;
    public function __construct(User $user)
    {
        self::$user = $user;
    }
    public static function update($id, array $fields)
    {
        if (self::$user->update($id, $fields)) {
            $user = self::$user->find($id);
            cache_remember('user_' . $id, 30, $user);
            self::updateSession(self::$user->guard($user));
            logger("User $id updated.", 1);
            $_SESSION['message']['title'] = 'User updated.';
            $_SESSION['message']['type'] = 'success';
            return true;
        }
    }
    public static function updateSession(array $fields)
    {
        SessionManager::regenerateSession();
        foreach ($fields as $key => $value) {
            $_SESSION['USER_' . strtoupper($key)] = htmlspecialchars($value);
        }
        $_SESSION['isLoggedIn'] = true;
    }
    public function show($id)
    {
        return $this->user->find($id);
    }
}
