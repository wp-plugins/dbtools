<?php
# Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

$dir = plugin_dir_path( __FILE__ );
$backupdir = str_replace('\tools', '\backups' ,$dir);
$backupdir = str_replace('/tools', '/backups' ,$backupdir);

echo '<div class="wrap">';
echo '<table width="400px" border="0">';
echo '<tr>';
echo '<td colspan="2">Available backups:</td>';
echo '</tr>';
if ($dh = opendir($backupdir)) {
    while (false !== ($entry = readdir($dh))) {
        if ($entry != "." && $entry != ".." && $entry != "index.html") {
        echo '<tr><form method="post" action="">';
        echo '<td><a href="'.plugins_url('DBtools/download.php?file='.$entry).'" title="Download SQL file">'.$entry.'</a></td>';
        echo '<td><input type="hidden" name="file" id="file" value="'.$entry.'" />';
        echo '<input type="submit" name="del" id="del" value="del" /></td>';
        echo '</form></tr>';
        }
    }
closedir($dh);
}
echo '</table>';
echo '<hr />';

?>
</form>


<form method="post" action="">
    <label for="go">Backup Database <?php echo DB_NAME; ?> ?</label>
    <button type="submit" name="go" id="go" value="go">Yes</button>
</form>
<?php
if (isset($_POST['del'])) {
    if (!unlink($backupdir.$_POST['file'])) {
        echo '<b>Could not delete</b> file '.$_POST['file'].'<br />';
    }
    else {
        #
        echo 'Backup file removed.<br />';
		echo '<script type="text/javascript">
		 window.location.href = "'.admin_url( "admin.php?page=dbtools_backup").'";
		</script>';
    }
}
if (isset($_POST['go'])) {
$tofile = "";
$query = "SHOW TABLES FROM ".DB_NAME." LIKE '" . $wpdb->prefix."%'";
$result = mysql_query($query);

 if (!$result) {
    echo '<br />DB Error, could not list tables<br />';
    echo 'MySQL Error: ' . mysql_error();
    exit;
 }
 $tofile.="# Backup created with the wordpress plugin DBtools\r\n";
 $tofile.="# database name: ". DB_NAME . "\r\n\r\n";
 $tofile.="# tables starting with : ". $wpdb->prefix . "\r\n\r\n";
  while ($row = mysql_fetch_row($result)) {
    $table = $row[0];
    $s = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($s);
    $tofile .= 'DROP TABLE IF EXISTS '.$table.';'."\r\n";
	//$tofile .= 'CREATE TABLE IF NOT EXISTS '.$table.';'."\r\n\r\n";
    $cr = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $tofile.= "\r\n\r\n".$cr[1].";\r\n\r\n";
    for ($i = 0; $i < $num_fields; $i++)  {
	    while($row = mysql_fetch_row($s)) {
				$tofile.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++)  {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = str_replace("\r\n\r\n","\r\n",$row[$j]);
					if (isset($row[$j])) {
                        $tofile.= '"'.$row[$j].'"' ;
                    }
                    else {
                        $tofile.= '""';
                    }
					if ($j<($num_fields-1)) {
                        $tofile.= ',';
                    }
				}
				$tofile.= ");\r\n";
			}
		}
		$tofile.="\r\n\r\n";
 }

	# save file
    $filename = $backupdir.'backup_'.DB_NAME.'_'.date('Y-m-d_His').'.sql';

    if (!$handle = fopen($filename, 'w+')) {
         echo "Cannot open file ($filename) <br />";
         exit;
    }
     if (fwrite($handle, $tofile) === FALSE) {
        echo "Cannot write to file ($filename) <br />";
        exit;
    }
    echo "Backup file ($filename) Created.<br />";
    fclose($handle);
	echo '<script type="text/javascript">
	 window.location.href = "'.admin_url( "admin.php?page=dbtools_backup").'";
	</script>';
}

if (isset($_POST['open'])) {
	// echo '<script type="text/javascript">
	//  window.location.href = "'.admin_url("admin.php?page=dbtools_download&file=$_POST[file]").'";
	// </script>';
	echo '<script type="text/javascript">
	 window.location.href = "'.admin_url("admin.php?page=dbtools_download").'";
	</script>';
}
//wp-admin/admin.php?page=dbtools_optimize
?>

</div>
