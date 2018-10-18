<?php
namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;

class LoginController extends BaseController {

    private $user;

    public function __construct(User $user) {
        $this->middleware = 'guest';
        $this->user = $user;
    }

    public function showLoginForm()
    {
        require VIEW_PATH . 'login.php';
    }

    public function login() {
        if (empty($_POST["email"])) {
            $_SESSION['errors']['email'] = 'Email is required';
        } else {
            $email = $_POST["email"];
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['errors']['email'] = "Invalid email format";
            }
            $_SESSION['email'] = $email;
        }

        if (empty($_POST["password"])) {
            $_SESSION['errors']['password'] = "Password is required";
        } else {
            $password = $_POST["password"];
        }

        if (empty($_SESSION['errors']['email']) && empty($_SESSION['errors']['password'])) {
            if (verify_token($_POST['csrf_token'])) {
                $success = $this->user->login($email, $password);
                if (!$success) {
                    $_SESSION['errors']['email'] = 'Invalid username or password.';
                }
            } else {
                $_SESSION['message']['title'] = "Session expired. Please login again.";
                $_SESSION['message']['type'] = "error";
            }
        }
        return header('Location: /');
    }
    public static function logout() {
        if (verify_token($_POST['csrf_token'])) {
            $_SESSION = array();
            session_destroy();
            // header('Cache-Control: nocache, no-store, max-age=0, must-revalidate', false);
            // header('Pragma: no-cache', false);
            // header('Expires: Sun, 02 Jan 1990 00:00:00 GMT', false);
            return header('Location: /', false);
        }
    }
}
