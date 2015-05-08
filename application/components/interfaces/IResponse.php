<?php
namespace components\interfaces;

/**
 * Interface IResponse
 * Интерфейс для класса ответа клиенту
 */
interface IResponse
{
    public function __construct($viewPath, array $params = [], $statusCode = 200);

    public function setViewPath($viewPath);

    public function setParams(array $params);

    public function setStatusCode($statusCode);

    public function show();
}