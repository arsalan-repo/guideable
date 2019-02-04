<?php
/*
Plugin Name: Export Users
description: Export Users Plugin allows you to export users list and their informations in CSV file.
Version: 1.2
Author: Boopathi Rajan
Author URI: http://www.boopathirajan.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function register_wp_user_export_page() 
{
	add_submenu_page('tools.php', 'Export Users', 'Export Users', 'manage_options', 'wp-export-users', 'wp_export_users_page');
}
add_action('admin_menu', 'register_wp_user_export_page');

function wp_export_users_page()
{
	global $wpdb;
	$sql='SELECT * FROM '. $wpdb->users;
	$users=$wpdb->get_results($sql);
	$datas=array();
	$headers = array('S.No','ID','Username','Email','Display Name','First Name','Last Name','Registered Date');
	$filename=time()."_"."user-details.csv";
	$upload_dir   = wp_upload_dir();
	$file = fopen($upload_dir['basedir'].'/'.$filename,"w");
	fputcsv($file, $headers);	
	?>
	<div class="wrap">
		<h1>Export Users</h1>	
	<?php	
	$sno=1;
	if($users)
	{
		foreach($users as $user)
		{
			$user_meta=get_userdata($user->ID);
			$datas[]=$sno."|".$user->ID."|".$user->user_login."|".$user->user_email."|".$user->display_name."|".$user_meta->first_name."|".$user_meta->last_name."|".$user->user_registered;
			$sno++;
		}
	}
	foreach ($datas as $data)
	{
		fputcsv($file,explode('|',$data));
	}							
	fclose($file);
	
	$url=$upload_dir['baseurl'].'/'.$filename;
	$message = "Users Exported Successfully. Click <a href='".$url."' target='_blank'>here</a> to download";
	echo '<div class="wrap"><div style="width: 380px;margin: 20% 0% 0% 30%;"><div class="updated"><p>'.$message.'</p></div></div></div>';
	?>
	</div>
	<?php
}

/* Add action links to plugin list*/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_wp_export_user_action_links' );
function add_wp_export_user_action_links ( $links ) {
	 $settings_link = array('<a href="' . admin_url( 'tools.php?page=wp-export-users' ) . '">Export Users</a>');
	return array_merge( $links, $settings_link );
}
?>