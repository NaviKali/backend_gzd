<?php

namespace app\user\controller;

use app\View;

class Index extends View
{
    public function index()
    {
        return view("/index", $this->data);
    }
}