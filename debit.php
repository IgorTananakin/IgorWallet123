<?php 
//echo 'id' . $_GET['id'];
?>

<?php 
//получение текущего URL
//$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
////echo $url;	
//$parts = parse_url($url); 
//
//if (isset($parts['query'])) {
//	parse_str($parts['query'], $query); 
//	$match_id = $query['id']; 
//}

?>
<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		
	
	<input name="user_id" type="text" class="user_id" style="visibility:hidden;" value="<?php echo get_current_user_id(); ?>">

<!--<div id="match" class="submit_link" alt_id_match="123456">-->
<!--    Счёт матча 01-0-->
<!--    <div alt_price="20" style="display: none;">Цена</div>-->
<!--</div>-->
<!---->
<!---->
<!--    <div id="match" class="submit_link" alt="123456">-->
<!--        Счёт матча 02-0-->
<!--        <div alt="20" style="display: none;">Цена</div>-->
<!--    </div>-->

	<a id="match" class="submit_link" href="#" alt_id_match="123456" alt_price="20" alt_user_id=" . <?php echo get_current_user_id(); ?> . ">Счёт матча 0444-0</a>
<!--	-->
<!--	<a id="match" class="submit_link" href="?match_id=01&price_match=200#" >Счёт матча 0-0</a>-->
<!--	<a id="match" class="submit_link" href="?match_id=02&price_match=150#" >Счёт матча 0-0</a>-->

</form>

<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		<select class="price_match" name="price_match" style="" id="">
			<option value="20">Матч лиги B 200 руб</option>
<!--			<option value="10">Матч лиги B 10 руб</option>-->
		</select>
	
		<input name="user_id" type="text" class="user_id" style="visibility:hidden;" value="<?php echo get_current_user_id(); ?>">
		<input name="match_id" type="text" class="match_id" style="" value="<?php echo $match_id; ?>">
<!--		<button name="submit" style="background-color: transparent; color:blue; border:0;">Списать средства</button>-->
	<a id="match" class="submit_link" href="#" >Счёт матча 1-0</a>
	

</form>


<form action="" method="post" id="my_form" onsubmit="pullOff(this);">
		<select class="price_match" name="price_match" style="visibility:hidden;" id="">
			<option value="20">Матч лиги C 300 руб</option>
<!--			<option value="10">Матч лиги B 10 руб</option>-->
		</select>
	
		<input name="user_id" type="text" class="user_id" style="visibility:hidden;" value="<?php echo get_current_user_id(); ?>">
		<input name="match_id" type="text" class="match_id" style="visibility:hidden;" value="<?php echo $match_id; ?>">
<!--		<button name="submit" style="background-color: transparent; color:blue; border:0;">Списать средства</button>-->
	<a id="match" class="submit_link" href="#" >Счёт матча 1-0</a>
	

</form>

<?php
//подключение js для того чтобы отправить форму по ссылке, а не по кнопке как было ранее
//html->js(отправка формы)->php(обработка и вставка в базу)->mysql(transaction)
include_once(__DIR__ . '/js/link.php');//в дальнейшем переделать подключение средства wp
