<?php
namespace App\Controllers;

class Controller
{

    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}
