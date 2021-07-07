<?php

namespace app\classes;

class Input
{

    /**
     * Retorna um valor via parâmetro get
     *
     * @param  string $param
     * @param  int $filter
     * @return mixed
     */
    public static function get($param, $filter = FILTER_SANITIZE_STRING)
    {
        return filter_input(INPUT_GET, $param, $filter);
    }

    /**
     * Retorna um valor via post
     *
     * @param  string $param
     * @param  int $filter
     * @return mixed
     */
    public static function post($param, $filter = FILTER_SANITIZE_STRING)
    {
        return filter_input(INPUT_POST, $param, $filter);
    }
}
