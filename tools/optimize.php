<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

echo '<div class="wrap">';
?>
<form method="post" action="">
	<button type="submit" name="opt" id="opt" value="opt">Optimize ?</button>
	<label for="opt">Optimize tables (can be slow for InnoDB tables)</label>
	<br />
	<button type="submit" name="ana" id="ana" value="ana">Analyze ?</button>
	<label for="opt">Analyze tables </label>
	<br />
	<hr />
</form>
<?php
//echo DB_NAME;
//$query = "SHOW TABLES FROM " . DB_NAME;
$query = "SHOW TABLES FROM ".DB_NAME." LIKE '" . $wpdb->prefix."%'";
//$result = mysql_query($query);
$result = $wpdb->get_results( $query, ARRAY_N );

//echo $query;
if (!$result) {
	echo '<br />DB Error, could not list tables<br />';
	echo 'Database Error: ' . $wpdb->print_error();
	exit;
}

if (isset($_POST['opt'])) {

	echo '<br />';
	foreach ( $result as $row )  {
		$opt = "OPTIMIZE TABLE ".$row[0];
		$go = $wpdb->get_results( $opt, ARRAY_N );
		if ($go) {
			echo 'Table: '. $row[0] .' Optimized<br />';
		}
		else {
			echo 'Table: '. $row[0] .' Skipped<br />';
		}
	}

	echo '<br /><br />All tables optimized successfully<br /><hr /><br />';
}

if (isset($_POST['ana'])) {

	echo '<br />';
	// while ($row = mysql_fetch_row($result)) {
	foreach ( $result as $row )  {
		$opt = "ANALYZE TABLE " . $row[0];
		$go = $wpdb->get_results( $opt, ARRAY_N );
		if ($go) {
			echo 'Table: ' . $row[0] . ' Analyzed<br />';
		}
		else {
			echo 'Table: ' . $row[0] . ' Skipped<br />';
		}
	}

	echo '<br /><br />All tables analyzed successfully<br /><hr /><br />';
}

#no selection
#show table data
?>
Database name: <b><?php echo DB_NAME; ?></b><br />
Showing all tables in <?php echo DB_NAME; ?>&nbsp;
but only the wordpress tables (<?php echo $wpdb->prefix.'####' ?>) will be ussed<br />

<?php
$stat = "SHOW TABLE STATUS";
//$statr = mysql_query($stat);
$statr = $wpdb->get_results( $stat, OBJECT );
?>
<table width="400px" border="0">
	<tr>
		<td>Name</td><td>Size</td><td>Rows</td><td>Engine</td>
	</tr>
	<?php
	//while ($rstat = mysql_fetch_array($statr)) {
	foreach ( $statr as $rstat )  {
		//echo $fivesdraft->post_title;
		// $size = ($rstat["Data_length"] + $rstat["Index_length"]) / 1024;
		// echo '<tr><td>' . $rstat['Name'] . '</td><td>' . $size . ' KB.</td><td>' . $rstat['Rows'] . '</td><td>' . $rstat['Engine'] . '</td></tr>';
		$size = ($rstat->Data_length + $rstat->Index_length) / 1024;
		echo '<tr><td>' . $rstat->Name . '</td><td>' . $size . ' KB.</td><td>' . $rstat->Rows . '</td><td>' . $rstat->Engine . '</td></tr>';
	}
	?>
</table>
</div>
