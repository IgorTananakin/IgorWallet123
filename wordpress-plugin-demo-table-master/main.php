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

////определяет видимость только своих записей
//function posts_for_current_author($query) {
//    global $pagenow;
//
//    if( 'edit.php' != $pagenow || !$query->is_admin )
//        return $query;
//
//    if( !current_user_can( 'edit_others_posts' ) ) {
//        global $user_ID;
//        $query->set('author', $user_ID );
//
//    }else{
//             echo '<style>.ac-search{
//    display: block!important;
//}</style>';   
//    }
//
//    return $query;
//}
//add_filter('pre_get_posts', 'posts_for_current_author');


//function create_avtor() {
//    if (get_role('author') == null) {
//        $user = new WP_User(1); // получим экземпляр класса пользователя с правами админа (как правило это пользователь с id=1)
//		var_dump($user );
//        $adm_caps = $user->get_role_caps(); // получим массив возможностей админа
//
//        $caps = array(
//            'edit_others_posts', // нельзя редактировать чужие записи
//            'delete_others_posts', // нельзя удалять чужие записи
//            'publish_posts', // как уже говорилось выше, право edit_posts отключает всё меню с записями, поэтому можно запретить пользователю публиковать созданные записи (они будут отправляться на утверждение)
//            'upload_files',
//            'install_plugins',
//            'delete_plugins',
//            'update_plugins',
//            'edit_plugins'
//        );
//        foreach ($caps as $cap) {
//            unset($adm_caps[$cap]); // удалим некоторые возможности
//        }
//        add_role('author', 'Младший Администратор', $adm_caps); // создадим новую роль на основе роли админа, но с урезанными правами
//
//        $user = new WP_User(3);
//        $user->set_role('author'); // установим новую роль для пользователя с id = 3 (то же самое можно и правильнее будет делать из админки)
//    }
//}
//add_action('init', 'create_author');


//$role = get_role( 'author' );
//print_r($role);

//добавление новой записи в вручную
//добавление таксономии

add_action( 'init', 'true_register_taxonomy' );
 
function true_register_taxonomy() {
	$args = array(
		'labels' => array(
			'name'          => 'WalletforUser',
			'singular_name' => 'WalletforUser1',
			'menu_name'     => 'WalletforUser1',
			'all_items'     => 'Все операции',
			// 'add_new'       => 'Добавить сотрудника',
			// 'add_new_item'  => 'Добавить новый сотрудника',
			// 'edit'          => 'Редактировать',
			// 'edit_item'     => 'Редактировать сотрудника',
			// 'new_item'      => 'Новый сотрудник',
			
		),
		
		
		'public' => true,
		
	);
	
	register_post_type('igor_post',$args);
	
	
	
	
	
}


// Регистрируем свои колонки (столбцы)
//igor_post имя поста записи
add_filter ( 'manage_igor_post_posts_columns', function ( $columns ) {
    $my_columns = [//массив колонок
		'ID' => 'ID',
		'User' => 'Пользователь',
		'Balance' => 'Текущий баланс',
		'Date_transaction' => 'Последняя операция',
    ];
	//array_slice для порядка колонок
    return array_slice( $columns, 0, 2 ) + $my_columns + $columns;
} );


add_theme_support('post-thumbnails', array('igor_post','post', 'page', 'slider', 'portfolio', 'book') ); //вставка изображения для типов


 //Выводим контент для каждого из своих столбцов. Обязательно.
add_action ( 'manage_igor_post_posts_custom_column', function ( $column_name) {
	
	global $wpdb;
	
	$sql = "SELECT wp_wallet.id, wp_wallet.id_user, wp_wallet.balance, wp_wallet.date_create,  wp_users.user_email
			FROM wp_wallet
			INNER JOIN wp_users 
			ON wp_wallet.id_user = wp_users.id
            WHERE wp_wallet.id_user = " . get_current_user_id() . "
		";
	
	$result = $wpdb -> get_results($sql, ARRAY_A);
	
	foreach ($result as $data) 
	{
			$id_user = $data['id_user'];
			$balance = $data['balance'];
			$user_email = $data['user_email'];
			//var_dump($data);
			//$date_transaction = $data['balance'];
	}
	
	if ( $column_name === 'ID' ) // ID
 	{
 		echo $id_user;
 	}
	if ( $column_name === 'User' ) // колонка User
	{
		echo $user_email;
	}
	if ( $column_name === 'Balance' ) // колонка BAlance
	{
		echo $balance . ' руб';
	}
	if ( $column_name === 'Date_transaction' ) // колонка Последняя операция
	{
		echo get_current_user_id();
		//echo get_post_meta( get_the_ID(), 'Зарплата', true );//заменить post_meta на дуругую таблицу
	}

}, 10, 2 );//end add_action

// Выводим стили для своих столбцов. Необязательно.
add_action( 'admin_print_footer_scripts-edit.php', function () {
    ?>
    <style>

        .column-image img {
            max-width: 100%;
            height: auto;
        }
    </style>
    <?php
} );



// add_action( 'init', 'register_post_types' );
// function register_post_types(){
// 	register_post_type( 'igor_post', [
// 		'label'  => null,
// 		'labels' => [
// 			'name'               => 'WalletforUser', // основное название для типа записи
// 			'singular_name'      => 'Просмотреть кошелёк', // название для одной записи этого типа
// 			//'add_new'            => 'Добавить ____', // для добавления новой записи
// 			//'add_new_item'       => 'Добавление ____', // заголовка у вновь создаваемой записи в админ-панели.
// 			//'edit_item'          => 'Редактирование ____', // для редактирования типа записи
// 			//'new_item'           => 'Новое ____', // текст новой записи
// 			//'view_item'          => 'Смотреть ____', // для просмотра записи этого типа.
// 			'search_items'       => 'Искать ____', // для поиска по этим типам записи
// 			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
// 			//'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
// 			//'parent_item_colon'  => '', // для родителей (у древовидных типов)
// 			'menu_name'          => 'WalletforUser', // название меню
// 		],
// 		'description'         => '',
// 		'public'              => true,
// 		// 'publicly_queryable'  => null, // зависит от public
// 		// 'exclude_from_search' => null, // зависит от public
// 		// 'show_ui'             => null, // зависит от public
// 		// 'show_in_nav_menus'   => null, // зависит от public
// 		'show_in_menu'        => null, // показывать ли в меню адмнки
// 		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
// 		//'show_in_rest'        => null, // добавить в REST API. C WP 4.7
// 		'rest_base'           => null, // $post_type. C WP 4.7
// 		'menu_position'       => null,
// 		'menu_icon'           => null,
// 		//'capability_type'   => 'post',
// 		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
// 		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
// 		'hierarchical'        => false,
// 		'supports'            => [ 'title', 'editor' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
// 		//'taxonomies'          => [],
// 		'has_archive'         => false,
// 		//'rewrite'             => false,
// 		'query_var'           => true,
// 	] );
// }



// // Регистрируем свои колонки (столбцы)
// //igor_post имя поста записи
// add_filter ( 'manage_igor_post_posts_columns', function ( $columns ) {
//     $my_columns = [//массив колонок
// 		// 'image' => 'Миниатюра',
//         // 'Salary' => 'Зарплата',
// 		'ID' => 'ID',
// 		'User' => 'Пользователь',
// 		'Balance' => 'Баланс',
// 		'Date_transaction' => 'Последняя операция',
//     ];
// 	//array_slice для порядка колонок
//     return array_slice( $columns, 0, 2 ) + $my_columns + $columns;
// } );


// //add_theme_support('post-thumbnails', array('igor_post','post', 'page', 'slider', 'portfolio', 'book') ); //вставка изображения для типов

// //Выводим контент для каждого из своих столбцов. Обязательно.
// add_action ( 'manage_igor_post_posts_custom_column', function ( $column_name, $post_ID ) {
// 	// if ( $column_name === 'ID' ) // колонка Зарплата
// 	// {
// 	// 	echo get_post_meta( get_the_ID(), 'Зарплата', true );//заменить post_meta на дуругую таблицу
// 	// }
// 	var_dump($post_ID);
// 	if ( $column_name === 'User' ) // колонка Зарплата
// 	{
// 		echo get_users();
// 		echo get_post_meta( get_the_ID(), 'Зарплата', true );//заменить post_meta на дуругую таблицу
// 	}
// 	// if ( $column_name === 'Balance' ) // колонка Зарплата
// 	// {
// 	// 	echo get_post_meta( get_the_ID(), 'Зарплата', true );//заменить post_meta на дуругую таблицу
// 	// }
// 	// if ( $column_name === 'Date_transaction' ) // колонка Зарплата
// 	// {
// 	// 	echo get_post_meta( get_the_ID(), 'Зарплата', true );//заменить post_meta на дуругую таблицу
// 	// }
	
	

// }, 10, 2 );//end add_action








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

