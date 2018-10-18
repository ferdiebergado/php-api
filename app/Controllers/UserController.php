<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use App\Services\UserService;

class UserController extends BaseController
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->middleware('auth', 'active');
        $this->service = $service;
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function edit()
    {
        $title = 'Edit User';
        return view('user/edit', compact('title'));
    }

    public function update($param)
    {
        if (empty($_POST["name"])) {
            $_SESSION['errors']['name'] = 'Name is required';
        } else {
            $name = $_POST["name"];
            $_SESSION['name'] = $name;
        }

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
        if (empty($_SESSION['errors'])) {
            if ($this->service->update($param['id'], compact('name', 'email'))) {
                return header('Location: /');
            }
        } else {
            return back();
        }
    }
}
