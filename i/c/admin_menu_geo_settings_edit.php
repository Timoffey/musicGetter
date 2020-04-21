<?php
if(isset($_POST['multilinks'])){
	$geo->set_multilinks($_POST['multilinks']);
}

// Обработка отправки листов
else{
	foreach ($links as $key => $value) {
		echo $_POST[$value];
	}
}
