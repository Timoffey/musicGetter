<?php 
// Сохраняем данные из запроса
$db_url=$_POST['db_url'];
$db_login=$_POST['db_login'];
$db_pass=$_POST['db_pass'];
$refresh_quantity=$_POST['refresh_quantity'];
$refresh_rate=$_POST['refresh_rate'];
$refresh_period=$_POST['refresh_period'];
// Рассчитываем период в секундах
switch ($refresh_period) {
	case 'm':
		$refresh_rate *= 60;
		break;
	case 'h':
		$refresh_rate *= 60*60;
		break;
	case 'd':
		$refresh_rate *= 60*60*24;
		break;
	
}
echo $refresh_rate;
global $wpdb;
$table_name = $wpdb->prefix . 'mg_config';
$query = "UPDATE $table_name SET db_url = %s, db_login = %s, db_pass = %s, refresh_rate = %d, refresh_quantity = %d";
$wpdb->query($wpdb->prepare($query, $db_url, $db_login, $db_pass, $refresh_rate, $refresh_quantity));

