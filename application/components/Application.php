<?php

namespace components;

use components\interfaces\IResponse;

/**
 * @class Application
 * Класс приложения
 */
class Application
{
    /**
     * Объект работы с конфигом
     * @var interfaces\IConfig
     */
    protected $config;

    /**
     * Объект для работы с роутингом
     * @var interfaces\IRouter
     */
    protected $router;

    /**
     * Имя текущего модуля
     * @var string
     */
    private $currentModuleName;

    /**
     * Имя текущего контроллера
     * @var string
     */
    private $currentControllerName;

    /**
     * Имя текущего экшена
     * @var string
     */
    private $currentActionName;

    /**
     * Конструктор. Прнимает объект для работы с конфигами и роутингом
     * @param interfaces\IConfig $config
     * @param interfaces\IRouter $router
     */
    public function __construct(interfaces\IConfig $config, interfaces\IRouter $router)
    {
        $this->config = $config;
        $this->router = $router;
    }

    /**
     * Запуск приложения: обработка запроса клиента
     */
    public function run()
    {
        try {
            $path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $config = $this->config->toArray();
            $routes = $config['routes'];
            list($module, $controller, $action, $params) =
                $this->router->processRoute($routes, $_SERVER['REQUEST_METHOD'], $path, $_REQUEST);

            $this->processRoute($module, $controller, $action, $params);
        } catch (\Exception $e) {
            $this->showError($e);
        }

    }

    /**
     * Возвращает имя теущего контроллера
     * @return string
     */
    public function getCurrentControllerName()
    {
        return $this->currentControllerName;
    }

    /**
     * Возвращает имя теущего модуля
     * @return string
     */
    public function getCurrentModuleName()
    {
        return $this->currentModuleName;
    }

    /**
     * Возвращает имя теущего действия
     * @return string
     */
    public function getCurrentActionName()
    {
        return $this->currentActionName;
    }

    /**
     * Обработка запроса (dispatching)
     * @param string $module     Имя модуля в котором расположен запускаемый контроллер
     * @param string $controller Имя запускаемого контроллера
     * @param string $action     Имя вызываемого действия
     * @param array  $params     Массив параметров, переданных как часть URL
     * @throws exception\Exception
     */
    private function processRoute($module, $controller, $action, array $params = [])
    {
        $className  = $this->createClassName($module, $controller);
        $actionName = strtolower($action) . 'Action';

        $this->currentModuleName     = $module;
        $this->currentControllerName = $controller;
        $this->currentActionName     = $action;
        /**
         * @var Controller $class
         */
        $class = new $className($this);
        /**
         * @var Response $reponse
         */
        if (!method_exists($class, $actionName) || !is_callable([$class, $actionName])) {
            throw new exception\Exception('Action not found!');
        }
        $response = call_user_func_array([$class, $actionName], $params);
        if (is_array($response)) {
            $response = $class->createResponse($response);
        } elseif (!is_object($response)) {
            $response = $class->createResponse([]);
        }
        if (is_object($response) && ($response instanceof IResponse)) {
            $response->show();
        } else {
            throw new exception\Exception(
                sprintf('Invalid response! Type: %s, object: %s', gettype($response), var_export($response, true))
            );
        }
    }

    /**
     * Возвращает имя класса контроллера который будет обрабатывать запрос
     * @param string $module Имя модуля
     * @param string $controller Имя контроллера
     * @return string
     */
    private function createClassName($module, $controller)
    {
        $result = [];
        if (!empty($module)) {
            $result = array_merge($result, ['modules', $module]);
        }
        $result = array_merge($result, ['controllers', ucfirst($controller) . 'Controller']);
        return implode('\\', $result);
    }

    /**
     * Отображает данные об ошибке
     * @param \Exception $e
     */
    private function showError(\Exception $e)
    {
        $trace    = str_replace("\n", '<br />', $e->getTraceAsString());
        $response = new Response(APPLICATION_PATH . '/views/error.tpl', [
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $trace
        ], 500);
        $response->show();
    }
}