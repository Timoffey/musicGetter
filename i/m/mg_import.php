<?php
class MG_Import{
	// Никто не знает, почему $mysqli возвращает тип поля MYSQL в виде числа
	// но эта функция возвращает всё на свои места
	private function convert_mysqli_type_to_str($type){
		switch ($type) {
			case 1:
				return 'tinyint';
				break;
			case 3:
				return 'int';
				break;
			case 253:
				return 'varchar';
				break;		
		}
	}

	// Получаем поля локальной БД
	private function get_local_db(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_list';
		// Смотрим заголовки и отправляем их, так мы на зависим от наличия записей
		$row = $wpdb->get_row("SELECT * FROM $table_name");
			$row = $wpdb->get_col_info('name');
			return $row;
	}
	
	// Получаем поля удалённой БД
	private function get_remote_db(){
		include_once(dirname(__FILE__)."/../m/mg_config.php");
		$config = new MG_Config;
		// Подключаемся к удалённой БД. Данные берём из конфига.
		$mysqli = new mysqli($config->db_url, $config->db_login, $config->db_pass, $config->db_name);
		if ($mysqli->connect_errno) {
		    echo "<br>Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		// Получаем структуру рабочей таблицы, для сверки
		$res = $mysqli->query("SELECT * FROM $config->db_name LIMIT 1");
		$row = $res->fetch_fields();
		
		$table = array();
		foreach ($row as $value) {
			// Эта строчка фиксит странный баг, с типом VARCHAR. Почему-то его размер умножается на 4.
			if($value->type==253)$value->length*=0.25;
			// А это мы заменяем индекс типа на название типа, для последующего составления SQL запроса.
			$table[$value->name] = $this->convert_mysqli_type_to_str($value->type)."(".$value->length.")";
		}
		return $table;
	}
	
	// Нахождение разницы между удалённой базой и локальной
	// От remote берём $key, а от local берём $value
	private function compare($local_db, $remote_db){
		//Убираем поле id из массива удалённой БД
		foreach ($remote_db as $key => $value) {
			if($key=='id'){
				unset($remote_db[$key]);
			}
		}
		//Убираем поле id из массива локальной БД
		foreach ($local_db as $key => $value) {
			if($value=='id'){
				unset($local_db[$key]);
			}
		}
		// Находим новые поля и возвращаем. Смотреть будем по ключам, потому что в значениях у удалённой типы.
		// При попытке перевернуть удалённую и смотреть по ней, выходила чушь.
		$diff=array_diff_key($remote_db, array_flip($local_db));
		return $diff;
	}

	// Добавляем недостающие поля в локальную базу
	// Принимает массив формата $name => $type. Например Name => varchar(20).
	private function update($diff){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_list';
		$sql = "ALTER TABLE $table_name ADD (";
		foreach ($diff as $name => $type) {
			$sql .= $name.' '.$type.', ';
		}
		// Отрезаем последнюю запятую, и закрывем скобку. Запрос готов, шлём!
		$sql=rtrim($sql, ', ').')';
		$wpdb->query($sql);
	}
	
	// Актуализация локальной БД относительно удалённой. Если разница есть – добавляем поля.
	public function actualize(){
		$remote_db=$this->get_remote_db();
		$local_db=$this->get_local_db();
		$diff = $this->compare($local_db,$remote_db);
		if ($diff) $this->update($diff);
	}
}