<?php
class Config
{
	public $db_login;
	public $db_pass;
	public $db_url;
	public $refresh_rate;
	public $refresh_quantity;
	public $refresh_period;

	function __construct(){

	}
}

function get(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_config';
	return $wpdb->get_row("SELECT * FROM $table_name");
}

function convert($seconds){
	if ($seconds/60 < 60) {
		$time=$seconds/60;
		$quant='m';
	}elseif ($seconds/60/60 < 24){
		$time=$seconds/60/60;
		$quant='h';
	}
	else{
		$time=$seconds/60/60/24;
		$quant='d';
	}
	return;
}