<?php

namespace components;

/**
 * @class Config
 * Класс для работы с конфигом
 */
class Config implements interfaces\IConfig
{
    protected $data = [];

    /**
     * Читает конфиги, мерджа их
     * @param array $configs Массив имен файлов с конфигами
     */
    public function __construct(array $configs = [])
    {
        $result = [];
        foreach ($configs as $configFile) {
            if (is_readable($configFile)) {
                $conf   = include_once($configFile);
                $result = array_replace_recursive($result, $conf);
            }
        }
        $this->data = $result;
    }

    /**
     * Возвращает информацию конфига в виде массива
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}