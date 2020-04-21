<?php
if(isset($_POST['multilinks'])){
	$geo->set_multilinks($_POST['multilinks']);
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
