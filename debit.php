<?php
//Список ссылок
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    .modal__backdrop {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(0, 0, 0, 0.5);
      opacity: 0;
      z-index: -1;
      pointer-events: none;
      transition: opacity0 .2s ease-in;
    }

    .modal__content {
      position: relative;
      width: auto;
      margin: 10px;
      transition: opacity 0.3s ease-in;
      display: flex;
      flex-direction: column;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-radius: 0.3rem;
      box-shadow: 0 0 7px 0 rgba(0, 0, 0, 0.3);
    }

    @media (min-width: 576px) {
      .modal__content {
        max-width: 500px;
        margin: 50px auto;
      }
    }

    .modal__show .modal__backdrop,
    .modal__show .modal__content {
      opacity: 1;
      z-index: 1050;
      pointer-events: auto;
      overflow-y: auto;
    }

    .modal__header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px;
      border-bottom: 1px solid #eceeef;
    }

    .modal__title {
      margin-top: 0;
      margin-bottom: 0;
      line-height: 1.5;
      font-size: 1.25rem;
      font-weight: 500;
    }

    .modal__btn-close {
      float: right;
      font-family: sans-serif;
      font-size: 24px;
      font-weight: 700;
      line-height: 1;
      color: #000;
      text-shadow: 0 1px 0 #fff;
      opacity: 0.5;
      text-decoration: none;
    }

    .modal__btn-close:focus,
    .modal__btn-close:hover {
      color: #000;
      text-decoration: none;
      cursor: pointer;
      opacity: 0.75;
    }

    .modal__btn-close:hover {
      color: #fff;
      background-color: #5a6268;
      border-color: #545b62;
    }

    .modal__body {
      position: relative;
      padding: 10px;
      overflow: auto;
    }

    .modal__footer {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding: 10px;
      border-top: 1px solid #e9ecef;
      border-bottom-right-radius: 0.3rem;
      border-bottom-left-radius: 0.3rem;
    }

    .btn {
      display: inline-block;
      font-weight: 400;
      color: #212529;
      text-align: center;
      vertical-align: middle;
      cursor: pointer;
      user-select: none;
      background-color: transparent;
      border: 1px solid transparent;
      padding: 4px 6px;
      font-size: 16px;
      line-height: 1.5;
      transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
      border-radius: 4px;
    }

    .btn-2 {
      color: #fff;
      background-color: #dc3545;
      border-color: #dc3545;
    }

    .btn-1 {
      color: #212529;
      background-color: #f8f9fa;
      border-color: #f8f9fa;
      margin-left: 10px;
    }

    .btn-2:hover {
      color: #fff;
      background-color: #c82333;
      border-color: #bd2130;
    }

    .btn-1:hover {
      color: #212529;
      background-color: #e2e6ea;
      border-color: #dae0e5;
    }
		  
/*	второе модальное окно*/
		  
		  /* свойства модального окна по умолчанию */
    .modal1 {
      position: fixed;
      /* фиксированное положение */
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(0, 0, 0, 0.5);
      /* цвет фона */
		cursor: pointer;
      z-index: 99999999999999999999;
      opacity: 0;
	  visibility: hidden;	
      /* по умолчанию модальное окно прозрачно */
      -webkit-transition: opacity 400ms ease-in;
      -moz-transition: opacity 400ms ease-in;
      transition: opacity 400ms ease-in;
      /* анимация перехода */
      
      /* элемент невидим для событий мыши */
		
    }
		  .modal1.active {
			  opacity: 1;
			  visibility: visible;
		  }

    /* при отображении модального окно */
    .modal1:target {
      opacity: 1;
      pointer-events: auto;
      overflow-y: auto;
    }

    /* ширина модального окна и его отступы от экрана */
    .modal-dialog1 {
      position: relative;
      width: auto;
      margin: 10px;
    }

    @media (min-width: 576px) {
      .modal-dialog {
        max-width: 500px;
        margin: 30px auto;
      }
    }

    /* свойства для блока, содержащего контент модального окна */
    .modal-content1 {
      position: relative;
      display: -webkit-box;
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -webkit-flex-direction: column;
      -ms-flex-direction: column;
      flex-direction: column;
      background-color: #fff;
      -webkit-background-clip: padding-box;
      background-clip: padding-box;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: .3rem;
      outline: 0;
    }

    @media (min-width: 768px) {
      .modal-content {
        -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
        box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
      }
    }

    /* свойства для заголовка модального окна */
    .modal-header1 {
      display: -webkit-box;
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -webkit-align-items: center;
      -ms-flex-align: center;
      align-items: center;
      -webkit-box-pack: justify;
      -webkit-justify-content: space-between;
      -ms-flex-pack: justify;
      justify-content: space-between;
      padding: 15px;
      border-bottom: 1px solid #eceeef;
    }

    .modal-title1 {
      margin-top: 0;
      margin-bottom: 0;
      line-height: 1.5;
      font-size: 1.25rem;
      font-weight: 500;
    }

    /* свойства для кнопки "Закрыть" */
    .close1 {
      float: right;
      font-family: sans-serif;
      font-size: 24px;
      font-weight: 700;
      line-height: 1;
      color: #000;
      text-shadow: 0 1px 0 #fff;
      opacity: .5;
      text-decoration: none;
    }

    /* свойства для кнопки "Закрыть" при нахождении её в фокусе или наведении */
    .close1:focus,
    .close1:hover {
      color: #000;
      text-decoration: none;
      cursor: pointer;
      opacity: .75;
    }

    /* свойства для блока, содержащего основное содержимое окна */
    .modal-body1 {
      position: relative;
      -webkit-box-flex: 1;
      -webkit-flex: 1 1 auto;
      -ms-flex: 1 1 auto;
      flex: 1 1 auto;
      padding: 15px;
      overflow: auto;
    }
  </style>
</head>
<body>
	
	<div  id="buttons1" style="display:flex; flex-direction: column;">
		<a id="match" class="show" data-toggle="modal" alt_id_match="88821" alt_price="10"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 0-0</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="88854451" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 1-0</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="1231" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 1-1</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="123451" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 2-1</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="123461" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 2-2</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="123471" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 2-3</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="123481" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 2-4</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="123491" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 2-5</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="1234101" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 3-5</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="1234111" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 4-5</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="1234121" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 5-5</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="1234131" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 6-5</a>
		<a id="match" class="show" data-toggle="modal" alt_id_match="1234131" alt_price="100"  alt_user_id="<?php echo get_current_user_id(); ?>">счёт 6-5</a>
	</div>
	
<!--  <button id="show-2" class="show" data-toggle="modal">show-2</button>-->
  <div class="message"></div>
	
	<div class="container1">
    <div id="openModal" class="modal1">
      <div class="modal-dialog">
        <div class="modal-content1">
          <div class="modal-header1">
            <h3 class="modal-title1">Операция</h3>
            <a href="#close" title="Close" class="close1">×</a>
          </div>
          <div class="modal-body1">
<!--            Средства списаны-->
          </div>
        </div>
      </div>
    </div>
  </div>

	

	
	


</body>
</html>



<?php
//подключение js для того чтобы отправить ссылку в js и там обработать и отправить json на сервер
//html->js(отправка ссылки)->php(обработка и вставка в базу)->mysql(transaction)
include_once(__DIR__ . '/js/link.php');//в дальнейшем переделать подключение средствами wp
