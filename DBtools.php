<?php

/*
  Plugin Name: DBtools
  Plugin URI: https://github.com/mdgeus/WP-DBtools
  Description: Usefull mysql Database tools
  Version: 1.1.5
  Author: Hands Off
  Author URI: http://dennis.famgeus.nl/
  License: GPL2
 */

 // Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

global $wpdb;
global $current_user;

if (!defined('DBtools_VERSION_KEY')) {
    define('DBtools_VERSION_KEY', 'DBtools_version');
}
if (!defined('DBtools_VERSION_NUM')) {
    define('DBtools_VERSION_NUM', '1.1.5');
}
add_option(DBtools_VERSION_KEY, DBtools_VERSION_NUM);



$dir = plugin_dir_path( __FILE__ );

#create the admin menu
add_action('admin_menu', 'dbtools_menu');

#create the menucode
function dbtools_menu() {
  //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  add_menu_page('dbtools Options', 'DBtools', 'manage_options', 'dbtools_menu', 'dbtools_options');
    //add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
    add_submenu_page('dbtools_menu', 'Maintenance', 'Maintenance', 'manage_options', 'dbtools_optimize', 'dbtools_optimize');
    add_submenu_page('dbtools_menu', 'Backup', 'Backup', 'manage_options', 'dbtools_backup', 'dbtools_backup');
}



function dbtools_listdb() {
    # listing tables
    }


function dbtools_del() {
    # delete backup file
    $file = $_GET['fn'];
    $backupdir = str_replace('\tools', '\backups' ,$dir);
    $backupdir = str_replace('/tools', '/backups' ,$backupdir);
    if(unlink($backupdir.$file)) {
        echo 'Backup file ' . $file . ' removed<br />';
    }
    }


function dbtools_optimize() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    global $wpdb;
		global $dir;
   include($dir.'tools/optimize.php');
   exit;
}

function dbtools_backup() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
   global $wpdb;
	global $dir;
   //$backupdir = $dir.'backups/';
   include($dir.'tools/backup.php');
   exit;
}


#menu page output here
function dbtools_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo '<p><b>DB tools</b> are some usefull database tools</p>';
    echo '<p>You can run the maintanence tools or backup your wordpress database from the menu</p>';
    echo '<p>Tools included:<br />
             <ul>
                <li>Optmimize tables</li>
                <li>Analyze tables</li>
                <li>Backup database</li>
             </ul></p>';
    echo '<hr />';
    echo '<p>Version '.DBtools_VERSION_NUM.'</p>';
    echo '</div>';
}

?>
