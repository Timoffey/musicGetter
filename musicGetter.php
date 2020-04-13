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

function intall_musicGetter_plugin(){
	global $wpdb;
	// Создаём таблицу под конфиг плагина. Настройки донорской БД и параметры обновления локальной БД.
	$table_name = $wpdb->prefix . 'mg_config';
	if ($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
	    `db_login` varchar(20) NOT NULL,
	    `db_pass` varchar(20) NOT NULL,
	    `db_url` varchar(40) NOT NULL,
	    `refresh_rate` int(10) NOT NULL,
	    `refresh_quantity` int(10) NOT NULL,
	    PRIMARY KEY (`id`)
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
    $wpdb->query($sql);
	}

	// Создаём таблицу под локальную версию БД. Она пустая, чтобы заполнить её колонками
	// необходимо нажать кнопку "Получение полей из БД" в разделе Import 
	$table_name = $wpdb->prefix . 'mg_list';
	if ($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY (`id`)
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
    $wpdb->query($sql);
	}

	// Создаём таблицу под фильтр, для заполнения локальной версии БД. Она пустая, чтобы заполнить её колонками
	// необходимо нажать кнопку "Получение полей из БД" в разделе Import 
	$table_name = $wpdb->prefix . 'mg_import_filter';
	if ($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY (`id`)
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
    $wpdb->query($sql);
	}

	// Создаём таблицу под настройки Гео. Тут будет переключатель мультилинка
	// и настройка блэк/вайт листов на поля со ссылками. 
	// Как-то оно будет обновляться, мне пока не понятно как оно будет определять тип.
	$table_name = $wpdb->prefix . 'mg_geo';
	if ($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
	    `multi_links` tinyint(1) NOT NULL,
	    `geo_field_1` varchar(10) NOT NULL,
	    `geo_field_1` varchar(10) NOT NULL,
	    PRIMARY KEY (`id`)
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
    $wpdb->query($sql);
	}

	// Создаём таблицу под список стран в листах.
	// Как-то оно будет обновляться, мне пока не понятно как оно будет определять тип.
	$table_name = $wpdb->prefix . 'mg_geo_lists';
	if ($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name ){
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
	    `geo_field_1` varchar(255) NOT NULL,
	    `geo_field_1` varchar(255) NOT NULL,
	    PRIMARY KEY (`id`)
	    ) ENGINE = MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
    $wpdb->query($sql);
	}
}

// При удалении плагина – сносим БД
register_uninstall_hook(__FILE__, 'uninstall_musicGetter_plugin');

function unintall_musicGetter_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'mg_config';
    $table_name2 = $wpdb->prefix . 'mg_list';
    $table_name3 = $wpdb->prefix . 'mg_import_filter';
    $table_name4 = $wpdb->prefix . 'mg_geo';
    $table_name5 = $wpdb->prefix . 'mg_geo_lists';
    $sql = "DROP TABLE IF EXISTS $table_name,$table_name2,$table_name3,$table_name4,$table_name5";
    $wpdb->query($sql);
}
