<?php
namespace App\Controllers;

class BaseController {
    protected $middleware = [];

    protected function middleware($middleware, array $options = []) {
        foreach ((array) $middleware as $m) {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => &$options
            ];
        }
    }

    protected function executeMiddleware() {
        // foreach ($types as $type) {
        //     switch ($type) {
        //         case 'guest':
        //             if (!isset($_SESSION['isLoggedIn'])) {
        //                 return header('Location: /login');
        //             }
        //             break;
        //         case 'auth':
        //             if (!isset($_SESSION['isLoggedIn'])) {
        //                 return header('Location: /');
        //             }
        //             break;
        //         case 'active':
        //             if ($_SESSION['isLoggedIn'] && !$_SESSION['USER_ACTIVE']) {
        //                 App\Controllers\LoginController::logout();
        //                 return header('Location: /');
        //             }
        //             break;
        //         default:
        //             # code...
        //             break;
        //     }
        // }
    }
    /**
     * Set the controller methods the middleware should apply to.
     *
     * @param  array|string|dynamic  $methods
     * @return $this
     */
    public function only($methods)
    {
        $this->middlewareoptions['only'] = is_array($methods) ? $methods : func_get_args();

        return $this;
    }

    /**
     * Set the controller methods the middleware should exclude.
     *
     * @param  array|string|dynamic  $methods
     * @return $this
     */
    public function except($methods)
    {
        $this->options['except'] = is_array($methods) ? $methods : func_get_args();

        return $this;
    }

    /**
     * Determine if the given options exclude a particular method.
     *
     * @param  string  $method
     * @param  array  $options
     * @return bool
     */
    protected static function methodExcludedByOptions($method, array $options)
    {
        return (isset($options['only']) && ! in_array($method, (array) $options['only'])) ||
            (! empty($options['except']) && in_array($method, (array) $options['except']));
    }

}
