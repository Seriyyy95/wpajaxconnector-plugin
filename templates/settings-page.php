<?php

/**
 * Provide a admin area view for the plugin
 *
 * @link       plugin_name.com/team
 * @since      1.0.0
 *
 * @package    WPConnector
 */
?>

<div class="wrap">
		        <div id="icon-themes" class="icon32"></div>  
		        <h2>Access keys for WPConnector</h2>
			<table>
				<tbody>
					<?php foreach($users as $data):?>
						<tr>
							<td><?php echo $data->user_login; ?></td>
							<td><input type="text" id="semmi_access_key" name="semmi_access_key" value="<?php echo str_repeat('*', 64); ?>" style="width: 600px" readonly/></td>
							<td><a href="<?php echo admin_url( 'admin-post.php' ); ?>?action=<?php echo \WPAjaxConnector\WPAjaxConnectorPlugin\SettingsPage::DELETE_KEY_ACTION_NAME ?>&user=<?php echo $data->ID; ?>"><b>X</b></a></td>
						</tr>	
					<?php endforeach;?>
				</tbody>
			</table>
		        <h2>Create a new key</h2>
			<form action="<?php echo admin_url( 'admin-post.php' ); ?>">
				<input type="hidden" name="action" value="<?php echo \WPAjaxConnector\WPAjaxConnectorPlugin\SettingsPage::ADD_KEY_ACTION_NAME ?>">
				<?php wp_dropdown_users() ?>
				<?php submit_button( 'Create a key' ); ?>
			</form>
</div>
