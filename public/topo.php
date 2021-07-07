<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cs-estrutura' . DIRECTORY_SEPARATOR . 'estrutura.php';

define("PROJETO", str_replace(ROOT_PATH, "", __DIR__));

use App\Core\Template;
use App\Core\Tools;

Template::$assets = [
    'jquery' => TRUE,
    'bootstrap' => TRUE,
    'font-awesome' => TRUE,
    'jquery-ui' => TRUE,
    'jquery-maskedinput' => TRUE,
    'jquery-maskmoney' => TRUE,
    'bootstrap-dialog' => TRUE,
    'toastr' => FALSE,
    'owl-carousel' => FALSE,
];

Template::loadAssets();

Template::setSession(true);
Template::$idturma = [1,12448];
//Template::$nivel = 1;
//Template::$id_sistema = 10;
Template::$sistema = "INTRANET FMUSP";
//Template::$titulo = "INTRANET FMUSP";
Template::getHeader();
Template::getMenu();

//var_dump(phpversion());
?>
