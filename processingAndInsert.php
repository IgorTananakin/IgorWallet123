<?php
//для ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//этот файл нужен для получения данных со строны js
//price_match и id пользователя передаются через json
$input = json_decode(file_get_contents("php://input"), true);//парсит из json

//записываю значения из массива
$price_match = (int)$input['value'];//перевожу в число
//var_dump($price_match);

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
	//подготовка запроса
	$sql = "SELECT true FROM wp_wallet_transaction 
			INNER JOIN wp_wallet ON wp_wallet.id_user = wp_wallet_transaction.wallet_id 
			WHERE wp_wallet_transaction.match_id = $match_id AND wp_wallet_transaction.wallet_id = $user_id";
	//суть запроса выявить если такой матч
	//выполнение
	$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );
	//получение в виде массива
	$result = mysqli_fetch_assoc($result);
	//проверка если такого матча нет покупаем
	if (empty($result)) {
		//получение данных из таблицы wp_wallet
		$sql = "SELECT * FROM wp_wallet WHERE id_user = $user_id";
		$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );

		/* извлечение ассоциативного массива */
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row['balance'] > $price_match) {
				//привожу к типу, получаю поле объекта, вычитаю цену
				$balance = (int)$row['balance'] - $price_match;
				//проверка на вставку и обновление
				$key_transaction = 1;
			} 
			else {
				$key_transaction = 3;
			} 
		}
	} else {
		$key_transaction = 2;
	}
	
	if ($key_transaction == 1) {
		//получение текущего unix времени
		$time = time();
		
		//формирование и вставка в таблицу wp_wallet_transaction
		$sql = "INSERT INTO wp_wallet_transaction(wallet_id,total_spent,date_transaction,match_id) VALUES ($user_id,$price_match,$time,$match_id)";
		$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );
		
		//формирование и обновление в таблицу wp_wallet
		$sql = "UPDATE wp_wallet SET balance = $balance WHERE id_user = $user_id";
		$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );

		//средства списаны
		$resMessage = ['isSuccess' => 1];
//		var_dump(json_encode($resMessage, JSON_UNESCAPED_UNICODE));
		echo json_encode($resMessage, JSON_UNESCAPED_UNICODE);
		
	} else if ($key_transaction == 2) {
		$resMessage = ['isSuccess' => 2];
//		var_dump(json_encode($resMessage, JSON_UNESCAPED_UNICODE));
		echo json_encode($resMessage, JSON_UNESCAPED_UNICODE);
	} else if ($key_transaction == 3) {
				$resMessage = ['isSuccess' => 3];
//		var_dump(json_encode($resMessage, JSON_UNESCAPED_UNICODE));
		echo json_encode($resMessage, JSON_UNESCAPED_UNICODE);
	}

}


	
