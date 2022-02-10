<?php defined('ABSPATH') || exit;

class Plg_Table_Controller_Data1
{
	/**
	 * Table class
	 */
	private $Table1;
	
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
	

    public function action1()
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
                $this -> actionIndex1();
                break;
        }
    }
	
	
	
	
	public function view1()
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
				$this -> viewIndex1();
			break;
		}
	}

	
	//===========================================================
	// Actions
	//===========================================================
	/**
	 * List data
	 */
	
	
	public function actionIndex1()
	{
		$this -> Table1 = new Plg_Table_View_Admin_Data_Index1;
		$this -> Table1 -> per_page = 25;
	}
	
	/**
	 * Data create
	 * 
	 * @global wpdb $wpdb
	 */
	public function actionAdd()
	{
		global $wpdb;
		
		$this -> Validate = $this -> _validate();

		if(Plg_Table_Helpers::isRequestPost() && $this -> Validate -> validate())
		{
			$data_ar = $this -> Validate -> getData();
			
			$wpdb -> insert(
				$wpdb -> prefix . 'wallet',
				array(
//					'field_one'   => $data_ar['field_one'],
//					'field_two'   => $data_ar['field_two'],
					'id_user'   => $data_ar['id_user'],
//                    'balance'   => $data_ar['balance'],
					'total_spent'   => $data_ar['total_spent'],
					
					'date_create' => time(),
				),
				array('%s', '%s', '%d')
			);
			
			Plg_Table_Helpers::flashRedirect(add_query_arg(array('action' => 'add')), __('Data created', 'lance'));
		}
		
		if($this -> Validate -> isErrors())
		{
			Plg_Table_Helpers::flashShow('error', $this -> Validate -> getErrors());
		}
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
				$wpdb -> prefix . 'wallet_transaction',
				array(
					'field_one' => $data_ar['field_one'],
					'field_two' => $data_ar['field_two'],
					'balance' => $data_ar['balance'],
					
					'total_spent' => $data_ar['total_spent'],
				),
				array('id' => $id),
				array('%s', '%s'),
				array('%d')
			);
			
			Plg_Table_Helpers::flashRedirect(add_query_arg(array('action' => 'edit', 'id' => $id)), __('Data updated', 'lance'));
		}
		else if(Plg_Table_Helpers::isRequestPost() == false)
		{
			$data_ar = $wpdb -> get_row("SELECT *
				FROM `".$wpdb -> prefix . "wallet_transaction`
				WHERE `id` = " . $id . "
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
			$wpdb -> prefix. 'wallet_transaction',
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
	
	
	
	public function viewIndex1()
	{
        global $wpdb;
        //var_dump($wpdb);
        $user_id = get_current_user_id();
//        var_dump($user_id);
        ?>

        <?php
        $this -> Table1 -> prepare_items();
		
		$btn_add_url = http_build_query(array(
			'page' => filter_input(INPUT_GET, 'page'),
			'action' => 'add'
		));
       
        ?>
        <div class="wrap">
            <h2>
                <?php echo __('История операций', 'lance') ?>

            </h2>

            <?php
//			include_once(__DIR__ . '/transaction.php');
            //в дальнейшем переделать подключение средствами wp
            //файл подключения js для отправки на сервер

            ?>
<!--            <a href="../wp-content/plugins/wordpress-plugin-demo-table-master/app/controller/processingTransaction.php" class="btn export" alt_user_id="<?php //echo $user_id;?>"> Экспортировать транзакции в Exel</a>-->
			
			<?php 
				$role = "";
				//проверка какая роль
				if (Plg_Table_Controller_Data::is_user_role('administrator', $user_id)) {
					$role = "admin";
				} else {
					$role = "";
				}
			?>
			
			<div class="wrap">
				<a href="../wp-content/plugins/wordpress-plugin-demo-table-master/app/controller/processingTransaction.php?format=csv&user_id= <?php echo $user_id;?> &role=<?php echo $role;?>" > Экспортировать в CSV</a>
			
				<a href="../wp-content/plugins/wordpress-plugin-demo-table-master/app/controller/processingTransaction.php?format=xls&user_id= <?php echo $user_id;?> &role=<?php echo $role;?>" > Экспортировать в Exel</a>
			</div>
			
			
			<?php 
//		$time = time();
//			var_dump($time);
//			 $date = gmdate("d-m-Y H:i:s",$time);
//			var_dump($date);
//			date_default_timezone_set('Europe/London');
//
//if (date_default_timezone_get()) {
//    echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
//}
//
//if (ini_get('date.timezone')) {
//    echo 'date.timezone: ' . ini_get('date.timezone');
//}
			?>
<!--
			<div class="wrap">
				<form action="">
					<label class="display-5" for="period"> Задайте период</label>
					<select name="period" id="">
						<option value="">Январь 2022</option>
						<option value="">Февраль 2022</option>
					</select>
					<button>Экспортировать период</button>
				</form>
				
			</div>
-->
		<form action="../wp-content/plugins/wordpress-plugin-demo-table-master/app/controller/processingTransaction.php" method="post">
			<div class="alignleft actions">
				
				<?php 
		
					$date_all = $wpdb -> get_results("SELECT YEAR(wp_wallet_transaction.date_transaction4) as year, DATE_FORMAT(wp_wallet_transaction.date_transaction4,'%m') as number_mounth, MONTHNAME(wp_wallet_transaction.date_transaction4) as name_mounth FROM wp_wallet_transaction", ARRAY_A);
					
					foreach ($date_all as $date) {
						var_dump($date["number_mounth"]);
					}
					
					
					
		
				?>
				<label for="filter-by-date" class="screen-reader-text">Фильтр по дате</label>
				<select name="m" id="filter-by-date">
					<option selected='selected' value="not">Все даты</option>
					<?php 
//						$date_all = $wpdb -> get_row("SELECT YEAR(wp_wallet_transaction.date_transaction4) as year, MONTH(wp_wallet_transaction.date_transaction4) as number_mounth, MONTHNAME(wp_wallet_transaction.date_transaction4) as name_mounth FROM wp_wallet_transaction", ARRAY_A);
		
						foreach ($date_all as $date) {
						var_dump($date["year"]);
					
		
					?>
					<option  <?php echo "value='" . $date["year"] . $date["number_mounth"] . "'";?>Февраль 2022</option>
					<?php } ?>
				</select>
				<label for="filter-by-export" class="screen-reader-text">Фильтр по экспорту</label>
				<select name="format" id="filter-by-export">
					<option selected='selected' value="not">Не задавать фильтр на экспорт</option>
					<option  value='csv'>Экспортировать период в .CSV</option>
					<option  value='xls'>Экспортировать период в .XLS</option>
		<!--		<option  value='202112'>Декабрь 2021</option>-->
				</select>
				<input type="" style="display: none;" name="user_id" value="<?php echo $user_id; ?>">
				<input type="" style="display: none;" name="role" value="<?php echo $role; ?>">
				<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Фильтр"  />		
			</div>	
		</form>
		
		
		
	</div>
<?php		

//add_filter( 'tutsplus_lowercase_all', 'tutsplus_lowercase_all_callback', 10, 1 );
//function tutsplus_lowercase_all_callback( $content ) {
//    return strtolower( $content );
//}
// 
//add_filter( 'the_content', 'tutsplus_the_content' );
//function tutsplus_the_content( $content ) {
// 
//    // Don't proceed with this function if we're not viewing a single post.
//    if ( ! is_single() ) {
//        return $content;
//    }
// 
//    return apply_filters( 'tutsplus_lowercase_all', $content );
//}
			?>
			
			
			
            <form method="get">
                
               
                <?php $this -> Table1 -> display(); ?>
            </form>
			<?php the_posts_pagination(); ?>
        </div>
        <?php
       
	}
	
	/**
	 * Data create
	 */
	public function viewAdd()
	{
		echo Plg_Table_Helpers::view(PLG_TABLE__PATH . 'app/view/add', array(
			'page_title' => __('Добавление средств', 'lance'),
			'form_actiion' => add_query_arg(array('action' => 'add')),
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
			
			'id_user' => __('User ID', 'lance'),
			'user' => __('User', 'lance'),
//			'balance' => __('Balance', 'lance'),
			'balance' => __('Balance', 'lance'),
			'total_spent' => __('Сумма списания', 'lance'),
		))
		
		
			
		-> setFilters('id_user', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
		
		-> setFilters('user', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
			
		-> setFilters('balance', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
		
		-> setFilters('total_spent', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
		
		-> setRules('field_one', array(
			'required' => array(),
			'max_length' => array(225),
		))
		-> setRules('field_two', array(
			'required' => array(),
			'max_length' => array(225),
		))
		-> setRules('id_user', array(
			'required' => array(),
			'max_length' => array(225),
		))
		
		-> setRules('user', array(
			'required' => array(),
			'max_length' => array(225),
		))
			
		-> setRules('balance', array(
			'required' => array(),
			'max_length' => array(225),
		))
			
		-> setRules('total_spent', array(
			'required' => array(),
			'max_length' => array(225),
		));
	}
}