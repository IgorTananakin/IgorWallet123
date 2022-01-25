<?php 
//echo 'id' . $_GET['id'];
?>

<?php 

$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo $url;
//$url = 'http://site.ru/path?id=456';
	
$parts = parse_url($url); 
parse_str($parts['query'], $query); 
$match_id = $query['id'];

//// Все GET-параметры
//print_r($query);
 
// Параметр «email»
echo ' ' . $match_id; 
?>
<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		<label for="user">Выберите матч</label>
		<select class="price_match" name="price_match" id="">
			<option value="20">Матч лиги A 20 руб</option>
			<option value="10">Матч лиги B 10 руб</option>
		</select>
	
		<input name="user_id" type="text" class="user_id" style="visibility:hidden;" value="<?php echo get_current_user_id(); ?>">
		<input name="match_id" type="text" class="match_id" style="" value="<?php echo $match_id; ?>">
<!--		<button name="submit" style="background-color: transparent; color:blue; border:0;">Списать средства</button>-->
	<a id="match" class="submit_link" href="#" >Счёт матча 0-0</a>
<!--	<a href="?id=123">Ссылка</a>-->
	
<!--
	<a id="a" href="#">ссылка</a>
<script>
  a.href = '/';

  alert( 'атрибут:' + a.getAttribute('href') ); // '/'
  alert( 'свойство:' + a.href );  // полный URL

</script>
-->
</form>

<?php
//подключение js для того чтобы отправить форму по ссылке, а не по кнопке как было ранее
//html->js(отправка формы)->php(обработка и вставка в базу)->mysql(transaction)
include_once(__DIR__ . '/js/link.php');//в дальнейшем переделать подключение средства wp
