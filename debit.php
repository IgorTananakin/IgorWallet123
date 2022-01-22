<?php


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
<link rel="stylesheet" href="/wp-content/plugins/wordpress-plugin-demo-table-master/js/functions.js">
<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		<label for="user">Выберите матч</label>
		<select class="price_match" name="price_match" id="">
			<option value="20">Матч лиги A 20 руб</option>
			<option value="10">Матч лиги B 10 руб</option>
		</select>
	
		<input name="user_id" type="text" class="user_id" value="<?php echo get_current_user_id(); ?>">
		<button name="submit" style="background-color: transparent; color:blue; border:0;">Списать средства</button>
	<a class="submit_link" href="#">Счёт матча 0-0</a>
	
	
<!--		<input type="submit" name="submit"/>-->
</form>

<?php
//подключение js для того чтобы отправить форму по ссылке, а не по кнопке как было рание
//html->js(отправка формы)->php(обработка и вставка в базу)->mysql(transaction)
include_once(__DIR__ . '/js/link.php');//в дальнейшем переделать подключение средства wp
}

debit();