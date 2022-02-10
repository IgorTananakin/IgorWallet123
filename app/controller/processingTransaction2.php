<?php
//старый вариант с сылками рабочий
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



$user_id = $_GET['user_id'];
//var_dump($user_id);
$query = $db->query("SELECT wp_users.user_nicename FROM wp_users WHERE wp_users.ID = $user_id"); 
//$sql = "SELECT wp_users.user_nicename FROM wp_users WHERE wp_users.ID = $user_id";
//var_dump($query);
while($row = $query->fetch_assoc()){ 
	$fileName = $row["user_nicename"];
} 
$format = $_GET['format'];

$role = $_GET["role"];


 //Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}


// Excel file name for download 
$fileName = $fileName . date('Y-m-d');
if ($format === 'csv') {
	$fileName .= ".csv"; 
} else if ($format === 'xls') {
	$fileName .= ".xls";
}

 
// Column names 
$fields = array('ID операции',  'Пользователь', 'Сумма списания', 'матч ID', 'Дата транзакции'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from database 
if ($role == "admin") {
	$query = $db->query("SELECT wp_wallet_transaction.id, wp_users.user_nicename, wp_wallet_transaction.total_spent, wp_wallet_transaction.match_id, wp_wallet_transaction.date_transaction FROM wp_wallet_transaction  INNER JOIN wp_users ON wp_wallet_transaction.wallet_id = wp_users.ID  ORDER BY id ASC");
} else {
	$query = $db->query("SELECT wp_wallet_transaction.id,  wp_users.user_nicename, wp_wallet_transaction.total_spent, wp_wallet_transaction.match_id, wp_wallet_transaction.date_transaction FROM wp_wallet_transaction  INNER JOIN wp_users ON wp_wallet_transaction.wallet_id = wp_users.ID WHERE wallet_id = $user_id ORDER BY id ASC"); 
}


if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){ 
//		date("d-m-Y h:m:i",$current_timestamp);
//        $status = ($row['status'] == 1)?'Active':'Inactive'; 
        $lineData = array($row['id'],  $row['user_nicename'], $row['total_spent'], $row['match_id'], date("d-m-Y H:i:s",$row['date_transaction'])); 
//        array_walk($lineData, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
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

