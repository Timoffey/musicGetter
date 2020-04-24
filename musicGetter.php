<?php
/*
 * Plugin Name: musicGetter
 * Plugin URI: https://github.com/Timoffey/musicGetter
 * Description: Плагин забирает данные музыкальных треков с центральной БД и выкладывает их в виде записей WordPress
 * Version: 0.1
 * Author: Timoffey Cosman
 * Author URI: https://timoffey.com
 * License: GPLv2 or later
 */


//==========УСТАНОВКА И УДАЛЕНИЕ ПЛАГИНА=======

// При установке плагина создаём БД
register_activation_hook(__FILE__, 'install_musicGetter_plugin');


function install_musicGetter_plugin(){
	global $wpdb;
	// Создаём таблицу под конфиг плагина. Настройки донорской БД и параметры обновления локальной БД.
	$table_name = $wpdb->prefix . 'mg_config';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `is_on` tinyint(1) NOT NULL,
	    `db_url` varchar(40) NOT NULL,
	    `db_login` varchar(20) NOT NULL,
	    `db_pass` varchar(20) NOT NULL,
	    `db_name` varchar(20) NOT NULL,
	    `db_table_name` varchar(20) NOT NULL,
	    `refresh_quantity` int(10) NOT NULL,
	    `refresh_rate` int(10) NOT NULL
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8;";
    $wpdb->query($sql);
    $sql2 = "INSERT INTO $table_name (is_on ,db_url, db_login, db_pass, db_name, db_table_name, refresh_quantity, refresh_rate) VALUES (0, 'localhost', 'wp', 'wp', 'test', 'db', 1000 , 60)";
    $wpdb->query($sql2);
	}

	// Создаём таблицу под локальную версию БД. Она пустая, чтобы заполнить её колонками
	// необходимо нажать кнопку "Получение полей из БД" в разделе Import 
	$table_name = $wpdb->prefix . 'mg_list';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `id` int(11) NOT NULL,
	    PRIMARY KEY (`id`)
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8;";
    $wpdb->query($sql);
	}

	// Не создаём таблицу под фильтр, для заполнения локальной версии БД. Она пустая, чтобы заполнить её колонками
	// необходимо нажать кнопку "Получение полей из БД" в разделе Import, там и создадим. 


	// Создаём таблицу под настройки Гео. Тут будет переключатель мультилинка
	// и настройка блэк/вайт листов на поля со ссылками. Будем хранить JSON с типом поля и значением
	$table_name = $wpdb->prefix . 'mg_geo';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `multi_links` tinyint(1) NOT NULL
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8;";
    $wpdb->query($sql);
    $wpdb->insert($table_name,array('multi_links' => 'on'));
	}

	// Создаём таблицу под конфиг плагина. Настройки донорской БД и параметры обновления локальной БД.
	$table_name = $wpdb->prefix . 'mg_template';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `meta_title` varchar(100) NOT NULL,
	    `meta_description` varchar(100) NOT NULL,
	    `post_title` varchar(100) NOT NULL,
	    `post_text` varchar(1000) NOT NULL
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8;";
    $wpdb->query($sql);
    $wpdb->insert($table_name,array('meta_title'=>'Download [name] - [release_date] on our website', 'meta_description'=>'Are you searchig for [name]? We have everything about it on our site', 'post_title' => '[name] in high-qiality [file_type]', 'post_text'=>'Hey! You can see this song and its description: <p>[description]'));
    }
}

// При удалении плагина – сносим БД
register_uninstall_hook(__FILE__, 'uninstall_musicGetter_plugin');

function uninstall_musicGetter_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'mg_config';
    $table_name2 = $wpdb->prefix . 'mg_list';
    $table_name3 = $wpdb->prefix . 'mg_import_filter';
    $table_name4 = $wpdb->prefix . 'mg_geo';
    $table_name5 = $wpdb->prefix . 'mg_template';
    $sql = "DROP TABLE IF EXISTS $table_name,$table_name2,$table_name3,$table_name4,$table_name5";
    $wpdb->query($sql);
}

//========СОЗДАНИЕ МЕНЮ В АДМИНКЕ=====
add_action('admin_menu','mg_admin_menu');

function mg_admin_menu(){
    add_menu_page('Settings','Настройки сервера','administrator','admin_menu_server_settings','admin_menu_server_settings');
    add_submenu_page('admin_menu_server_settings','Import','Настройки импорта','administrator','admin_menu_import_settings','admin_menu_import_settings');
    add_submenu_page('admin_menu_server_settings','GEO','Настройки ГЕО','administrator','admin_menu_geo_settings','admin_menu_geo_settings');
    add_submenu_page('admin_menu_server_settings','Template','Настройки шаблона','administrator','admin_menu_template_settings','admin_menu_template_settings');
}


// Подключаем страницу с настройками БД
function admin_menu_server_settings(){
    include_once('i/v/admin_menu_server_settings.php');
}
// Подключаем страницу с настройками импорта
function admin_menu_import_settings(){
    include_once('i/v/admin_menu_import_settings.php');
}
// Подключаем страницу с настройками ГЕО
function admin_menu_geo_settings(){
    include_once('i/v/admin_menu_geo_settings.php');
}
// Подключаем страницу с настройками шаблона
function admin_menu_template_settings(){
    include_once('i/v/admin_menu_template_settings.php');
}

// Вот честно – я так и не смог запустить функцию из класса напрямую =( что-то набедокурили разработчики WP, не могу понять что. Раньше нормально работало.
add_action( 'init', 'add_links_shortcode' );	
 
function add_links_shortcode() {
    add_shortcode( 'mg_links', 'link_unshort');
}
function link_unshort(){
	include_once('i/m/mg_template.php');
	$template = new MG_Template;
	return $template->link_unshort();
}