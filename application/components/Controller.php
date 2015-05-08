<?php

namespace components;

/**
 * @class Controller
 * Базовый класс контроллера
 */
class Controller
{
    // Имя каталога в котором хранятся представления
    const VIEW_DIR_NAME = 'views';

    /**
     * Ссылка на приложение
     * @var Application
     */
    protected $app;

    /**
     * Объект формирующий ответ клиенту
     * @var Response
     */
    private $response;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Создает экземпляр объекта ответа
     * @param array $params Массив параметров передавыаемых в представление
     * @param int   $statusCode HTTP-статус ответа
     * @return Response
     */
    public function createResponse(array $params = [], $statusCode = 200)
    {
        if ($this->response === null) {
            $this->response = new Response($this->getViewPath(), $params, $statusCode);
        }
        return $this->response;
    }

    /**
     * Возвращает ссылку на приложение
     * @return Application
     */
    protected function getApp()
    {
        return $this->app;
    }

    /**
     * Возвращает путь к файлу представления
     * По-умолчанию файл представления хранится в модуле контроллера, и имеет путь вида views/{controllerName}/{viewName}
     * @return string
     */
    protected function getViewPath()
    {
        $viewPath          = [];
        $app               = $this->getApp();
        $currentModule     = $app->getCurrentModuleName();
        $currentController = $app->getCurrentControllerName();
        $currentActionName = $app->getCurrentActionName();
        if (!empty($currentModule)) {
            $viewPath = array_merge($viewPath, [MODULES_PATH, $currentModule]);
        } else {
            $viewPath = array_merge($viewPath, [APPLICATION_PATH]);
        }
        $viewPath = array_merge($viewPath, [self::VIEW_DIR_NAME, $currentController, $currentActionName]);
        return implode('/', $viewPath) . '.tpl';
    }

    /**
     * Возвращает параметры запроса
     * @return array
     */
    protected function getParams()
    {
        return $_REQUEST;
    }

    /**
     * Возвращает значение запрошенного параметра. Если параметр не существует - возвращается дефолтное значение
     * @param string $name Имя параметра
     * @param null $default Значение по-умолчанию
     * @return mixed
     */
    protected function getParam($name, $default = null)
    {
        $params = $this->getParams();
        return isset($params[$name]) ? $params[$name] : $default;
    }
}