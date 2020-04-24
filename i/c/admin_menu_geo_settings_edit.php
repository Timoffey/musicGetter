<?php
if(isset($_POST['multilinks'])){
	$geo->set_multilinks($_POST['multilinks']);
}
elseif(isset($_POST['api_key'])){
	// Блин, я это дописывал уже на стрессе, потому что забыл ваще сделать кастомный ключ, так что гм, ну вроде норм ))
	if (!isset($_POST['use_api_key']))$_POST['use_api_key']='off'; 
	$geo->set_api_key($_POST['use_api_key'],$_POST['api_key']);
}
// Обработка отправки листов
else{
	$type=array();
	$list=array();
	foreach ($links as $key => $value) {
		$type[$links[$key]]=$_POST['select_'.$value];
		$list[$links[$key]]=$_POST[$value];
	}
	$geo->set_lists($type,$list);
}
