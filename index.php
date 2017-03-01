<?php
require_once 'boot.php';

//boot Smarty
Smarty_Autoloader::register();
global $Smarty;
$Smarty = new Smarty();

//boot DB
global $DbContext;
$DbContext = DB::GetInstance(Config::$DbHost, Config::$DbUser, Config::$DbPass, Config::$DbBase);

//boot Request
global $Request;
$Request = Request::GetInstance();
?>