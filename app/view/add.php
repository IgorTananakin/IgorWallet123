<?php 
defined('ABSPATH') || exit; 
//получение данных из таблицы wp_users
$users = get_users();
?>
<div class="wrap">
	<h2>
		<?php echo $page_title ?>
		<a href="<?php echo remove_query_arg(array('action', 'id', 'orderby', 'order')) ?>" class="page-title-action"><?php echo __('К списку кошельков', 'lance') ?></a>
	</h2>


<?php
//страница блок добавление средств
function addMoney($users) {
	//проверка если форма не заполнена списание средств и запись в базу
	if ( isset($_POST['submit1'])) 
	{
		global $wpdb;
		$balance = (int)$_POST['balance'];
		$id_user = (int)$_POST['id_user'];
		$table_name_wallet = $wpdb->prefix . "wallet";
		$wallet = $wpdb->get_results( "SELECT * FROM $table_name_wallet WHERE id_user = " . $id_user . "", ARRAY_A);
		// get_current_user_id() получение текущего пользователя
		if ($balance > 0) {
			foreach ( $wallet as $wal ) {
				//привожу к типу, получаю поле объекта, скаладываю текущий баланс с имеющимся
				$balance = $balance + (int)$wal['balance'];
				$wpdb->update($table_name_wallet,[ 'balance' => $balance, 'date_create' => time()], [ 'id_user' => $id_user ]);
				//Модальное окно средства списаны
				?><script>alert('Средства добавлены');</script><?php
			}
		} else {
			?><script>alert('Ошибка записи');</script><?php
		}
	}
	

?>

	<form action="" method="post" id="my_form" onsubmit="">
		<table class="form-table">
			<tr>
				<th scope="row">
					Пользователь
				</th>
				<td>
					
					<select name="id_user" id="">
					<?php 
						//способ который сделал как с сылками html->php->mysql(update) без классов
						foreach ($users as $key ) {
							foreach ($key as $data) {
								if($data->user_login !== NULL) {
						?>
									<option value="<?php echo strval($data->ID); ?>">
										<?php echo $data->user_login;?>
									</option>
						<?php
																}
													}
												  }
																?>
					</select>
                    
				</td>
			</tr>
			<tr>
				<th scope="row">Баланс</th>
				<td>
                    <input name="balance" type="text" class="f-text" value="">
				</td>
			</tr>
		</table>
		
		<button name="submit1" >Добавить средств</button>
	</form>


<?php

}
global $wpdb;
		
addMoney($users);?>
</div>