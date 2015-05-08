<?php
namespace components\interfaces;

/**
 * Interface IRouter
 * Интерфейс для класса роутинга
 */
interface IRouter
{
    public function processRoute(array $routes, $method, $path);
}