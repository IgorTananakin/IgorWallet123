<?php
//include_once(__DIR__ . '/js/functions.php');
////подключение для подтверждения отправки формы
////подключил с расчётом чтоб не прописывать путь от корня

//страница блок списания
function debit() {
	//проверка если форма не заполнена списание средств и запись в базу
	if ( isset($_POST['submit'])) 
	{
		global $wpdb;
		$price_match = (int)$_POST['price_match'];
		$table_name_wallet = $wpdb->prefix . "wallet";
		$wallet = $wpdb->get_results( "SELECT * FROM $table_name_wallet WHERE id_user = " . get_current_user_id() . "", ARRAY_A);
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
	
?>

<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		<label for="user">Выберите матч</label>
		<select name="price_match" id="">
			<option value="20">Матч лиги A 20 руб</option>
			<option value="10">Матч лиги B 10 руб</option>
		</select>
		<button name="submit" >Списать средства</button>
<!--		<input type="submit" name="submit"/>-->
</form>
<?php

}

debit();