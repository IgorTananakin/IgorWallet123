<?php
//доработать подключение этого файла
?>
<!-- <script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>-->
<script>
//	//дожидаемся полной загрузки страницы
window.onload = function () {
	
	    // полифилл CustomEvent для IE11
    (function () {
      if (typeof window.CustomEvent === "function") return false;
      function CustomEvent(event, params) {
        params = params || { bubbles: false, cancelable: false, detail: null };
        var evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
      }
      window.CustomEvent = CustomEvent;
    })();

    $modal = function (options) {
      var
        _elemModal,
        _eventShowModal,
        _eventHideModal,
        _hiding = false,
        _destroyed = false,
        _animationSpeed = 200;

      function _createModal(options) {
        var
          elemModal = document.createElement('div'),
          modalTemplate = '<div class="modal__backdrop" data-dismiss="modal"><div class="modal__content"><div class="modal__header"><div class="modal__title" data-modal="title">{{title}}</div><span class="modal__btn-close" data-dismiss="modal" title="Закрыть">×</span></div><div class="modal__body" data-modal="content">{{content}}</div>{{footer}}</div></div>',
          modalFooterTemplate = '<div class="modal__footer">{{buttons}}</div>',
          modalButtonTemplate = '<button type="button" class="{{button_class}}" data-handler={{button_handler}}>{{button_text}}</button>',
          modalHTML,
          modalFooterHTML = '';

        elemModal.classList.add('modal');
        modalHTML = modalTemplate.replace('{{title}}', options.title || '');
        modalHTML = modalHTML.replace('{{content}}', options.content || '');
        if (options.footerButtons) {
          for (var i = 0, length = options.footerButtons.length; i < length; i++) {
            var modalFooterButton = modalButtonTemplate.replace('{{button_class}}', options.footerButtons[i].class);
            modalFooterButton = modalFooterButton.replace('{{button_handler}}', options.footerButtons[i].handler);
            modalFooterButton = modalFooterButton.replace('{{button_text}}', options.footerButtons[i].text);
            modalFooterHTML += modalFooterButton;
          }
        }
        modalFooterHTML = modalFooterTemplate.replace('{{buttons}}', modalFooterHTML);
        modalHTML = modalHTML.replace('{{footer}}', modalFooterHTML);
        elemModal.innerHTML = modalHTML;
        document.body.appendChild(elemModal);
        return elemModal;
      }

      function _showModal() {
        if (!_destroyed && !_hiding) {
          _elemModal.classList.add('modal__show');
          document.dispatchEvent(_eventShowModal);
        }
      }

      function _hideModal() {
        _hiding = true;
        _elemModal.classList.remove('modal__show');
        _elemModal.classList.add('modal__hiding');
        setTimeout(function () {
          _elemModal.classList.remove('modal__hiding');
          _hiding = false;
        }, _animationSpeed);
        document.dispatchEvent(_eventHideModal);
      }

      function _handlerCloseModal(e) {
        if (e.target.dataset.dismiss === 'modal') {
          _hideModal();
        }
      }

      _elemModal = _createModal(options);

      _elemModal.addEventListener('click', _handlerCloseModal);
      _eventShowModal = new CustomEvent('show.modal', { detail: _elemModal });
      _eventHideModal = new CustomEvent('hide.modal', { detail: _elemModal });

      return {
        show: _showModal,
        hide: _hideModal,
        destroy: function () {
          _elemModal.parentElement.removeChild(_elemModal),
            _elemModal.removeEventListener('click', _handlerCloseModal),
            destroyed = true;
        },
        setContent: function (html) {
          _elemModal.querySelector('[data-modal="content"]').innerHTML = html;
        },
        setTitle: function (text) {
          _elemModal.querySelector('[data-modal="title"]').innerHTML = text;
        }
      }
    };

    (function () {
      var elemTarget;
      var modal = $modal({
        title: 'Купить этот матч?',
        content: '<p>Содержмиое модального окна...</p>',
        footerButtons: [
          { class: 'btn btn-2', text: 'ОК', handler: 'modalHandlerOk' },
          { class: 'btn btn-1', text: 'Отмена', handler: 'modalHandlerCancel' }
        ]
      });
//      document.addEventListener('show.modal', function (e) {
////        document.querySelector('.actions').textContent = 'Действия при открытии модального окна...';
//        // получить ссылку на DOM-элемент показываемого модального окна (.modal)
//        console.log(e.detail);
//      });
//      document.addEventListener('hide.modal', function (e) {
//        document.querySelector('.actions').textContent = 'Действия при закрытии модального окна...';
//        // получить ссылку на DOM-элемент скрываемого модального окна (.modal)
//        console.log(e.detail);
//      });
		document.addEventListener('click', function (e) {
		  //получение по событию значения атрибутов
			const match_id_value = event.target.getAttribute("alt_id_match");
			const price_match_value = event.target.getAttribute("alt_price");
			const user_id_value = event.target.getAttribute("alt_user_id");
        if (e.target.dataset.toggle === 'modal') {
          elemTarget = e.target;//записываем потому как тут ещё доступна ссылка
          modal.show();
          modal.setContent('ID матча  <b>' + match_id_value + '</b> <br> Цена матча <b>' + price_match_value + ' руб </b>');
			console.log(e.target);
			
			console.log(e.target.dataset);
			console.log(match_id_value);
        }  if (e.target.dataset.handler === 'modalHandlerCancel') {
          modal.hide();
//          document.querySelector('.message').textContent = 'Вы нажали на кнопку Отмена, а открыли окно с помощью кнопки ' + elemTarget.textContent;
        } if (e.target.dataset.handler === 'modalHandlerOk') {
			//тут e.target уже кнопка, а нужно ссылка elemTarget ссылка
			console.log(e.target);
			console.log(elemTarget);
			
			//console.log(match_id_value);
			//получение по событию значения атрибутов
			const match_id_value = elemTarget.getAttribute("alt_id_match");
			const price_match_value = elemTarget.getAttribute("alt_price");
			const user_id_value = elemTarget.getAttribute("alt_user_id");
			console.log(match_id_value);
			console.log(price_match_value);
			console.log(user_id_value);
          //пишем делегирование у div
//	document.getElementById('buttons')
//	.addEventListener('click', event => { 
		//вешаем на него событие
			const modal_test = document.querySelector('.modal1');
			document.querySelector('.close1').addEventListener('click', function () {
							modal_test.classList.remove('active');
						  });
			
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
//						console.log(res.status);
//						console.log(res.json());
//						console.log(json);
						return res.json();
					}
				})
				
//				.then(json => console.log(json))
//				.then(json => json.isSuccess === 1 ? document.querySelector('.message').textContent = 'Такой матч куплен' : json.isSuccess === 2 ?document.querySelector('.message').textContent = 'Недостаточно средств') :
				
				
				
				
//				.then( json => { if (json.isSuccess == 1) {document.querySelector('.message').textContent = 'Средства списаны'} 
//				if (json.isSuccess == 3) {document.querySelector('.message').textContent = 'Недостаточно средств'}
//				if (json.isSuccess == 2) {document.querySelector('.message').textContent = 'Такой матч куплен'}
//				})
				.then( json => { if (json.isSuccess == 1) {
					console.log('ответ');
					console.log(modal_test);
					modal_test.classList.add('active');
//					var scrollbar = document.body.clientWidth - window.innerWidth + 'px';
//				  console.log(scrollbar);
//				  document.querySelector('[href="#openModal"]').addEventListener('click', function () {
//					document.body.style.overflow = 'hidden';
//					document.querySelector('#openModal').style.marginLeft = scrollbar;
//				  });
//				  document.querySelector('[href="#close"]').addEventListener('click', function () {
//					document.body.style.overflow = 'visible';
//					document.querySelector('#openModal').style.marginLeft = '0px';
//				  });
				} 
				if (json.isSuccess == 3) {document.querySelector('.message').textContent = 'Недостаточно средств'}
				if (json.isSuccess == 2) {console.log('ответ');
					console.log(modal_test);
					modal_test.classList.add('active');
										  
//					document.addEventListener("DOMContentLoaded", function () {
//						  var scrollbar = document.body.clientWidth - window.innerWidth + 'px';
//						  console.log(scrollbar);
//						  document.querySelector('[href="#openModal"]').addEventListener('click', function () {
//							document.body.style.overflow = 'hidden';
//							document.querySelector('#openModal').style.marginLeft = scrollbar;
//						  });
						  
//						});				 
										 }
				})
				.catch(err => console.log(err))

				
				//catch отлавливает событие
				//feach js 
				//суть отправить json на файл processingAndInsert.php
			}			
			//получение по событию значения атрибутов
//			const match_id_value = elemTarget.getAttribute("alt_id_match");
//			const price_match_value = event.target.getAttribute("alt_price");
//			const user_id_value = event.target.getAttribute("alt_user_id");
//			console.log(match_id_value);

			
			if(price_match_value) //если есть значение
			{
				post_query_to_php({value: price_match_value,user_id_value,match_id_value});	//
			}
			
        	
    	
//    });
			
			modal.hide();
//          document.querySelector('.message').textContent = 'Вы нажали на кнопку ОК, а открыли окно с помощью кнопки ' + elemTarget.textContent;
        } 
//		  else if (e.target.dataset.dismiss === 'modal') {
////          document.querySelector('.message').textContent = 'Вы закрыли модальное окно нажав на крестик или на область вне модального окна, а открыли окно с помощью кнопки ' + elemTarget.textContent;
//        }
      });
    })();
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
//    //пишем делегирование у div
//	document.getElementById('buttons')
//	.addEventListener('click', event => { 
//		//вешаем на него событие
//        //спрашиваем 
//		let strconfirm = confirm("Вы действительно хотите купить матч?");
//		if (strconfirm == true) {
//			
//			const post_query_to_php = (body) => {
//				fetch('/wp-content/plugins/wordpress-plugin-demo-table-master/processingAndInsert.php', {
//					  method: 'POST',
//					  headers: {
//						'Content-Type': 'application/json;charset=utf-8'
//					  },
//					  body: JSON.stringify(body)
//				})
//				.then(res => {
//					if(res.status === 200) {
//						return res.json();
//					}
//				})
//				.then(json => json.isSuccess ? alert('Средства списаны') : alert('Недостаточно средств'))
//				.catch(err => console.log(err))
//				//catch отлавливает событие
//				//feach js 
//				//суть отправить json на файл processingAndInsert.php
//			}			
//			//получение по событию значения атрибутов
//			const match_id_value = event.target.getAttribute("alt_id_match");
//			const price_match_value = event.target.getAttribute("alt_price");
//			const user_id_value = event.target.getAttribute("alt_user_id");
//			
//
//			
//			if(price_match_value) //если есть значение
//			{
//				post_query_to_php({value: price_match_value,user_id_value,match_id_value});	//
//			}
//			
//        	
//    	} 
//    });
}
	
	
</script>