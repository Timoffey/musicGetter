<?php
include_once(dirname(__FILE__)."/../m/mg_import.php");
$import = new MG_Import;

if (isset($_POST['actualize'])){
	include_once(dirname(__FILE__)."/../v/showMessage.php");
	if($import->actualize()){
		showMessage('База успешно актуализирована!');
	}else{
		showMessage('База уже актуальна!');
	}			
}

if (isset($_POST['update_fields'])){
	echo 'Обновлены следующие поля:<br>';
	var_dump($_POST['update_fields']);
}

if (isset($_POST['import_filter'])){
	echo 'Фильтры на поля:<br>';
	foreach ($_POST['import_filter'] as $key) {
		echo $key.' - '.$_POST[$key].'<br>';
	}
}