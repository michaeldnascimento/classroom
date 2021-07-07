<?php
/** topo estrutura */
require_once('topo.php');

/** include projeto */
require_once('../vendor/autoload.php');
require_once('../app/config/config.php');
//require_once('../app/functions/functions.ph
(new \app\core\RouterCore());

/** footer estrutura */
(App\Core\Template::getFooter());