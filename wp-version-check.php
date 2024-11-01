<?php
/*
Plugin Name: WP Version Check 
Plugin URI: http://patchlog.com/wordpress/wp-version-check
Description: Allows you to manually check for new wordpress versions
Author: Mihai Secasiu
Version: 0.1
Licence: GPL
Author URI: http://patchlog.com/
*/

function wpvc_admin_menu() {
	global $wpvc_pa;
   if (function_exists('add_submenu_page')) {
        //add_options_page('Version Check', 'Version Check', 8, basename(__FILE__), 'wpvc_subpanel');
		add_submenu_page('tools.php', 'Version Check', 'Version Check', 8, basename(__FILE__), 'wpvc_subpanel');
        }
}


function wpvc_subpanel()
{
	global $wp_version,$wpvc_pa;
	?>
<div class="wrap">
                <h2>Check for new Wordpress versions NOW</h2>
                <form method="post">
		<input type="submit" name="check" value="Check" />
		</form>
<?

	if(isset($_POST['check'])){
		
		wp_version_check();
		$c = get_transient( 'update_core' );
		if ( is_object($c)) {
			$mv=$wp_version;
			foreach($c->updates as $u){
				if(version_compare($mv,$u->current,'<')){
					$mv=$u->current;
				}
			}
			if(version_compare($wp_version,$mv,'<')){
			?>
				<div id="update-nag">WordPress <?=$mv?> is available! <a href="update-core.php">Please update now</a>.</div>
					
				<?
			}else{?>
				<div id="update-nag">No new versions found</div>
			<?
			}
		}
	}
?>

<div class="postbox-container" style="width:30%;">
<div class="metabox-holder">
<div class="meta-box-sortables">
<?php
	$wpvc_pa->news(); 
?>

</div>
</div>
</div>

<div class="postbox-container" style="width:30%;">
<div class="metabox-holder">
<div class="meta-box-sortables">
<?php
	$wpvc_pa->plugin_like();
?>

</div>
</div>
</div>

<div class="postbox-container" style="width:30%;">
<div class="metabox-holder">
<div class="meta-box-sortables">
<?php
	$wpvc_pa->plugin_support();
?>

</div>
</div>
</div>
</div>
<?
}

add_action('admin_menu', 'wpvc_admin_menu');


if(!class_exists('Patchlog_Plugin_Admin')){
        require_once('patchlog_plugin_tools.php');
}
$wpvc_pa = new Patchlog_Plugin_Admin(false);
$wpvc_pa->hook='wp-version-check.php';
$wpvc_pa->share_link='http://patchlog.com/wordpress/manual-wordpress-version-check/';
$wpvc_pa->share_title='WP Version Check Plugin';
$wpvc_pa->share_excerpt="Manually check for new wordpress versions";
$wpvc_pa->share_sites=array('Twitter','FriendFeed','Identi.ca',
                        'Sphinn','StumbleUpon','Digg','Reddit',
                        'del.icio.us','Technorati','Google',
                        'Yahoo! Bookmarks');
$wpvc_pa->shortname='WP Version Check';
$wpvc_pa->filename='wp-version-check/wp-version-check.php';

