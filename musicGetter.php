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

// При установке плагина создаём БД
register_activation_hook(__FILE__, 'install_musicGetter_plugin');
function intall_musicGetter_plugin(){

}

// При удалении плагина – сносим БД
register_uninstall_hook(__FILE__, 'uninstall_musicGetter_plugin');
function unintall_musicGetter_plugin(){
	
}
