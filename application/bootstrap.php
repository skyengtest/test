<?php

define('DS', DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', realpath(__DIR__));
define('CONFIGS_PATH', realpath(APPLICATION_PATH . DS . 'configs'));
define('MODULES_PATH', realpath(APPLICATION_PATH . DS . 'modules'));

set_include_path(
    implode(
        PATH_SEPARATOR,
        [
            realpath(APPLICATION_PATH),
            get_include_path(),
        ]
    )
);

spl_autoload_register(
    function ($class) {
        $founded = false;
        foreach (explode(PATH_SEPARATOR, get_include_path()) as $basePath) {
            $path = $basePath . DIRECTORY_SEPARATOR
                . implode(preg_split('/[_\\\]/', (string) $class, -1, PREG_SPLIT_NO_EMPTY), DIRECTORY_SEPARATOR)
                . '.php';


            if (is_file($path) && is_readable($path)) {
                require_once $path;
                $founded = true;
                break;
            }
        }
        if (!$founded) {
            throw new Exception(sprintf('Class %s not found!', $class));
        }
    }
);