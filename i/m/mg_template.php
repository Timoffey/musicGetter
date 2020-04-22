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
			'post_author'   => 1
		);
		$post_id=wp_insert_post($array);
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