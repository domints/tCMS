<?php
require_once 'boot.php';

//boot Twig
$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
    'cache' => './templates_c',
));
echo $twig->render('index.html', array('name' => 'Fabien'));

//boot DB
global $DbContext;
$DbContext = DB::GetInstance(Config::$DbHost, Config::$DbUser, Config::$DbPass, Config::$DbBase);

//boot Request
global $Request;
$Request = Request::GetInstance();
?>
