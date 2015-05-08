<?php

namespace components;

/**
 * @class Router
 * Класс для роутинга запросов
 */
class Router implements interfaces\IRouter
{
    const DEFAULT_MODULE     = '';
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION     = 'index';

    /**
     * Выбор обработчика поступившего URL
     * @param array  $routes Массив маршрутов из конфига
     * @param string $method Тип поступившего запроса (GET/POST/...)
     * @param string $path   Путь из запроса
     * @throws exception\Exception
     * @return array
     *
     * Массив маршрутов представляет собой ассоциативный массив, где:
     *      ключ - регулярное выражение которому должен соответствовать запрос
     *      значение - массив и информацией о обработчике.
     *
     * @example:
     * ...
     * '^/articles/(\d+)/?$' => [
     *      'route' => 'news/bydate/',
     *      'method' => 'GET'
     * ]
     * ...
     *
     * Регулярному выражению будут соответствовать такие URL:
     *      /articles/123/
     *      /articles/0?x=1&y=2
     * и не будут соответсвовать:
     *      /articles/
     *      /articles/test/
     *
     * Если поступившый URL соответствует очередному регулярному выражению, проверяется тип запрос - GET/POST
     * В данном примере - обрабатываются только GET-запросы.
     * Если это условие также выполняется - перебор завершается - совпадение найдено, иначе - идет перебор дальше.
     * Если в конце перебора не найден ни один подходящий вариант, но есть секция с ключом в виде пустой строки - онабудет использована по-умолчанию
     * Если секций с пустым ключем несколько - будет использована первая из них.
     *
     * Далее берется элемент 'route', в нашем примере:
     *         'route' => 'news/bydate/',
     * этот элемент записывается в виде {модуль}/{контроллер}/{действие}
     * Если не указан модуль - будет использован основной модуль (контроллеры их application/controllers)
     * Если не указан контроллер - будет использован контроллер по-умолчанию - IndexController
     * Последнее верно и для действий - если не указано - используется indexAction
     *
     * Т.е.:
     *         //test - IndexController::testAction()
     *         users/manager/list - Модуль users, ManagersController::listAction()
     *
     * Если в регулярном выражении находятся подстановки - они будут переданы в соотв. метод в качестве параметров.
     * Например:
     * '^/news/(\d+)/(\d+)/?$' => [
     *      'route' => 'news/index/show',
     *      'method' => 'GET'
     * ]
     *
     * При поступившем URL = /news/123/456/
     * в модуле news будет вызван метод showAction класса IndexController с 2 параметрами - 123 и 456:
     *         IndexController::showAction(123, 456)
     */
    public function processRoute(array $routes, $method, $path)
    {
        $method  = strtolower($method);
        $default = $pathParams = [];
        $founded = false;
        foreach ($routes as $pattern => $info) {
            if (empty($pattern) && empty($default)) {
                $default = $info;
            } else {
                if (preg_match('|' . $pattern . '|', $path, $matches)) {
                    $itemMetod = !empty($info['method']) ? strtolower($info['method']) : null;
                    if (($itemMetod !== null && $itemMetod === $method) || ($itemMetod === null)) {
                        $founded = $info;
                        array_shift($matches);
                        $pathParams = $matches;
                        break;
                    }
                }
            }
        }
        $default = null;
        if ((false === $founded) && !empty($default)) {
            $founded = $default;
        }
        if (!is_array($founded)) {
            throw new exception\Exception('Routing failed!');
        }
        $result   = $this->parseRoute($founded);
        $result[] = $pathParams;
        return $result;
    }

    /**
     * Приводит маршрут обработчика к нормальному виду
     * @param array $info
     * @return array
     */
    private function parseRoute(array $info)
    {
        $route  = !empty($info['route']) ? $info['route'] : null;
        $module = $controller = $action = null;
        if ($route !== null) {
            list($module, $controller, $action) = explode('/', $route);
        }
        if (empty($module)) {
            $module = self::DEFAULT_MODULE;
        }
        if (empty($controller)) {
            $controller = self::DEFAULT_CONTROLLER;
        }
        if (empty($action)) {
            $action = self::DEFAULT_ACTION;
        }
        return [$module, $controller, $action];
    }
}