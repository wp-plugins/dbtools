=== DBtools ===
Contributors: mdgeus
Tags: tool, tools, database, optimize, backup
Requires at least: 3.0.1
Tested up to: 4.2.3
Stable tag: 1.1.7
License: GPLv2 or later

Wordpress plugin for DB maintenance and backup

== Description ==
Simple tool you can use to backup your Wordpress tables and give an optimize and analyze command.

Functionality:
* Optimize tables
* Analyze tables
* database backup

! make sure you test the import of the created backup file, before you rely on the backups !

== Installation ==

To install a WordPress Plugin manually:

Download your WordPress Plugin to your desktop.
If downloaded as a zip archive, extract the Plugin folder to your desktop.
Read through the "readme" file thoroughly to insure you follow the installation instructions.
With your FTP program, upload the Plugin folder to the wp-content/plugins folder in your WordPress directory online.
Go to Plugins screen and find the newly uploaded Plugin in the list.
Click Activate Plugin to activate it.

The plugin folder is called DBtools, this is the folder you should upload.

== Screenshots ==

1. Create backups
2. optimize or analyse the WP tables

== Changelog ==

= 1.1.7 =
placed backup location outside the plugin directory so your backups will be kept after deactivating or updating this plugin

= 1.1.6 =
Now you can download your backup file

= 1.1.5 =
Added - IF NOT EXISTS to the drop table statement

= 1.1.4 =
Fixed some bugs and errors
switched to mysqli
reload the page after backup

= 1.1.3 =
Limited analyze and optimize to WP tables only

= 1.1.2 =
Fixed delete backup file
Changed backup filename to date_time for multiple backups per day

= 1.1.1 =
Fixed backup creation

= 1.1.0 =
Added table list
Added analyze tables

= 1.0.0 =
First stable
