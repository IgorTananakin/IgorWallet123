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
            <form method="get">
                
               
                <?php $this -> Table1 -> display(); ?>
            </form>
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