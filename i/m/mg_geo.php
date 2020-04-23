<?php
class MG_Geo{

public function filter_list($links){
	$country='IS';
	$lists=$this->get_lists();
	$n=count($links);
	// Прогоняем страну по чёрным и белым спискам
	for ($i = 1; $i <=$n; $i++){
		// Эта строка нужна для создания ссылки для выключенного мультилинка
		if ($lists['link_'.$i]->type=='w' && strstr($lists['link_'.$i]->value,$country)) $white = array($links['link_'.$i]);
		if (($lists['link_'.$i]->type=='b' && strstr($lists['link_'.$i]->value,$country))||
			($lists['link_'.$i]->type=='w' && !strstr($lists['link_'.$i]->value,$country)))unset($links['link_'.$i]);
	}
	// Если мультитлинк включен
	if($this->get_multilinks()){
		return $links;
	}else{
	// Если мультитлинк выключен, а стран много, то кидаем
			// Если не нашлось страны в вайт-листе
		if (!isset($white)){
			if (count($links)>1){
				return array(array_pop($links));
			}
			elseif (count($links)<1){
				return NULL;
			}
			elseif (count($links)==1){
				return $links;
			}
		}else return $white;
		
	}
	
}
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

public function set_lists($types,$values){
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_geo';
	foreach ($types as $key => $value) {
		$wpdb->query("UPDATE $table_name SET $key = '{\"type\":\"$value\",\"value\":\"$values[$key]\"}'");
	}
}

public function get_lists(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_geo';
	$link=$wpdb->get_row("SELECT * FROM $table_name", ARRAY_A);
	return $link=array_map('json_decode',$link);
	
}
}