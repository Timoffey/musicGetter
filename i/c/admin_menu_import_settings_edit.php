<?php
include_once(dirname(__FILE__)."/../m/mg_import.php");
$import = new MG_Import;

if ($_POST['actualize']=='on'){
	$import->actualize();			
}