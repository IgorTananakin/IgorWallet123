<?php
//Список ссылок
?>

<div id="buttons">
	<a id="match" class="submit_link" href="#" alt_id_match="8886" alt_price="20"  alt_user_id="<?php echo get_current_user_id(); ?>">Счёт матча 0-0</a>
	<a id="match" class="submit_link" href="#" alt_id_match="44444" alt_price="200" alt_user_id="<?php echo get_current_user_id(); ?>">Счёт матча 1-0</a>
	<a id="match" class="submit_link" href="#" alt_id_match="222222" alt_price="400" alt_user_id="<?php echo get_current_user_id(); ?>">Счёт матча 1-1</a>
</div>


<?php
//подключение js для того чтобы отправить ссылку в js и там обработать и отправить json на сервер
//html->js(отправка ссылки)->php(обработка и вставка в базу)->mysql(transaction)
include_once(__DIR__ . '/js/link.php');//в дальнейшем переделать подключение средствами wp
