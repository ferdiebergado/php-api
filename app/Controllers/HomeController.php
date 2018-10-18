<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Zend\Diactoros\Response\JsonResponse;

class HomeController extends Controller
{
    public function home()
    {
        return new JsonResponse(['data' => 'Welcome to PHP API by ferdie!']);
    }

    public function send_mail()
    {
        ob_start();
        // include VIEW_PATH . 'sections/bsheader.php';
        include VIEW_PATH . 'email.php';
        // include VIEW_PATH . 'sections/bsfooter.php';
        $m = ob_get_contents();
        ob_end_clean();
        \Core\Mail::send('ferdiebergado@yahoo.com', 'Test email', $m);
        return header('Location: /');
    }
}
