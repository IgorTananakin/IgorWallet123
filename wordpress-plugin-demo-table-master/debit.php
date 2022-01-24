
<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		<label for="user">Выберите матч</label>
		<select class="price_match" name="price_match" id="">
			<option value="20">Матч лиги A 20 руб</option>
			<option value="10">Матч лиги B 10 руб</option>
		</select>
	
		<input name="user_id" type="text" class="user_id" style="visibility:hidden;" value="<?php echo get_current_user_id(); ?>">
<!--		<button name="submit" style="background-color: transparent; color:blue; border:0;">Списать средства</button>-->
	<a class="submit_link" href="#">Счёт матча 0-0</a>
</form>

<?php
//подключение js для того чтобы отправить форму по ссылке, а не по кнопке как было ранее
//html->js(отправка формы)->php(обработка и вставка в базу)->mysql(transaction)
include_once(__DIR__ . '/js/link.php');//в дальнейшем переделать подключение средства wp
