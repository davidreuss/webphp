<?php
ini_set('include_path', ini_get('include_path') . ':../lib');
require 'webphp.php';
spl_autoload_register(array('WebPHP', 'autoload'));

$app = new WebPHP();
print $app->dispatch();
?>