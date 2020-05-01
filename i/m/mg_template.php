<?php
class MG_Template{
	
	public function set_thumbs($value){
	global $wpdb;
	$table_name = $wpdb->prefix . 'mg_template';
	switch ($value) {
		case 'on':
				$wpdb->query("UPDATE $table_name SET `thumbs` = 1");
			break;
		
		case 'off':
			$wpdb->query("UPDATE $table_name SET `thumbs` = 0");
			break;
		}
	}
	public function get_thumbs(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_template';
		return $wpdb->get_var("SELECT thumbs FROM $table_name");
	}

	// Преобразует шоткод вида [link id=N] в данные из ячейки таблицы mg_list -> link_N
	public function link_unshort(){
		include_once(dirname(__FILE__)."/mg_geo.php");
		$geo = new MG_Geo;
		$links=$geo->get_links();
		global $wpdb;
		$table_name=$wpdb->prefix.'mg_list';
		$post=get_the_ID();
		$db_id = get_post_meta($post, 'db_id', true);
		foreach ($links as $key => $value) {
			$link['link_'.($key+1)]=$wpdb->get_var("SELECT $value FROM $table_name WHERE id = $db_id");
		}
		$link=$geo->filter_list($link);
		$list="<a href=";
		$i=1;
		if ($link){
			foreach ($link as $key => $value) {
				$list.=$value.">Download link $i</a><br><a href=";
				$i++;
			}
		}
		return rtrim($list,"<a href=");
	}
	private function check_language($term){
		$taxonomy='post_tag';
		$is = term_exists($term, $taxonomy);
		return $is['term_id'];
	}

	private function check_genre($term){
		$taxonomy='category';
		$is = term_exists($term, $taxonomy);
		return $is['term_id'];
	}

	private function make_post($data,$fields){
		// Если поле жанра и языка разбито запятыми, формируем массив. После чего,добавляем все значения.
		$data['genre']=explode(',',$data['genre']);
		$data['language']=explode(',',$data['language']);
		var_dump($data['language']);
		echo '<br>';
		foreach ($data['genre'] as $key => $value) {
			if (!$category[]=$this->check_genre($value))$category[]=wp_insert_term($value, 'category')['term_id'];
		}

		foreach ($data['language'] as $key => $value) {
			//if ($post_tag[]=!$this->check_language($value))$post_tag[]=wp_insert_term($value, 'post_tag');

			if (!$this->check_language($value))wp_insert_term($value, 'post_tag');
			$post_tag[]=$value;
		}

		
		// Убираем всякий булевый шлак, котрый навешали сравнениями в предыдущем блоке.
		foreach ($category as $key => $value) {
			if(is_bool($value))unset($category[$key]);
		}
		foreach ($post_tag as $key => $value) {
			if(is_bool($value))unset($post_tag[$key]);
		}
		var_dump($post_tag);
		$params_array=array(
			'post_title'=>$fields['post_title'],
			'post_content'=>$fields['post_text'].'<br>'.'[mg_links]',
			'post_status'=>'publish',
			'post_parent'   => 0,
			'post_author'   => 1,
			'comment_status' => 'closed',
			'post_category'  => $category,
			'tags_input'     => $post_tag,    
			'meta_input' => ['db_id'=>$data['id'], '_aioseop_title' => $fields['meta_title'], '_aioseop_description' => $fields['meta_description']]
		);
		$post_id=wp_insert_post($params_array);
		if($this->get_thumbs()){
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
			
			$url = $fields['cover'];
			$desc = "Album cover";

			$img_tag = media_sideload_image( $url, $post_id, $desc, 'id');
			set_post_thumbnail( $post_id, $img_tag);
		}
	}
	
	// Заменяем значения в полях вида [field_name] на значение из бд
	private function prepare_post($array){
		
		$fields = $this->get_template();		
		foreach ($array as $key => $value) {
			foreach ($fields as $key2 => $value2) {
				if(strstr($value2, "[$key]")&&($key!='cover')){
				$fields[$key2]=str_ireplace("[$key]", $value, $fields[$key2]);
				}
				// отдельно под картинки
				if(strstr($value2, "[$key]")&&($key=='cover')){
					// Если стоит галочка на миниатюру, заменяем на "", иначе делаем тег img
					if($this->get_thumbs()){
						$fields[$key2]=str_ireplace("[cover]", '', $fields[$key2]);
					}else{
						$fields[$key2]=str_ireplace("[cover]", '<img src='.$value.'>', $fields[$key2]);
					}
				// Ужасный хак, но что поделать.
				$fields['cover']=$value;
				}
			}
		}

		return $fields;
	}

	public function get_delta_data(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'postmeta';
		$table_name2 = $wpdb->prefix . 'mg_list';
		
		$max_post=$wpdb->get_var("SELECT MAX(meta_value) FROM $table_name WHERE `meta_key` = 'db_id'");

		if (!$max_post)$max_post=0;

		$delta = $wpdb->get_results("SELECT * FROM $table_name2 WHERE id>$max_post", ARRAY_A);
		foreach ($delta as $num => $array) {
			$fields=$this->prepare_post($array);

			$this->make_post($array, $fields);
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