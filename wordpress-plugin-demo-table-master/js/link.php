<?php
//доработать подключение этого файла
//echo 'id матча' . $_GET['id'];
?>

<script>
	//дожидаемся полной загрузки страницы
window.onload = function () {

    //получаем идентификатор элемента
    var a = document.getElementById('match');
    
    //вешаем на него событие
    a.onclick = function() {
        //производим какие-то действия
		let strconfirm = confirm("Вы действительно хотите купить матч?");
		if (strconfirm == true) {
			
			
			//обращаюсь к кнопке по классу
	const submit_link = document.querySelector('.submit_link');
	//вызов функции
	const submit_form = (e) => {
		e.preventDefault();//убирает дефолтные события у ссылки
		const price_match = document.querySelector('.price_match');//получение по классу price_match
		const price_match_value = price_match.value;//получение значение price_match
		
		const user_id = document.querySelector('.user_id');//получение по классу price_match
		const user_id_value = user_id.value;//получение значение price_match
		
		const match_id = document.querySelector('.match_id');//получение по классу price_match
		const match_id_value = match_id.value;//получение значение price_match
		
		if(price_match_value) //если есть значение
		{
		 post_query_to_php({value: price_match_value,user_id_value,match_id_value});	//
		}
	}
	
	submit_link.addEventListener('click', e => submit_form(e));//вызов события по кнопке
	
	const post_query_to_php = (body) => {
		fetch('/wp-content/plugins/wordpress-plugin-demo-table-master/processingAndInsert.php', {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/json;charset=utf-8'
			  },
			  body: JSON.stringify(body)
		})
		.then(res => {
			if(res.status === 200) {
				return res.json();
			}
		})
		.then(json => json.isSuccess ? alert('Средства списаны') : alert('Ошибка списания средств'))
		.catch(err => console.log(err))
		//catch отлавливает событие
		//feach js 
		//суть отправить json на файл processingAndInsert.php
	}
			
			
			
			
        	//this.innerHTML = 'On';
    	} else {

			//alert(id.getAttribute(name));
			
			
			//this.innerHTML = 'Off';
		}

    }
}
	
	
	
	
	
	
</script>