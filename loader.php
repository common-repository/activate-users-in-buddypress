<?php
/*
Plugin Name: Activate Users In Buddypress
Description: This plug-in is intended to assist developers in making sure all previous wordpress users have been correctly pulled into Buddypress. This very simple plug-in checks for the key user meta fields (X Profile, BP Activity, and Last Activity), provides a list of all your user and a legend as to which META exists. If any information is missing, a button enables administration to bring users into the BP member arena by adding these key fields. This plug-in is intended for migration from older version of Wordpress & Buddypress where due to anomolies user meta has not been added correctly.
Plugin URI: http://www.simondelasalle.com/expertise/wordpress-websites/
Version: 1.1
Revision Date: 07 23, 2012
License: (GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html)
Author: Simon de la Salle
Author URI: http://www.simondelasalle.com
Site Wide Only: true
*/


/*************************************************************************************************************
 ---  Built using ----- SKELETON COMPONENT V 1.5 ---
 *************************************************************************************************************/
 

/* Only load the component if BuddyPress is loaded and initialized. */
function activate_users_init() {
	require( dirname( __FILE__ ) . '/includes/activate-users.php' );
}
add_action( 'bp_include', 'activate_users_init' );


function activate_users_activate() {

	// activate component
	// no install or uninstall is required for this plugin
	
}
register_activation_hook( __FILE__, 'activate_users_activate' );


function activate_users_deactivate() {

	// deactivate component
	// no install or uninstall is required for this plugin
	
}
register_deactivation_hook( __FILE__, 'activate_users_deactivate' );
?>