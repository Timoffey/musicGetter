<?php
class MG_Config
{
	public $is_on;
	public $db_url;
	public $db_login;
	public $db_pass;
	public $db_name;
	public $refresh_quantity;
	public $refresh_rate;
	public $refresh_period;

	private function convert_from_seconds($seconds){
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
		return array($time,$quant);
	}

	private function convert_to_seconds($refresh_rate, $refresh_period){
		switch ($refresh_period) {
			case 'm':
				return $refresh_rate *= 60;
				break;
			case 'h':
				return $refresh_rate *= 60*60;
				break;
			case 'd':
				return $refresh_rate *= 60*60*24;
				break;
		}
	}
	
	public function set($is_on, $db_url, $db_login, $db_pass, $db_name, $refresh_quantity, $refresh_rate, $refresh_period){
		$refresh_rate=$this->convert_to_seconds($refresh_rate, $refresh_period);
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_config';
		$query = "UPDATE $table_name SET is_on = %d, db_url = %s, db_login = %s, db_pass = %s, db_name=%s,refresh_rate = %d, refresh_quantity = %d";
		$wpdb->query($wpdb->prepare($query, $is_on, $db_url, $db_login, $db_pass, $db_name, $refresh_rate, $refresh_quantity));
	}

	function __construct(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_config';
		$config=$wpdb->get_row("SELECT * FROM $table_name");
		$this->is_on = $config->is_on;
		$this->db_url = $config->db_url;
		$this->db_login = $config->db_login;
		$this->db_pass = $config->db_pass;
		$this->db_name = $config->db_name;
		$this->refresh_quantity = $config->refresh_quantity;
		$tq = $this->convert_from_seconds($config->refresh_rate);
		$this->refresh_rate = $tq[0];
		$this->refresh_period = $tq[1];
	}
}


