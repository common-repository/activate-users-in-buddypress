<?php

/**
 * bp_activate_users_admin()
 *
 * Checks for form submission, saves component settings and outputs admin screen HTML.
 */
function bp_activate_users_admin() {
	global $bp, $wpdb;
	/* If the form has been submitted and the admin referrer checks out(NONCE), save the settings */
	 if ( isset( $_POST['submit'] ) && check_admin_referer('bp-activate_users-settings') ) {
		$sql5 = "SELECT U.ID FROM $wpdb->users U ORDER BY U.ID DESC";
		$aallusers = $wpdb->get_col( $wpdb->prepare($sql5));
		echo "<h2>Results from Activation</h2>";
		// loop through the users
		foreach ( $aallusers as $iallusersUserID ) :
			// check to see if the user is active in wp_bp_xprofile_data
			$sql6 = "SELECT XP.user_id
			FROM $wpdb->bp_xprofile_data XP
			WHERE XP.user_id = $iallusersUserID";
			$acheck_for_user_xprofile = $wpdb->get_col( $wpdb->prepare($sql6));
			if ($acheck_for_user_xprofile) {
				echo $iallusersUserID;
				echo " - already exists, no action taken";
				echo "<br />";
			} else {
				// update the xprofile field for each user that does not have a record
				// grab this users nicename
				$user = get_userdata( $iallusersUserID );
				$nicename = $user->user_nicename;
				
				// create a new record in bp_xprofile_data and add the nicename
				xprofile_set_field_data( 1, $iallusersUserID, $nicename );
				echo $iallusersUserID;
				echo " - <strong>Activated</strong>";
				echo "<br />";	
			}
			unset ($acheck_for_user_xprofile);
		endforeach;
		echo '<hr size="1" />';
			
	 }

	?>
	<div class="wrap">
	<h2><?php _e( 'Activate Users In Buddypress', 'bp-example' ) ?></h2>
	<br />
	<?php if ( isset($updated) ) : ?><?php echo "<div id='message' class='updated fade'><p>" . __( 'Settings Updated.', 'bp-example' ) . "</p></div>" ?><?php endif; ?>
	<?php
	// collect the info about users in the database
	global $wpdb;
	$table_prefix = $wpdb->prefix; // wpdb->call not working on Xprofile table and bp_activity
				
	$sql = "SELECT $wpdb->users.ID FROM $wpdb->users ORDER BY ID DESC";
	$aUsersID = $wpdb->get_col( $wpdb->prepare($sql));
	$total_users = count($aUsersID);
	echo "<strong>$total_users</strong> Total Users | ";
	$sql7 = "SELECT XP.user_id
	FROM ".$table_prefix."bp_xprofile_data XP
	WHERE XP.user_id = $iUserID";
	$acount_user_xprofile = $wpdb->get_col( $wpdb->prepare($sql7));
	$total_not_active = count($acount_user_xprofile);
	
	if ($total_not_active == '0') {
		echo "<strong>0</strong> inactive users detected by this plug-in";	
	} else {
		echo " <strong>$total_not_active</strong> are not active in buddpress";
		if ( is_admin() ) {	// security check ... all clear.... display the form button ?>
			<form action="<?php echo site_url() . '/wp-admin/admin.php?page=bp-activate_users-settings' ?>" name="bp-activate_users-settings-form" id="bp-activate_users-settings-form" method="post">	
			<p class="submit alignright">
			<input type="submit" name="submit" value="<?php _e( 'Activate Users', 'bp-example' ) ?>"/>
			</p>
			<?php wp_nonce_field( 'bp-activate_users-settings' ); ?>
			</form>
			<?php
		} 
	}
?>
<p><small>Users from your previous Wordpress install NOT active in your BP community are marked with a green background.</small></p>
<div class="wrap">
<table class="widefat" cellspacing="0">
<thead>
<tr class="thead">
        <th scope="col" id="username" class="manage-column column-username" style="">Username</th>
        <th scope="col" id="email" class="manage-column column-email" style="">E-mail</th>
        <th scope="col" id="registered" class="manage-column column-registered" style="">X Profile</th>
		<th scope="col" id="comments" class="manage-column column-comments" style="">BP Activity</th>
		<th scope="col" id="comments" class="manage-column column-comments" style="">Last Activity</th>
        <th scope="col" id="comments" class="manage-column column-comments" style="">Role</th>
</tr>
</thead>
<tbody id="users" class="list:user user-list">
		<?php
        foreach ( $aUsersID as $iUserID ) :
			$user = get_userdata( $iUserID );
			$email = $user->user_email;
			$username = $user->display_name;
			$registered = strtotime($user->user_registered);
			// grab the user role
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};
			if ( !isset( $wp_roles ) )
			$wp_roles = new WP_Roles();
			foreach ( $wp_roles->role_names as $role => $name ) :	
				if ( array_key_exists( $role, $capabilities ) )
				$userrole = $role;
			endforeach;
			// check to see if the user is active in activity
			$sql2 = "SELECT *
			FROM ".$table_prefix."bp_activity activity 
			WHERE activity.user_id = $iUserID";
					$acheck_for_bp_activity = $wpdb->get_col( $wpdb->prepare($sql2));
					if ($acheck_for_bp_activity) {
						$check_for_bp_activity_answer = "<strong>yes</strong>";
					} else {
						$check_for_bp_activity_answer = "no";
					}
					unset ($acheck_for_bp_activity);
			// check to see if the user is active in wp_usermeta last_activity
			$sql3 = "SELECT UM.user_id, UM.meta_key 
			FROM $wpdb->usermeta UM
			WHERE UM.user_id = $iUserID
			AND UM.meta_key LIKE 'last_activity'";
					$acheck_for_user_meta = $wpdb->get_col( $wpdb->prepare($sql3));
					if ($acheck_for_user_meta) {
						$check_for_user_meta_answer = "<strong>yes</strong>";
					} else {
						$check_for_user_meta_answer = "no";
					}
					unset ($acheck_for_user_meta);
			// check to see if the user is active in wp_bp_xprofile_data
			$sql4 = "SELECT XP.user_id 
			FROM ".$table_prefix."bp_xprofile_data XP
			WHERE XP.user_id = $iUserID";
			$acheck_for_user_xprofile = $wpdb->get_col( $wpdb->prepare($sql4));
				$rowclass = '"background-color: #FFF;"';
				if ($acheck_for_user_xprofile) {
					$check_for_user_xprofile_answer = "<strong>yes</strong>";
				} else {
					$check_for_user_xprofile_answer = "no";
					$rowclass = '"background-color: #AAFFAA;"';
				}
				unset ($acheck_for_user_xprofile);
					?>
			<tr id="<?php echo $iUserID; ?>" style=<?php echo $rowclass; ?>>
				<td class="fullname column-fullname"><strong><a href="user-edit.php?user_id=<?php echo $user->ID ?>;wp_http_referer=%2Fblog%2Fwp-admin%2Fusers.php"><?php echo $user->user_login; ?> (ID: <?php echo $iUserID; ?>)</a></strong><br /><div class="row-actions"><span class='edit'><a href="user-edit.php?user_id=<?php echo $user->ID ?>&#038;wp_http_referer=%2Fblog%2Fwp-admin%2Fusers.php">Edit</a></span></div></td>
				<td class="email column-email">
				<?php
				echo '<a href="mailto:'.$email.'" title="email:'.$email.'">'.$email.'</a>';
				?>
				</td>
				<td class="registered column-registered"><?php echo $check_for_user_xprofile_answer; ?></td>
				<td>
				<?php echo $check_for_bp_activity_answer; ?>
				</td>
				<td>
				<?php echo $check_for_user_meta_answer; ?>
				</td>
				<td>
				<?php echo $userrole; ?>
				</td>
			</tr>
            <?php
        endforeach;
?>
</table>
</div>
	</div>
<?php
}
?>