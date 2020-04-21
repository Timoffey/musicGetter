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
			case 10:
				return 'date';
				break;
			case 253:
				return 'varchar';
				break;		
		}
	}

	// Получаем поля локальной БД
	public function get_local_db($table = 'mg_list'){
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
		// Смотрим заголовки и отправляем их, так мы на зависим от наличия записей
		$row = $wpdb->get_row("SELECT * FROM $table_name");
			$row = $wpdb->get_col_info('name');
		//Убираем поле id из массива локальной БД
		foreach ($row as $key => $value) {
			if($value=='id'){
				unset($row[$key]);
			}
		}
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
		$res = $mysqli->query("SELECT * FROM $config->db_table_name LIMIT 1");
		$row = $res->fetch_fields();
		
		$table = array();
		foreach ($row as $value) {
			// Дурацкая  DATE требует отсутствия указания размера, пришлось городить эту строку...
			if($value->type==10){
				$table[$value->name] = $this->convert_mysqli_type_to_str($value->type);
			}else{
				// Эта строчка фиксит странный баг, с типом VARCHAR. Почему-то его размер умножается на 4.
				if($value->type==253)$value->length*=0.25;
				$table[$value->name] = $this->convert_mysqli_type_to_str($value->type)."(".$value->length.")";
			}
		}
		
		//Убираем поле id из массива удалённой БД
		foreach ($table as $key => $value) {
			if($key=='id'){
				unset($table[$key]);
			}
		}
		return $table;
	}
	
	// Нахождение разницы между удалённой базой и локальной
	// От remote берём $key, а от local берём $value
	private function compare($local_db, $remote_db){

		// Находим новые поля и возвращаем. Смотреть будем по ключам, потому что в значениях у удалённой типы.
		// При попытке перевернуть удалённую и смотреть по ней, выходила чушь.
		$diff=array_diff_key($remote_db, array_flip($local_db));
		return $diff;
	}

	// Добавляем недостающие поля в локальную базу
	// Принимает массив формата $name => $type. Например Name => varchar(20).
	private function update_db($diff){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_list';
		$sql = "ALTER TABLE $table_name ADD (";
		foreach ($diff as $name => $type) {
			$sql .= $name.' '.$type.', ';
		}
		// Отрезаем последнюю запятую, и закрывем скобку. Запрос готов, шлём!
		$sql=rtrim($sql, ', ').')';
		$wpdb->query($sql);
		$this->update_filter($diff);
	}

	// Добавляем недостающие поля в базу с полями фильтров для импорта
	private function update_filter($diff){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_import_filter';
		// В случае, если такой базы вообще не создано, создаём её.
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			$this->create_filter($table_name,$diff);
			return;
		}
		// Иначе, просто добавляем недостающие поля
		$sql = "ALTER TABLE $table_name ADD (";
		foreach ($diff as $name => $type) {
			$sql .= $name.' varchar(40), ';
		}
		// Отрезаем последнюю запятую, и закрывем скобку. Запрос готов, шлём!
		$sql=rtrim($sql, ', ').')';
		$wpdb->query($sql);

	}

	// При первом запуске база будет отсутствовать, поэтому её надо создать и заполнить
	private function create_filter($table_name, $diff){
		global $wpdb;
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (";
    	$sql2_names = "";
    	$sql2_values = "";
	    foreach ($diff as $name => $type) {
	    	$sql .= $name.' varchar(40) NOT NULL, ';
	    	$sql2_names .= $name.', ';
	    	$sql2_values .= '" ", ';
	    }
	    // Отрезаем последнюю запятую, и закрывем скобку. 
	    $sql=rtrim($sql, ', ').')';
	    // И добавляем сторчку с движком нашей БД и кодировкой. Запрос готов, шлём!
	    $sql .="ENGINE = MyISAM DEFAULT CHARSET=utf8;";
	    $wpdb->query($sql);
	    // Также необходимо заполнить её пустыми значениями, чтобы, в последствии, можно было апдейтить.
	    $sql2_names=rtrim($sql2_names, ', ');
	    $sql2_values=rtrim($sql2_values, ', ');
	    $sql = "INSERT INTO $table_name ($sql2_names) VALUES ($sql2_values)";
	    $wpdb->query($sql);
	}
	private function get_sql_filter(){
		// Собираем строчку фильтров для запросов
		$sql_filter = 'WHERE 1 = 1 AND ';
		foreach ($this->get_filter() as $key => $value) {
			if($value AND $value !=" "){
				$sql_filter .= '`'.$key.'` = "'.trim($value).'" AND '; 	
			}
		}
		$sql_filter = rtrim($sql_filter, ' AND ');
		return $sql_filter;
	}
	// Актуализация локальной БД относительно удалённой. Если разница есть – добавляем поля.
	public function actualize(){
		$remote_db=$this->get_remote_db();
		$local_db=$this->get_local_db();
		$diff = $this->compare($local_db,$remote_db);
		if ($diff){
			$this->update_db($diff);
			// Обновляем также поля для гео
			include_once(dirname(__FILE__)."/../m/mg_geo.php");
			$geo = new MG_Geo;
			$geo->update_database();
			return 1;
		}else{
			return 0;
		}
	}

	public function set_filter($filter){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_import_filter';
		$query = "UPDATE $table_name SET";
		foreach ($filter as $key => $value) {
			$query .=' '.$key.' = "'.$value.'", ';
		}
		$query=rtrim($query, ', ');
		$wpdb->query($query);
	}
	public function get_filter(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_import_filter';
		// Эта конструкция необходима, чтобы не было ошибки, в первый раз, когда таблица mg_import_filter ещё не создана
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			$row = $wpdb->get_row("SELECT * FROM $table_name");
		}else $row = NULL;
		return $row;
	}
	public function import(){
		// Собираем строчку фильтров для запросов
		$sql_filter = $this->get_sql_filter();

		// Подготавливаем подключение к удалённой и локальной базам
		include_once(dirname(__FILE__)."/../m/mg_config.php");
		$config = new MG_Config;
		// Подключаемся к удалённой БД. Данные берём из конфига.
		$mysqli = new mysqli($config->db_url, $config->db_login, $config->db_pass, $config->db_name);
		if ($mysqli->connect_errno) {
		    echo "<br>Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		// Подготовка получения данных из локальной базы
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_list';

		


		//Блок получения количества записей в удалённой базе, с учётом фильтров и в локальной.
		$result=$mysqli->query("SELECT COUNT(`id`) FROM `$config->db_table_name` $sql_filter");
		$size=$result->fetch_array()[0];
		$current = $wpdb->get_var("SELECT COUNT(`id`) FROM $table_name");
		

		while (($size-$current)>0){
			// Блок составления первой части инсерта с полями для инсерта
			// Делаем запрос в удалённую базу на одну строку, вытаскиваем в цикле поля, собираем в запрос.
			$final_query="INSERT INTO $table_name (";
			$result2=$mysqli->query("SELECT * FROM `$config->db_table_name` $sql_filter LIMIT 1");
			while($res=$result2->fetch_object()){
				$keys='';
				foreach ($res as $key => $value) {
					$keys.="`".$key."`, ";
				}
			$final_query .= rtrim($keys,", ").") VALUES ";
			}

			// Блок составления второй части инсерта со значениями
			// Делаем запрос в удалённую базу на количество строк, указанное в конфиге
			// Также в запросу присутствует смещение, равное количеству записей в локальной базе
			// Таким образом мы будем строить запросы блоками по стольку, сколько указано в настройках
			$result2=$mysqli->query("SELECT * FROM `$config->db_table_name` $sql_filter LIMIT $current,$config->refresh_quantity");
			while($res=$result2->fetch_object()){
				$values='(';
				foreach ($res as $key => $value) {
					$values.="'".$value."', ";
				}
				$final_query .= rtrim($values,", ")."),";
			}

			// Выполняем заброс блока БД, равного количесту строк, указанных в настройках
			$wpdb->query(rtrim($final_query,","));
			// После этого, обновляем параметры счётчиков и, в случае надобности, формируем слудующий блок данных.

			// Блок получения количества записей в удалённой базе, с учётом фильтров и в локальной.
			// Нужен для контроля общего цикла while(205)
			$result=$mysqli->query("SELECT COUNT(`id`) FROM `$config->db_table_name` $sql_filter");
			$size=$result->fetch_array()[0];
			$current = $wpdb->get_var("SELECT COUNT(`id`) FROM $table_name");
		}

	}

	public function update_fields($fields){
		// Собираем строчку полей для запроса
		$sql_fields='`id`, ';
		foreach ($fields as $key => $value) {
			$sql_fields .= "`$value`, ";
		}
		$sql_fields = rtrim($sql_fields,", ");
		// Собираем строчку фильтров для запросов
		$sql_filter = $this->get_sql_filter();

		// Подготавливаем подключение к удалённой и локальной базам
		include_once(dirname(__FILE__)."/../m/mg_config.php");
		$config = new MG_Config;
		// Подключаемся к удалённой БД. Данные берём из конфига.
		$mysqli = new mysqli($config->db_url, $config->db_login, $config->db_pass, $config->db_name);
		if ($mysqli->connect_errno) {
		    echo "<br>Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		// Подготовка получения данных из локальной базы
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_list';


		$result = $mysqli->query("SELECT $sql_fields FROM $config->db_table_name $sql_filter");
		$res=$result->fetch_all(MYSQLI_ASSOC);
		$res2 = $wpdb->get_results("SELECT $sql_fields FROM $table_name $sql_filter",ARRAY_A);
		
		// Гениальное решение, которое я нашёл на стаковерфлоу!
		$diff=array_map('unserialize',array_diff(array_map('serialize',$res), array_map('serialize', $res2)));
		if ($diff){
			foreach ($diff as $num => $array) {
				$q="UPDATE `$table_name` SET ";
				$id="";	
				foreach($array as $key => $value){
					if($key!='id'){
						$q .= '`'.$key.'` = "'.$value.'", ';
					}else $id=$value;
				}
				$q = rtrim($q,", ");
				$q .= " WHERE id=$id";
				// Да, надо было сделать через $wpdb->update(). Но я уже подсобрал SQL строку. Может быть потом обновлю )
				$wpdb->query($q);
				return 1;
			}
		}else return 0;

	}
}