<?php
if ( !isset( $_GET['file'] ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
if (isset($_GET['file'])) {
    //$backupdir = ABSPATH.'dbtoolsbackups';
    $backupdir = '../../../dbtoolsbackups';
    $file_name = $_GET['file'];
    $pattern = '/^backup_[\w]+_[\d]{4}-[\d]{2}-[\d]{2}_[\d]{6}\.sql$/i';
    $checkfile = preg_match($pattern, $file_name);
    //$checkfile = TRUE;
    if ($checkfile) {
        header('Content-Type: text/plain');
        //header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"".$file_name."\"");
        readfile($backupdir.'/'.$file_name);
        exit;
    }
}
?>
