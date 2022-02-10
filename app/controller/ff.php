<?php
global $wpdb;
//var_dump($wpdb);
$sql = " SELECT *
				FROM wp_wallet_transaction
			
        ";
//        var_dump($sql);
// var_dump($wpdb -> get_results($sql, ARRAY_A));
$results = $wpdb -> get_results($sql, ARRAY_A);
if (!empty($results)) {
    foreach ($results as $result) {
        var_dump($result);
    }
    $content = array('абвгд', 'abcdefg', '12345');
    echo json_encode($content);

}