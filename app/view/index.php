<?php defined('ABSPATH') || exit;

/**
 * Table
 */
//обязательная проверка
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class Plg_Table_View_Admin_Data_Index extends WP_List_Table
{
	public $per_page;
	
    /**
     * Prepare columns of table for showing
     */
    public function prepare_items()
    {
		global $wpdb;
		
		/** Determine the total number of records in the database */
		$total_items = $wpdb -> get_var("
			SELECT COUNT(`id`)
			FROM `" . $wpdb -> prefix . "wallet`
			{$this -> _getSqlWhere()}
		");
		
		
		/** Sets */
		$per_page = $this -> get_items_per_page($this -> per_page, 10);
		
		/** Set the data for pagination */
        $this -> set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ));

		/** Get the data to form a table */
        $data = $this -> table_data();
		
		$this -> _column_headers = $this -> get_column_info();
		
		/** Set the table data */
        $this -> items = $data;
    }
 
    /**
     * Title of columns of the table
     * @return array
     */
    public function get_columns()
    {
        return array(
            'id'		 => __('ID', 'lance'),
//            'field_one'	 => __('Field one', 'lance'),
//            'field_two'	 => __('Field two', 'lance'),
			//'id_user'	 => __('User ID', 'lance'),
			//'user'	 => __('User', 'lance'),
			'user_email'	 => __('Пользователь', 'lance'),
			'balance'	 => __('Баланс', 'lance'),
			
            'date_create'=> __('Последние действие', 'lance'),
        );
    }
 
    /**
     * An array of column names for which sorting is performed
     * @return array
     */
    public function get_sortable_columns()
    {
		
        return array(
			'id'		 => array('id', false),
//			'field_one'	 => array('field_one', false),
//			'field_two'	 => array('field_two', false),
			'id_user'	 => array('id_user', false),
			//'user'	 => array('user', false),
			'user'	 => array('user_email', false),
			'balance'	 => array('balance', false),
			'date_create'=> array('date_create', false),
		);
    }
 
    /**
     * Table data
     * @return array
     */
    private function table_data()
    {
		global $wpdb;
		
		/** Sets */
		$per_page = $this -> get_pagination_arg('per_page');
		$order_ar = $this -> get_sortable_columns();
		
		$get_orderby = filter_input(INPUT_GET, 'orderby');
		$order = filter_input(INPUT_GET, 'order') == 'asc' ? 'asc' : 'desc';
		$orderby = key_exists($get_orderby, $order_ar) ? $get_orderby: 'date_create';

		
//		SELECT *
//			FROM `" . $wpdb -> prefix . "wallet_transaction`
			
			
		
		
		$user = wp_get_current_user();//получение текущего пользователя объект 
		//проверка какая роль
		
		if (Plg_Table_Controller_Data::is_user_role('administrator', $user->ID)) {
			
			$sql = " SELECT wp_wallet.id, wp_wallet.id_user, wp_wallet.balance, wp_wallet.date_create,wp_wallet.id_user, wp_users.user_email
				FROM `" . $wpdb -> prefix . "wallet`
				{$this -> _getSqlWhere()}
				INNER JOIN wp_users 
				ON wp_wallet.id_user = wp_users.id
				ORDER BY `{$orderby}` {$order}
				LIMIT " . (($this -> get_pagenum() - 1) * $per_page) . ", {$per_page}	
			";
			
		} else {

			$sql = " SELECT wp_wallet.id, wp_wallet.id_user, wp_wallet.balance, wp_wallet.date_create,wp_wallet.id_user, wp_users.user_email
			FROM `" . $wpdb -> prefix . "wallet`
			{$this -> _getSqlWhere()}
			INNER JOIN wp_users 
			ON wp_wallet.id_user = wp_users.id
			WHERE wp_wallet.id_user = $user->ID
			ORDER BY `{$orderby}` {$order}
			LIMIT " . (($this -> get_pagenum() - 1) * $per_page) . ", {$per_page}
			";
		}
		
		
		//var_dump($sql );
		
//		var_dump($wpdb -> get_results($sql, ARRAY_A));
		

		return $wpdb -> get_results($sql, ARRAY_A);
		
		
    }
 
	/**
	 * Displays if there is no data
	 */
	public function no_items()
	{
	  echo __('Data not found', 'lance');
	}
	
    /**
     * Returns the contents of the column
     * @param  array $item item data array
     * @param  string $column_name the name of the current column
     * @return mixed
     */
	//отвечает за вывод в таблице
    public function column_default($item, $column_name)
    {
        switch($column_name)
		{
			case 'id':
//			case 'field_two':
//				return $item[$column_name] ? esc_attr($item[$column_name]) : '-';
			case 'balance':
				return $item[$column_name] ? esc_attr($item[$column_name]) : '-';
			case 'user':
				return $item[$column_name] ? esc_attr($item[$column_name]) : '-';
			case 'user_email':
				return $item[$column_name] ? esc_attr($item[$column_name]) : '-';
			case 'id_user':
				//подправить чтоб выводился пользователь
				return $item[$column_name] ? esc_attr($item[$column_name]) : '-';
        }
    }
	
	/**
	 * Returns data from a custom column
	 * @param string $item
	 * @return string
	 */
//	public function column_field_one($item)
//	{
//		return esc_attr($item['field_one']).$this -> row_actions(array(
//			'edit' => '<a href="' . add_query_arg(array('action' => 'edit', 'id' => $item['id'])) . '">' . __('edit', 'lance') . '</a>',
//			'delete' => '<a href="' . add_query_arg(array('action' => 'delete', 'id' => $item['id'])) . '" onclick="return confirm(\'' . __('Delete?', 'lance') . '\')">' . __('delete', 'lance').'</a>',
//		));
//	}
	
	public function column_field_one($item)
	{
		return esc_attr($item['field_one']) . $this -> row_actions(array(
			'edit' => '<a href="' . add_query_arg(array('action' => 'edit', 'id' => $item['id'])) . '">' . __('edit', 'lance') . '</a>',
			'delete' => '<a href="' . add_query_arg(array('action' => 'delete', 'id' => $item['id'])) . '" onclick="return confirm(\'' . __('Delete?', 'lance') . '\')">' . __('delete', 'lance').'</a>',
		));
	}

	/**
	 * Returns data from a custom column
	 * @param string $item
	 * @return string
	 */	
	public function column_date_create($item)
	{
		return date(get_option('date_format', 'd.m.Y') . ' ' . get_option('time_format', 'H:i'), $item['date_create']);
	}
	
	/********************************************************************************************************************/
	/************************************************* PRIVATE METHODS **************************************************/
	/********************************************************************************************************************/
	
	/**
	 * Get "where" for sql
	 * @global wpdb $wpdb
	 * @return string
	 */
	private function _getSqlWhere()
	{
		global $wpdb;
		
		$where = '';
		$get_s = filter_input(INPUT_GET, 's');
		
		if($get_s)
		{
			$where = 'WHERE ' . join(' OR ', array(
//				"`field_one` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
//				"`field_two` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
				"`id_user` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
				"`balance` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
			));
		}
		
		return $where;
	}
	private function _getSqlWhere1()
	{
		global $wpdb;
		
		$where = '';
		$get_s = filter_input(INPUT_GET, 's');
		
		if($get_s)
		{
			$where = 'WHERE ' . join(' OR ', array(
//				"`field_one` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
//				"`field_two` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
				"`total_spent` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
//				"`balance` LIKE  '%" . $wpdb -> _real_escape($get_s) . "%'",
			));
		}
		
		return $where;
	}
}