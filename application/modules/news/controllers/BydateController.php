<?php

namespace modules\news\controllers;

use components\Controller;

class BydateController extends Controller
{
    public function indexAction($date, $id)
    {
        return ['date' => $date, 'id' => $id];
    }
}