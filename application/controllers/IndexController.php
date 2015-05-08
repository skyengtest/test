<?php

namespace controllers;

use components\Controller;

class IndexController extends Controller
{
    public function defaultAction()
    {
    }

    public function helpAction()
    {
        $now          = time();
        $daysInterval = 10;
        return [
            'HOST' => $_SERVER['HTTP_HOST'],
            'date' => date('d.m.Y', rand($now - $daysInterval * 86400, $now + $daysInterval * 86400)),
            'id'   => rand(1, 10000)
        ];
    }
}