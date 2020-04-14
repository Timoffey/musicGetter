<?php
class Config
{
	public $db_url;
	public $db_login;
	public $db_pass;
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
	
	public function set($db_url, $db_login, $db_pass, $refresh_quantity, $refresh_rate, $refresh_period){
		$refresh_rate=$this->convert_to_seconds($refresh_rate, $refresh_period);
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_config';
		$query = "UPDATE $table_name SET db_url = %s, db_login = %s, db_pass = %s, refresh_rate = %d, refresh_quantity = %d";
		$wpdb->query($wpdb->prepare($query, $db_url, $db_login, $db_pass, $refresh_rate, $refresh_quantity));
	}

	function __construct(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_config';
		$config=$wpdb->get_row("SELECT * FROM $table_name");
		$this->db_url = $config->db_url;
		$this->db_login = $config->db_login;
		$this->db_pass = $config->db_pass;
		$this->refresh_quantity = $config->refresh_quantity;
		$tq = $this->convert_from_seconds($config->refresh_rate);
		$this->refresh_rate = $tq[0];
		$this->refresh_period = $tq[1];
	}
}


