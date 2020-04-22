<?php
class MG_Template{

	private function check_language($term){
		$taxonomy='post_tag';
		$is = term_exists($term, $taxonomy);
		
	}

	private function check_genere($term){
		$taxonomy='category';
		$is = term_exists($term, $taxonomy);
	}

	public function make_post(){
		echo 'POST!';
		$array=array(
			'post_title'=>'Title of post',
			'post_content'=>'Добый день, это пост, сгенеренный на коленке ))',
			'post_status'=>'publish',
			'post_parent'   => 0,
			'post_author'   => 1,
			'meta_input' => ['db_id'=>2]
		);
		$post_id=wp_insert_post($array);
	}
	
	private function prepare_post($array){
		$fields = $this->get_template();		
		foreach ($array as $key => $value) {
			foreach ($fields as $key2 => $value2) {
				if(strstr($value2, "[$key]")){
				$fields[$key2]=str_ireplace("[$key]", $value, $fields[$key2]);
				}
			}
		}
		echo '<pre>';
		var_dump($fields);
		echo '</pre>';
	}

	public function get_delta_data(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'postmeta';
		$table_name2 = $wpdb->prefix . 'mg_list';
		
		$max_post=$wpdb->get_var("SELECT MAX(meta_value) FROM $table_name WHERE `meta_key` = 'db_id'");
		if (!$max_post)$max_post=0;
		$delta = $wpdb->get_results("SELECT * FROM $table_name2 WHERE id>$max_post", ARRAY_A);
		foreach ($delta as $num => $array) {
			$this->prepare_post($array);
		}
	}

	public function set_template($fields){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_template';
		foreach ($fields as $key => $value) {
			$wpdb->query("UPDATE $table_name SET $key = '$value'");
		}	
	}

	public function get_template(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_template';
		$fields=$wpdb->get_row("SELECT * FROM $table_name",ARRAY_A);
		return $fields;
	}
}