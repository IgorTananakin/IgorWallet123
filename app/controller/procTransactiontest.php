<?php
////для ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//$text = "Ваш индивидуальный текст";
//$fp = fopen("hello.txt", 'w');
//var_dump($fp);
//fwrite($fp, $text);
//fclose($fp);


//этот файл нужен для получения данных со строны js
//price_match и id пользователя передаются через json
$input = json_decode(file_get_contents("php://input"), true);//парсит из json
//записываю значения из массива
$user_id = (int)$input['value'];//перевожу в число

//подключение к базе через mysqli, 
//возможно переделать и воспользоваться подключением через Wordpress
$servername = "localhost";
$username = "igorr2";
$password = "zV6yW6nU";
$dbname = "igorr2wordpress";

$conn = mysqli_connect($servername, $username, $password, $dbname);

//вывод для названия файла
$name_file = '';//название файла
//подготовка запроса 
$sql = "SELECT wp_users.user_nicename FROM wp_users WHERE wp_users.ID = $user_id";
//выполнение
$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );
//получение в виде массива и извлечение
while ($row = mysqli_fetch_row($result)) {
	$name_file = $row[0];
}


$sql = "SELECT * FROM wp_wallet_transaction WHERE wp_wallet_transaction.wallet_id = $user_id";
$result = mysqli_query( $conn, $sql )or die( "database error:" . mysqli_error( $conn ) );
$t = '';
while ($row = mysqli_fetch_array($result)) {
//        printf ("%s (%s)\n", $row[0], $row[1], $row[2]);
	$t = "Сумма " . $row[2] . "время " . $row[4] .$t;
	var_dump($t);
	echo "<br><pre>";
	var_dump($row );
	echo "</pre>";
    }



if(!empty($name_file)) { 
	echo "файл готов";

	$text = "Ваш индивидуальный текст1";
	
	$fp = fopen("$name_file.csv", "w");//поэтому используем режим 'w'
//	$fp = fopen("hello1.txt", 'w');
	while ($row = mysqli_fetch_assoc($result)) {
		 foreach ($row as $line) {
			fputcsv($fp, split(',', $line));
		}

	fwrite($fp, $row );

		  //	while ($row = mysqli_fetch_assoc($result)) {
//		$str =  'Код-'.$row[0].' Имя пользователя-'.$row[1].' Ссылка-'.$row[2]."\r\n"; 
//		var_dump($str);
		
	}
		  
		  
		 
	
	fclose($fp);
	
	}






//$query = mysql_query("SELECT * FROM Roles");
//$fp = fopen('file.csv', 'w');
//if($query)
//{
//   while($arr = mysql_fetch_array($query))
//   {
//	  foreach ($arr as $line) 
//	  	{
//			fputcsv($fp, split(',', $line));
//		}
//   }
// 
//}
//fclose($fp);

