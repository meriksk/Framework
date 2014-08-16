<?php
require 'autoload.php';

$app = new \Framework\Application;
$app->get('/users2/([a-z]+)', function($username) {
	echo 'username: <b>' . $username . '</b>';
});

//function routeNotMatch($route) {
//	var_dump($route);
//}

$app->run();