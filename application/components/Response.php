<?php

namespace components;

/**
 * @class Response
 * Класс ответа клиенту
 */
class Response implements interfaces\IResponse
{
    /**
     * Путь к файлу представления
     * @var string
     */
    private $viewPath;

    /**
     * Параметры передаваемые представлению
     * @var string
     */
    private $params;

    /**
     * HTTP-статус ответа
     * @var int
     */
    private $statusCode;

    public function __construct($viewPath, array $params = [], $statusCode = 200)
    {
        $this->viewPath   = $viewPath;
        $this->params     = $params;
        $this->statusCode = $statusCode;
    }

    /**
     * Отдает ответ клиенту
     * @throws exception\ViewNotFound
     */
    public function show()
    {
        $viewPath = $this->viewPath;
        if (is_readable($viewPath)) {
            $content = file_get_contents($viewPath);
        } else {
            throw new exception\ViewNotFound('View not found: ' . $viewPath);
        }

        foreach ($this->params as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        http_response_code($this->statusCode);
        echo $content;
    }

    /**
     * Устанавливает путь к файлу представления
     * @param string $viewPath Путь к файлу представления
     * @return $this
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
        return $this;
    }

    /**
     * Устанавливает параметры представления
     * @param array $params параметры представления
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Устанавливает статус ответа
     * @param int $statusCode HTTP-статус ответа
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}