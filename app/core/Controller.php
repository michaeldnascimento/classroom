<?php

namespace app\core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function load($view, $params = [])
    {

        $twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader('../app/view/')
        );

        $twig->addGlobal('BASE', BASE);
        echo $twig->render($view . '.twig.php', $params);
    }

    public function showMessage($titulo, $descricao,  $link = null, $httpCode = '200')
    {
        http_response_code($httpCode);

        $this->load('partials/message', [
            'titulo'    => $titulo,
            'descricao' => $descricao,
            'link'      => $link
        ]);
    }
}
