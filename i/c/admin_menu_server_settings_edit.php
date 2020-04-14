<?php 
// Сохраняем данные из запроса
$db_url=$_POST['db_url'];
$db_login=$_POST['db_login'];
$db_pass=$_POST['db_pass'];
$refresh_quantity=$_POST['refresh_quantity'];
$refresh_rate=$_POST['refresh_rate'];
$refresh_period=$_POST['refresh_period'];
// Рассчитываем период в секундах

include_once(dirname(__FILE__)."/../m/config.php");
$config = new Config;
$config->set($db_url, $db_login, $db_pass, $refresh_quantity, $refresh_rate, $refresh_period);


