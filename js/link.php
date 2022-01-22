<?php
//доработать подключение этого файла
?>

<script>
	//обращаюсь к кнопке по классу
	const submit_link = document.querySelector('.submit_link');
	//вызов функции
	const submit_form = (e) => {
		e.preventDefault();//убирает дефолтные события у ссылки
		const price_match = document.querySelector('.price_match');//получение по классу price_match
		const price_match_value = price_match.value;//получение значение price_match
		
		const user_id = document.querySelector('.user_id');//получение по классу price_match
		const user_id_value = user_id.value;//получение значение price_match
		
		if(price_match_value) //если есть значение
		{
		 post_query_to_php({value: price_match_value,user_id_value});	//
		}
	}
	
	submit_link.addEventListener('click', e => submit_form(e));//вызов события по кнопке
	
	const post_query_to_php = (body) => {
		fetch('/wp-content/plugins/wordpress-plugin-demo-table-master/processingAndInsert.php'
			  , {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/json;charset=utf-8'
			  },
			  body: JSON.stringify(body)
		})
		.then(res => console.log(res))
		.catch(err => console.log(err))
		//feach js 
		//суть отправить json на файл test1.php
	}
</script>