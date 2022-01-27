<?php defined('ABSPATH') || exit;

class Plg_Table_Controller_Data
{
	/**
	 * Table class
	 */
	private $Table;
	
	/**
	 * Validate form
	 */
	private $Validate;

	//===========================================================
	// Light version [START]
	//===========================================================
	
   public function __construct()
   {
		add_action('admin_head', array($this, 'styleIndex'));
   }
	
	public function action()
	{
		switch(filter_input(INPUT_GET, 'action'))
		{
			case 'add':
				$this -> actionAdd();
			break;
			case 'edit':
				$this -> actionEdit();
			break;
			case 'delete':
				$this -> actionDelete();
			break;
			default:
				$this -> actionIndex();
			break;
		}
	}

	
//    public function action1()
//    {
//        switch(filter_input(INPUT_GET, 'action1'))
//        {
//            case 'add':
//                $this -> actionAdd();
//                break;
//            case 'edit':
//                $this -> actionEdit();
//                break;
//            case 'delete':
//                $this -> actionDelete();
//                break;
//            default:
//                $this -> actionIndex1();
//                break;
//        }
//    }
	
	public function view()
	{
		switch(filter_input(INPUT_GET, 'action'))
		{
			case 'add':
				$this -> viewAdd();
			break;
			case 'edit':
				$this -> viewEdit();
			break;
			default:
				$this -> viewIndex();
			break;
		}
	}
	
	
//	public function view1()
//	{
//		switch(filter_input(INPUT_GET, 'action'))
//		{
//			case 'add':
//				$this -> viewAdd();
//			break;
//			case 'edit':
//				$this -> viewEdit();
//			break;
//			default:
//				$this -> viewIndex1();
//			break;
//		}
//	}

	
	//===========================================================
	// Actions
	//===========================================================
	/**
	 * List data
	 */
	public function actionIndex()
	{
		$this -> Table = new Plg_Table_View_Admin_Data_Index;
		$this -> Table -> per_page = 10;
	}
	
//	public function actionIndex1()
//	{
//		$this -> Table = new Plg_Table_View_Admin_Data_Index;
//		$this -> Table -> per_page = 25;
//	}
	
	/**
	 * Data create
	 * 
	 * @global wpdb $wpdb
	 */
    //action для добавления средств
	public function actionAdd()
	{
		
//			if ( isset($_POST['submit1'])) 
//			{
//				var_dump('actionAdd');
//				global $wpdb;
//				$balance = (int)$_POST['balance'];
//				$id_user = (int)$_POST['id_user'];
//				var_dump($id_user);
//				$table_name_wallet = $wpdb->prefix . "wallet";
//				$wallet = $wpdb->get_results( "SELECT * FROM $table_name_wallet WHERE id_user = " . $id_user . "", ARRAY_A);
//				// get_current_user_id() получение текущего пользователя
//				if ($balance > 0) {
//					foreach ( $wallet as $wal ) {
//							//привожу к типу, получаю поле объекта, скаладываю текущий баланс с имеющимся
//						$balance = $balance + (int)$wal['balance'];
//						$wpdb->update($table_name_wallet,[ 'balance' => $balance], [ 'id_user' => get_current_user_id() ]);
//					}
//				} else {
//					echo "Ошибка записи";
//				}
//			}
		
	}
	/**
	 * Data update
	 * 
	 * @global wpdb $wpdb
	 */
	public function actionEdit()
	{
		global $wpdb;
		
		//Sets
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		
		$this -> Validate = $this -> _validate();
		
		if(Plg_Table_Helpers::isRequestPost() && Plg_Table_Helpers::isRequestAjax() == false && $this -> Validate -> validate())
		{
			$data_ar = $this -> Validate -> getData();
			
			$wpdb -> update(
				$wpdb -> prefix . 'wallet',
				array(
//					'field_one' => $data_ar['field_one'],
//					'field_two' => $data_ar['field_two'],
					'balance' => $data_ar['balance'],
					'id_user' => $data_ar['id_user'],
				),
				array('id' => $id),
				array('%s', '%s'),
				array('%d')
			);
			
			Plg_Table_Helpers::flashRedirect(add_query_arg(array('action' => 'edit', 'id' => $id)), __('Data updated', 'lance'));
		}
		else if(Plg_Table_Helpers::isRequestPost() == false)
		{
//			$data_ar = $wpdb -> get_row("SELECT *
//				FROM `" . $wpdb -> prefix . "wallet`
//				WHERE `id` = " . $id . "
//				LIMIT 1", 
//			ARRAY_A);
			
			$data_ar = $wpdb -> get_row("SELECT wp_wallet.id, wp_wallet.id_user, wp_wallet.balance, wp_wallet.date_create, wp_wallet.id_user, wp_users.user_email
				FROM wp_wallet
                INNER JOIN wp_users 
					ON wp_wallet.id_user = wp_users.id
				WHERE wp_wallet.id = 1
				LIMIT 1", 
			ARRAY_A);

			if($data_ar === NULL)
			{
				wp_die(__('Page not found', 'lance'));
			}

			$this -> Validate -> setData($data_ar);
		}
		
		if($this -> Validate -> isErrors())
		{
			Plg_Table_Helpers::flashShow('error', $this -> Validate -> getErrors());
		}
	}
	
	/**
	 * Delete
	 * 
	 * @global wpdb $wpdb
	 */
	public function actionDelete()
	{
		global $wpdb;
		
		$wpdb -> delete(
			$wpdb -> prefix. 'wallet',
			array('id' => filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)),
			array('%d')
		);
		
		Plg_Table_Helpers::flashRedirect(remove_query_arg(array('action', 'id')), __('Data deleted', 'lance'));
	}
	
	//===========================================================
	// Styles
	//===========================================================
	
	/**
	 * List
	 */
	public function styleIndex()
	{
		echo '<style type="text/css">';
		echo '.wp-list-table .column-id { width: 5%; }';
		echo '.wp-list-table .column-date_create  { width: 150px; }';
		echo '</style>';
	}
	
	//===========================================================
	// Views
	//===========================================================
	
	/**
	 * List data
	 */
	public function viewIndex()
	{
		Plg_Table_Controller_Data::InsertNewUser();
		//мой статический метод для вставки только что созданных пользователей 
		
        $this -> Table -> prepare_items();
		
		$btn_add_url = http_build_query(array(
			'page' => filter_input(INPUT_GET, 'page'),
			'action' => 'add'
		));
        ?>
            <div class="wrap">
                <h2>
					<?php echo __('Добавить средств', 'lance') ?>
					<a href="?<?php echo $btn_add_url ?>" class="page-title-action"><?php echo __('Добавить', 'lance') ?></a>
				</h2>
				<form method="get">
					<input type="hidden" name="page" value="<?php echo filter_input(INPUT_GET, 'page') ?>" />
					<?php $this -> Table -> search_box(__('Search', 'lance'), 'search_id'); ?>
					<?php $this -> Table -> display(); ?>
				</form>
            </div>
        <?php
		
		
		
	}
	
	
	public static function InsertNewUser() { //для автоматического создания новой записи в кошельке 
		//при условии что пользователь существует 
		global $wpdb;//получаю объект для работы с таблицами
		
		

	
		
		//действия если содержится записи
		$sql = "SELECT id FROM wp_wallet"; //получаю все текущие кошельки
		$result = $wpdb -> get_results($sql, ARRAY_A);//выполняю запрос
		$issetWalletUser = [];//преобразую массив где число значение, а не id значение
		//$issetWalletUser массив уже существующих пользователей
		foreach ($result as $res) {
			$issetWalletUser[] = $res['id'];
		}

		$sql = "SELECT ID FROM wp_users"; //получаю всех текущих пользователей
		$result = $wpdb -> get_results($sql, ARRAY_A);
		$dats = [];//преобразую массив где число значение, а не id значение
		foreach ($result as $data)
		{
			$dats[] = $data['ID'];	
		}
		
		$result1 = array_diff($dats,$issetWalletUser);
		//вычитаю массив всех пользователей из массива существующих пользователей у которых есть кошелёк
		foreach ($result1 as $dat) //остаток из разницы вставляю новые записи
		{
			$wpdb->insert( 'wp_wallet', [ 'id_user' => $dat] );
		}
	}
	
	
	
	
	/**
	 * Data create
	 */
	public function viewAdd()
	{
		echo Plg_Table_Helpers::view(PLG_TABLE__PATH . 'app/view/add', array(
			'page_title' => __('Добавление средств', 'lance'),
			'form_action' => add_query_arg(array('action' => 'add')),
			'Validate' => $this -> Validate,
		));
	}

	public function viewEdit()
	{
		echo Plg_Table_Helpers::view(PLG_TABLE__PATH.'app/view/add', array(
			'page_title' => __('Редактировать', 'lance'),
			'form_actiion' => add_query_arg(array('action' => 'edit', 'id' => filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT))),
			'Validate' => $this -> Validate,
		));
	}
	
	//===========================================================
	// Validate
	//===========================================================
	
	/**
	 * Validate
	 * @return PlanceValidate
	 */
	private function _validate()
	{
		return Plg_Table_Validate::factory(wp_unslash($_POST))
		-> setLabels(array(
//			'field_one' => __('Field one', 'lance'),
//			'field_two' => __('Field two', 'lance'),
			'id_user' => __('User', 'lance'),
			'balance' => __('Balance', 'lance'),
		))
		
//		-> setFilters('field_one', array(
//			'trim' => array(),
//			'strip_tags' => array(),
//		))
//				
//		-> setFilters('field_two', array(
//			'trim' => array(),
//			'strip_tags' => array(),
//		))
			
		-> setFilters('id_user', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
			
		-> setFilters('balance', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
		
//		-> setRules('field_one', array(
//			'required' => array(),
//			'max_length' => array(225),
//		))
//		-> setRules('field_two', array(
//			'required' => array(),
//			'max_length' => array(225),
//		))
		-> setRules('id_user', array(
			'required' => array(),
			'max_length' => array(225),
		))
		-> setRules('balance', array(
			'required' => array(),
			'max_length' => array(225),
		));
	}
}