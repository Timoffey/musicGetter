<?php
include_once(dirname(__FILE__)."/../v/showMessage.php");
include_once(dirname(__FILE__)."/../m/mg_import.php");
$import = new MG_Import;

if (isset($_POST['actualize'])){
	
	if($import->actualize()){
		showMessage('База успешно актуализирована!');
	}else{
		showMessage('База уже актуальна!');
	}			
}

if (isset($_POST['update_fields'])){
	if($import->update_fields($_POST['update_fields'])){
	showMessage('Записи успешно обновлены');
	}else{
		showMessage('Записи уже актуальны');
	}
}

if (isset($_POST['import_filter'])){
	foreach ($_POST['import_filter'] as $key) {
		$array[$key]=$_POST[$key];
	}
	$import->set_filter($array);
}
if (isset($_POST['test'])){
	$import->import();
}