<?php
ini_set('include_path', ini_get('include_path') . ':../lib');

require 'webphp.php';
spl_autoload_register(array('WebPHP', 'autoload'));

View::$pathPrefix = '../view/';
View::$pathSuffix = '.tpl.php';

$app = new WebPHP();
$app->dispatch(new Root()/*, new View('index')*/);
?>