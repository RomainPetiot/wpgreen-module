<?php

function wpgreen_RegisterSettings( )
{
	register_setting( 'wpgreen', 'wpgreen_nb_revision' );
}

// la fonction wpgreen_AdminMenu( ) sera exécutée
// quand WordPress mettra en place le menu d'admin
add_action( 'admin_menu', 'wpgreen_AdminMenu' );
function wpgreen_AdminMenu( )
{
	add_options_page(
	   'WPgreen setting',
	   'WPgreen setting',
	   'manage_options',
	   'wpgreen_setting',
	   'wpgreen_SettingsPage'
   );
	add_action( 'admin_init', 'wpgreen_RegisterSettings' );
}

function wpgreen_SettingsPage( )
{
	?>
	<div class="wrap">
		<h2><?php _e('Setting Check Cookie Lite', 'check-cookie-lite');?></h2>

		<form method="post" action="options.php">

			<?php
				settings_fields( 'wpgreen' );
			?>
			<table class="form-table">
				<?php
				wpgreen_add_option_text('wpgreen_nb_revision', __('Nombre de révision', 'wpgreen-plugin'));

				?>
			</table>
			<?php submit_button();?>
		</form>
	</div>
<?php
}


function wpgreen_add_option_text($name, $label){
	echo '	<tr valign="top">
				<th scope="row"><label for="'.$name.'">'.$label.'</label></th>
				<td><input type="text" id="'.$name.'" name="'.$name.'" class="regular-text" value="'.get_option( $name ).'" /></td>
			</tr>';

}
