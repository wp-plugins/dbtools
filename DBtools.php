<?php

/*
Plugin Name: DBtools
Plugin URI: http://dennis.famgeus.nl/dbtools-plugin/
Description: Createa a backup of your wordpress tables and perform basic Analyze and optimize task.
Version: 1.1.11
Author: Hands Off
Author URI: http://www.hands-off.it/
License: GPL2
*/


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

global $wpdb;
global $current_user;

$dir = plugin_dir_path( __FILE__ );
DEFINE('BACKUPDIR', ABSPATH.'dbtoolsbackups');

if (!defined('DBtools_VERSION_NUM')) {
    define('DBtools_VERSION_NUM', '1.1.11');
}

add_option(DBtools_VERSION_NUM);

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


// Installation script
function dbtools_install() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    if (!file_exists(BACKUPDIR)) {
        mkdir(BACKUPDIR, 0775, true);
        if (!file_exists(BACKUPDIR.'/index.php')) {
            $fh = fopen(BACKUPDIR.'/index.php', 'w+');
            $indexfile = "<?php"."\n";
            $indexfile .= "echo 'No peeking here!';"."\n";
            $indexfile .= "exit;"."\n";
            $indexfile .= "?";
            $indexfile .= ">"."\n";
            fwrite($fh, $indexfile);
        }
    }
}

function dbtools_update() {
        // just double checking if the new backupfolder is present
        if (!file_exists(BACKUPDIR)) {
            mkdir(BACKUPDIR, 0775, true);
            if (!file_exists(BACKUPDIR.'/index.php')) {
                $fh = fopen(BACKUPDIR.'/index.php', 'w+');
                $indexfile = "<?php"."\n";
                $indexfile .= "echo 'No peeking here!';"."\n";
                $indexfile .= "exit;"."\n";
                $indexfile .= "?";
                $indexfile .= ">"."\n";
                fwrite($fh, $indexfile);
            }
        }
}

function dbtools_listdb() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    # listing tables
}

function dbtools_del() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    # delete backup file
    $file = $_GET['fn'];
    if(unlink(BACKUPDIR.$file)) {
        echo 'Backup file ' . $file . ' removed<br />';
    }
    exit;
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
    //BACKUPDIR = $dir.'backups/';
    include($dir.'tools/backup.php');
    exit;
}

function dbtools_download() {
    if (isset($_GET['file'])) {
        $file_name = $_GET['file'];
        $pattern = '/^backup_[\w]+_[\d]{4}-[\d]{2}-[\d]{2}_[\d]{6}\.sql$/i';
        $checkfile = preg_match($pattern, $file_name);
        //$checkfile = TRUE;
        if ($checkfile) {
            header('Content-Type: text/plain');
            //header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"".$file_name."\"");
            readfile(BACKUPDIR.$file_name);
            exit;
        }
    }

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
    echo '<p>Known issues:<br />Not working with PHP5.6.<br />Downloading, opening backupfile from the backuplist will come soon.</p>';
    echo '</div>';
}

register_activation_hook(__FILE__, 'dbtools_install');
add_action( 'plugins_loaded', 'dbtools_update' );
?>
