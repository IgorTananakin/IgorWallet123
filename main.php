<?php
//для ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
defined('ABSPATH') || exit;


/*
Plugin Name: IgorWallet123
Plugin URI: plugin url
Description: Кошелёк
Version: 2.0
Author: Igor
Author URI: author site url
License: GPL2
*/

define('PLG_TABLE__PATH', plugin_dir_path(__FILE__));
define('PLG_TABLE__URL', plugin_dir_url(__FILE__));


include_once __DIR__ . '/includes/helpers.php';
//заметка на будующее создать класс интерфейс чтоб не прописывать одно и тоже
include_once __DIR__ . '/app/controller/data.php';//для главной странице
include_once __DIR__ . '/app/controller/data_history.php';//для истории транзакций
include_once __DIR__ . '/app/view/index.php';//Plg_Table_View_Admin_Data_Index
include_once __DIR__ . '/app/view/index1.php';//Plg_Table_View_Admin_Data_Index1
include_once __DIR__ . '/includes/validate.php';


/** Flahs init */
Plg_Table_Helpers::flashInit();


add_action('plugins_loaded', function()
{
	if(is_admin())
	{
		add_action('admin_menu', function()
		{
			$ControllerData = new Plg_Table_Controller_Data();
			//Главное меню Wallet
			$hook = add_menu_page(
				'Wallet',
				'Wallet',
				'manage_options',
				'plg-table',
				
				array($ControllerData, 'view'),
				'dashicons-images-alt2'
			) ;
			
			add_action('load-' . $hook, array($ControllerData, 'action'));
			
			$ControllerData1 = new Plg_Table_Controller_Data1();
			$hook1 = add_menu_page(
				'Транзакции',
				'Транзакции',
				'manage_options',
				'plg-table1',
				
				array($ControllerData1, 'view1')
				
			) ;
			
			add_action('load-' . $hook1, array($ControllerData1, 'action1'));
			
			//Подменю транзакции
			$hook = add_submenu_page(
					'plg-table',
					'Wallet123',
					'Транзакции',
					'manage_options',
//                array($ControllerData, 'view'),
					'plg-table1' );
			add_action('load-' . $hook, array($ControllerData, 'action1'));
			
			//На страницу списание матча 
			$hook = add_submenu_page(
					'plg-table',
					'Submenu Page Title',
					'Заказать матч',
					'manage_options',
					'матч' );
			add_action('load-' . $hook, array($ControllerData, 'action'));
		});
		
		if(class_exists('WP_List_Table') == false)
		{
			require_once (ABSPATH.'wp-admin/includes/class-wp-list-table.php');
		}
	}
});


register_activation_hook(__FILE__, 'plg_table_activation');
function plg_table_activation()
{
	global $wpdb;
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	dbDelta("CREATE TABLE IF NOT EXISTS `" . $wpdb -> prefix . "wallet` (
		`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`id_user` int(11) unsigned NOT NULL default '0',
    	`balance` int(11) unsigned NOT NULL default '0',
		`date_create` INT(10) UNSIGNED NOT NULL
	) {$wpdb -> get_charset_collate()};");
	
	dbDelta("CREATE TABLE IF NOT EXISTS `" . $wpdb -> prefix . "wallet_transaction` (
		`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`wallet_id` int(11) unsigned NOT NULL default '0',
		`total_spent` int(11) unsigned NOT NULL default '0',
		`date_transaction` INT(10) UNSIGNED NOT NULL
	) {$wpdb -> get_charset_collate()};");
	
	
	return true;
};

register_uninstall_hook(__FILE__, 'plg_table_uninstall');
function plg_table_uninstall()
{
	global $wpdb;
	
	$wpdb -> query("DROP TABLE IF EXISTS `" . $wpdb -> prefix . "wallet`");

	return true;
}


//для вставки шорткода списать матч [baztag]здесь текст[/baztag]
add_shortcode( 'baztag', 'baztag_func' );
function baztag_func( $atts, $content ) 
{
	 return include_once(__DIR__ . '/debit.php');
}

//для вставки в правомм углу баланса
function replace_admintext( $wp_admin_bar ) {
	global $wpdb;
	$my_account=$wp_admin_bar->get_node('my-account');
	$user = get_current_user_id();
	$balance = $wpdb->get_results( "SELECT balance FROM wp_wallet WHERE id_user =  $user" , ARRAY_A);
	if ($balance != NULL) {
		$balance = 'Текущий баланс ' . implode('',end($balance)) . ' руб';
		$newtitle = str_replace( 'Привет', $balance  , $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
	) );
	} else {
		$newtitle = str_replace( 'Привет', 'Баланс не задан' , $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
	) );
		
	}
	
}
add_filter( 'admin_bar_menu', 'replace_admintext',25 );

