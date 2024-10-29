<?php

/**
 * bp_activate_users_setup_globals()
 *
 * Sets up global variables
 */
function activate_users_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->activate_users->id = 'activate_users';
	$bp->activate_users->table_name = $wpdb->base_prefix . 'bp_activate_users';
	$bp->activate_users->format_notification_function = 'bp_activate_users_format_notifications';
	$bp->activate_users->slug = BP_activate_users_SLUG;

	/* Register this in the active components array */
	$bp->active_components[$bp->activate_users->slug] = $bp->activate_users->id;
}
add_action( 'bp_setup_globals', 'activate_users_setup_globals' );

/**
 * bp_activate_users_add_admin_menu()
 *
 * Add the Menu
 */
function activate_users_add_admin_menu() {
	global $bp;

	if ( !is_super_admin() )
		return false;

	require ( dirname( __FILE__ ) . '/activate-users-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Activate Users', 'bp-activate_users' ), __( 'Activate Users', 'bp-activate_users' ), 'manage_options', 'bp-activate_users-settings', 'bp_activate_users_admin' );
}
// The admin menu should be added to the Network Admin screen when Multisite is enabled
add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', 'activate_users_add_admin_menu' );


?>