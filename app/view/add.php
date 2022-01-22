<?php defined('ABSPATH') || exit; 
//получение данных из таблицы wp_users
	$users = get_users( );

?>

<div class="wrap">
	<h2>
		<?php echo $page_title ?>
		<a href="<?php echo remove_query_arg(array('action', 'id', 'orderby', 'order')) ?>" class="page-title-action"><?php echo __('К списку кошельков', 'lance') ?></a>
	</h2>
	<form method="post" action="<?php echo $form_actiion ?>" class="xyz-form-create">
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php echo $Validate -> getLabel('id_user');?>
				</th>
				<td>
					<select name="id_user" id="">
						
					<?php 
						
						foreach ( $users as $key ) {
							foreach ($key as $data) {
								if($data->user_login !== NULL) {
									
						?>
									<option value="<?php echo strval($data->ID); ?>">
										<?php echo $data->user_login;?>
									</option>
						<?php
																	}
														}
													}
							?>
						
					</select>
                    
				</td>
			</tr>
			<tr>
				<th scope="row"><?php echo $Validate -> getLabel('balance'); ?></th>
				<td>
                    <input name="balance" type="text" class="f-text" value="<?php echo esc_attr($Validate -> getData('balance')) ?>">
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>