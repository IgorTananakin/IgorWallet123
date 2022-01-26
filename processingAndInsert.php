<?php
//для ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);




//echo 'id_match' . $_POST['id'];

//этот файл нужен для получения данных со строны js
//price_match и id пользователя передаются через json
$input = json_decode(file_get_contents("php://input"), true);//парсит из json

//записываю значения из массива
$price_match = (int)$input['value'];//перевожу в число

$user_id = (int)$input['user_id_value'];

$match_id = (int)$input['match_id_value'];

//подключение к базе через mysqli, 
//возможно переделать и воспользоваться подключением через Wordpress
$servername = "localhost";
$username = "igorr2";
$password = "zV6yW6nU";
$dbname = "igorr2wordpress";

$conn = mysqli_connect($servername, $username, $password, $dbname);



if (isset($price_match) ) {
	
	$sql = "SELECT true FROM wp_wallet_transaction 
			INNER JOIN wp_wallet ON wp_wallet.id_user = wp_wallet_transaction.wallet_id 
			WHERE wp_wallet_transaction.match_id = $match_id";
	//$result = $wpdb -> get_results($sql, ARRAY_A);
	$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );
	//var_dump($result);
	$result = mysqli_fetch_assoc($result);
	//var_dump($result);
	if (empty($result)) {
		//var_dump("dвавваыва");
		//массив пустой покупаем матч
		$sql = "SELECT * FROM wp_wallet WHERE id_user = $user_id";
		$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );

		/* извлечение ассоциативного массива */
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row['balance'] > $price_match) {
				//привожу к типу, получаю поле объекта, вычитаю цену
				$balance = (int)$row['balance'] - $price_match;
				//вывести true,false если в wp_wallet_transaction есть пользователь который купил id матча такой-то

				$key_transaction = 1;
			} 
		}
	} else {
		$key_transaction = 0;
	}
	
	if ($key_transaction == 1) {
		$time = time();
		//формирование и вставка в таблицу wp_wallet_transaction
		$sql = "INSERT INTO wp_wallet_transaction(wallet_id,total_spent,date_transaction,match_id) VALUES ($user_id,$price_match,$time,$match_id)";
		$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );
		//формирование и обновление в таблицу wp_wallet
		$sql = "UPDATE wp_wallet SET balance = $balance WHERE id_user = $user_id";
		$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );

//		echo "средства списаны1";
		$resMessage = ['isSuccess' => true];
		
		echo json_encode($resMessage, JSON_UNESCAPED_UNICODE);
		
	} else {
		$resMessage = ['isSuccess' => false];
		echo json_encode($resMessage, JSON_UNESCAPED_UNICODE);
	}

}


	
