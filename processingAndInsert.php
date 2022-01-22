<?php
//для ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once(__DIR__ . '/../../../wp-config.php');
//этот файл нужен для получения данных со строны js
//price_match и id пользователя
//далее подключим файл insert.php для того чтобы были доступны функции Wordpress для удобства вставки в базу
//последнее можно сделать и тут, но нужно поновой подключаться к базе
$input = json_decode(file_get_contents("php://input"), true);//парсит из json
//$price_match = $_GET['val'];
//$user_id = $_GET['user_id'];
//записываю значения из массива
$price_match = $input['value'];//перевожу в число

var_dump($price_match);
$user_id = $input['user_id_value'];
var_dump($user_id);
echo $user_id;
if (isset($price_match) ) {
	
	global $wpdb;
//	var_dump($wpdb);
	//$price_match = (int)$_POST['price_match'];
	$table_name_wallet = $wpdb->prefix . "wallet";
	//var_dump($table_name_wallet);
		$wallet = $wpdb->get_results( "SELECT * FROM $table_name_wallet WHERE id_user = $user_id"  , ARRAY_A);
	//$wallet =  "SELECT * FROM $table_name_wallet WHERE id_user = $user_id";
	
	var_dump($wallet);
	// get_current_user_id() получение текущего пользователя
	foreach ( $wallet as $wal ) {
		if ($wal['balance'] > $price_match) {
			//привожу к типу, получаю поле объекта, вычитаю цену
			$balance = (int)$wal['balance'] - $price_match;

			$update = 1;
		} else {
			$update = 0;
		}

	}


	if ($update == 1) {
//			$time = time();
//				 var_dump($time);


//$wpdb->insert($table_name, array('id_user' => get_current_user_id(), 'balance' => $balance , 'total_spent' =>  $price_match , 'date_create' => time()) );

		$table_name_wallet_transaction = $wpdb->prefix . "wallet_transaction";
		$wpdb->insert($table_name_wallet_transaction, array('wallet_id' => get_current_user_id(), 'total_spent' =>  $price_match , 'date_transaction' => time()) );
		$wpdb->update($table_name_wallet,[ 'balance' => $balance], [ 'id_user' => get_current_user_id() ]);
		echo "средства списаны1";
	} else {
		echo "ошибка";
	}
}