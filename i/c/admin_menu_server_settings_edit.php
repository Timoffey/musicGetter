<?php 
// Обработка нажатия "Очистить базы"
if (isset($_POST['reset'])){
?>
	<script>
		alert('Базы пересозданы');
	</script>
<?php
	uninstall_musicGetter_plugin();
	install_musicGetter_plugin();
}else{
	// Обработка нажатия "Сохранить"
	$is_on=$_POST['is_on'];
	$db_url=$_POST['db_url'];
	$db_login=$_POST['db_login'];
	$db_pass=$_POST['db_pass'];
	$db_name=$_POST['db_name'];
	$db_table_name=$_POST['db_table_name'];
	$refresh_quantity=$_POST['refresh_quantity'];
	$refresh_rate=$_POST['refresh_rate'];
	$refresh_period=$_POST['refresh_period'];
	// Рассчитываем период в секундах

	include_once(dirname(__FILE__)."/../m/mg_config.php");
	$config = new MG_Config;
	$config->set($is_on, $db_url, $db_login, $db_pass, $db_name, $db_table_name, $refresh_quantity, $refresh_rate, $refresh_period);
}



