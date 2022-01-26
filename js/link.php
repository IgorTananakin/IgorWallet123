<?php
//доработать подключение этого файла
//echo 'id матча' . $_GET['id'];

?>

<script>
//	//дожидаемся полной загрузки страницы
window.onload = function () {
	
    //получаем идентификатор элемента
    var a = document.getElementById('match');
    
    //вешаем на него событие
    a.onclick = function() {
		
//		var currentLocation = window.location.search;
//		console.log(currentLocation);
		

		
		
        //производим какие-то действия
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
			
		
//			const price_match = document.querySelector('.price_match');//получение по классу price_match
//			const price_match_value = price_match.value;//получение значение price_match

			const user_id = document.querySelector('.user_id');//получение по классу price_match
			const user_id_value = user_id.value;//получение значение price_match
			
			
			// const currentLocation = document.getElementById("match");
			// const queryString = currentLocation.search; // Returns:'?match_id=123&price=20'
			// let params = new URLSearchParams(queryString);
			// const match_id_value = parseInt(params.get("match_id")); // id матча
			// const price_match_value = parseInt(params.get("price_match")); // price_match матча


            // const currentLocation = document.getElementById("match");
            // let id_match = currentLocation.getAttribute("alt");
            // console.log(id_match);
            // let price = document.querySelector('#match > *:first-child');
            // // let price = currentLocation.firstChild;
            // console.log(price.getAttribute("alt"));


            const currentLocation = document.getElementById("match");
            let id_match = currentLocation.getAttribute("alt_id_match");
            let price = currentLocation.getAttribute("alt_price");
            console.log(id_match);
            console.log(price);
            // let price = currentLocation.firstChild;
            // console.log(price.getAttribute("alt"));
            //
            //
            // console.log(match_id_value);
			// console.log(price_match_value);
		
//			const match_id = document.querySelector('.match_id');//получение по классу price_match
//			const match_id_value = match_id.value;//получение значение price_match

			
			if(price_match_value) //если есть значение
			{
				post_query_to_php({value: price_match_value,user_id_value,match_id_value});	//
			}
			
        	//this.innerHTML = 'On';
    	} 
    }
}
	
	
	
	
	
	
</script>