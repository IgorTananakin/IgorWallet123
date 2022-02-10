<?php
////для ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "igorr2"; 
$dbPassword = "zV6yW6nU"; 
$dbName     = "igorr2wordpress"; 
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
}

$user_id = $_POST['user_id'];
//id user для проверки чтоб барать только свои

$query = $db->query("SELECT wp_users.user_nicename FROM wp_users WHERE wp_users.ID = $user_id"); 

while($row = $query->fetch_assoc()) { 
		$fileName = $row["user_nicename"];
}
//получение имя пользователя. Для названия файла

 
$format = $_POST['format'];
//для файла каким форматом экспортировать
$role = $_POST["role"];
//какая роль. Если admin, то все транзакции экспортировать
$date = $_POST['m'];
//получение периода
$date_year = substr($date, 0, 4);
$date_mounth = substr($date, 4, 6);
//парсинг значения
if ($date_mounth == 12) {
//проверки чтоб переключится на следующий месяц
	$count_year = $date_year + 1;
	$count_mounth = 1;
} else if ($date_mounth < 12) {
	$count_year = $date_year;
	$count_mounth = $date_mounth + 1;
}



if ($format == 'not') {
	// формат не задан redirect в плагин
	header('Location: https://igorr2.ddns.net/wp-admin/admin.php?page=plg-table1');
	exit;
	
} else if ($format !== 'not') {
	// формат задан экспорт
	
	$fileName = $fileName . date('Y-m-d');
	// формирование имени файла с форматом
	
	if ($format === 'csv') {
		$fileName .= ".csv"; 
	} else if ($format === 'xls') {
		$fileName .= ".xls";
	} 

	// Колонки в Exel файле
	$fields = array('ID операции',  'Пользователь', 'Сумма списания', 'матч ID', 'Дата транзакции'); 

	// Display column names as first row 
	$excelData = implode("\t", array_values($fields)) . "\n"; 

	// запись в файл
	if ($role == "admin") {
		//получение с учётом что админ
		if ($date === 'not') {
			//получение без учёта периода
			$query = $db->query("SELECT wp_wallet_transaction.id, wp_users.user_nicename, wp_wallet_transaction.total_spent, wp_wallet_transaction.match_id, wp_wallet_transaction.date_transaction FROM wp_wallet_transaction  INNER JOIN wp_users ON wp_wallet_transaction.wallet_id = wp_users.ID  ORDER BY id ASC");
			
		} else if (!empty($date_year) && !empty($date_mounth)) {
			
			//получение с учётом периода
			$query = $db->query("SELECT wp_wallet_transaction.id, wp_users.user_nicename, wp_wallet_transaction.total_spent, wp_wallet_transaction.match_id, wp_wallet_transaction.date_transaction, wp_wallet_transaction.date_transaction4 FROM wp_wallet_transaction  INNER JOIN wp_users ON wp_wallet_transaction.wallet_id = wp_users.ID WHERE wp_wallet_transaction.date_transaction4 BETWEEN '$date_year-$date_mounth-1' AND '$count_year-$count_mounth-1'");
			
//			var_dump($query);
//			while($row = $query->fetch_assoc()) { 
//				var_dump($row);
//			}
		}
		
	} else {
		//получение без учёта что админ
		
		if ($date === 'not') {
			
			$query = $db->query("SELECT wp_wallet_transaction.id,  wp_users.user_nicename, wp_wallet_transaction.total_spent, wp_wallet_transaction.match_id, wp_wallet_transaction.date_transaction FROM wp_wallet_transaction  INNER JOIN wp_users ON wp_wallet_transaction.wallet_id = wp_users.ID WHERE wallet_id = $user_id ORDER BY id ASC"); 
			
		} else if (!empty($date_year) && !empty($date_mounth)) {
			
			$query = $db->query("SELECT wp_wallet_transaction.id,  wp_users.user_nicename, wp_wallet_transaction.total_spent, wp_wallet_transaction.match_id, wp_wallet_transaction.date_transaction FROM wp_wallet_transaction  INNER JOIN wp_users ON wp_wallet_transaction.wallet_id = wp_users.ID WHERE wallet_id = $user_id AND wp_wallet_transaction.date_transaction4 BETWEEN '$date_year-$date_mounth-1' AND '$count_year-$count_mounth-1' ORDER BY id ASC");
			
		}
	}


	if ($query->num_rows > 0) { 
		
		// Output each row of the data 
		while($row = $query->fetch_assoc()){ 
	//		date("d-m-Y h:m:i",$current_timestamp);
	//        $status = ($row['status'] == 1)?'Active':'Inactive'; 
			$lineData = array($row['id'],  $row['user_nicename'], $row['total_spent'], $row['match_id'], date("d-m-Y H:i:s",$row['date_transaction'])); 
	//        array_walk($lineData, 'filterData'); 
			$excelData .= implode("\t", array_values($lineData)) . "\n"; 
			//сама запись
		} 
	}else{ 
		$excelData .= 'No records found...'. "\n"; 
	} 

	// Headers for download 
	header("Content-Type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=\"$fileName\""); 

	// Render excel data 
	echo $excelData; 

	exit;
	
}

