<?php
class MG_Geo{


// Возвращает список полей вида links_N
public function get_links(){
	include_once(dirname(__FILE__)."/mg_import.php");
	$import=new MG_Import;
	$list = $import->get_local_db('mg_geo');
	$links=array();
	foreach ($list as $key => $value) {
		if (strstr($value, 'link_')){
			$links[]=$value;
		}
	}
	return $links;
}
public function update_database(){
	include_once(dirname(__FILE__)."/mg_import.php");
	$import=new MG_Import;
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_geo';
	$links_all=array();
	$links_cur=array();
	foreach ($import->get_local_db() as $key => $value) {
		if (strstr($value, 'link_')){
			$links_all[]=$value;
		}
	}
	foreach ($import->get_local_db('mg_geo') as $key => $value) {
		if (strstr($value, 'link_')){
			$links_cur[]=$value;
		}
	}
	$diff=array_diff($links_all,$links_cur);

	if ($diff){
		$sql = "ALTER TABLE $table_name ADD (";
		foreach ($diff as $key => $name) {
			$sql .= $name.' varchar(40), ';
		}
		// Отрезаем последнюю запятую, и закрывем скобку. Запрос готов, шлём!
		$sql=rtrim($sql, ', ').')';
		$wpdb->query($sql);
	}

}
public function set_multilinks($value){
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_geo';
	switch ($value) {
		case 'on':
				$wpdb->query("UPDATE $table_name SET `multi_links` = 1");
			break;
		
		case 'off':
			$wpdb->query("UPDATE $table_name SET `multi_links` = 0");
			break;
	}
}
public function get_multilinks(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_geo';
	return $wpdb->get_var("SELECT multi_links FROM $table_name");
}
}