<?php

namespace modules\test\controllers;

use components\Controller;

class IndexController extends Controller
{
    public function getAction()
    {
        return [
            'type'   => 'GET',
            'params' => $this->getParamsField()
        ];
    }

    public function postAction()
    {
        return $this->createResponse(
            [
                'type'   => 'POST',
                'params' => $this->getParamsField()
            ]
        )->setViewPath(MODULES_PATH . '/test/views/index/get.tpl');
    }

    private function getParamsField()
    {
        $result = [];
        foreach ($this->getParams() as $key => $value) {
            $result[] = "{$key} => {$value}";
        }
        return implode('<br />', $result);
    }
}