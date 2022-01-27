<?php
//доработать подключение этого файла
?>

<script>
//	//дожидаемся полной загрузки страницы
window.onload = function () {
    //пишем делегирование у div
	document.getElementById('buttons')
	.addEventListener('click', event => { 
		//вешаем на него событие
        //спрашиваем 
		let strconfirm = confirm("Вы действительно хотите купить матч?");
		if (strconfirm == true) {
			
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
				.then(json => json.isSuccess ? alert('Средства списаны') : alert('Недостаточно средств'))
				.catch(err => console.log(err))
				//catch отлавливает событие
				//feach js 
				//суть отправить json на файл processingAndInsert.php
			}			
			//получение по событию значения атрибутов
			const match_id_value = event.target.getAttribute("alt_id_match");
			const price_match_value = event.target.getAttribute("alt_price");
			const user_id_value = event.target.getAttribute("alt_user_id");
			

			
			if(price_match_value) //если есть значение
			{
				post_query_to_php({value: price_match_value,user_id_value,match_id_value});	//
			}
			
        	
    	} 
    });
}
	
	
</script>