<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: Szymon Kargol (Shymi)
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."kls_special/infusion_db.php";
include INFUSIONS."kls_special/locale/English.php";

// Infusion general information
$inf_title = $locale['KLS_title'];
$inf_description = $locale['KLS_desc'];
$inf_version = "0.2";
$inf_developer = "Shymi";
$inf_email = "sym@wp.eu";
$inf_weburl = "http://r-fanatic.pl/";
$inf_folder = "kls_special"; // The folder in which the infusion resides.

// Delete any items not required below.
$inf_newtable[1] = DB_KLS_PUNKTACJA." (
pts_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
pts_name VARCHAR(50) NOT NULL DEFAULT '',
pts_team VARCHAR(50) NOT NULL DEFAULT '',
pts_points MEDIUMINT(8) NOT NULL DEFAULT '0',
pts_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (pts_id),
KEY pts_points (pts_points)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES('Teams', 
'kls', 
'openside(\"Klasyfikacja Konstruktorów\");
if (!defined(\"DBFKLSIS\")) { include_once INFUSIONS.\"kls_special/db_functions.php\";}
display_kls(\"teams\");
closeside();', '4', '3', 'php', '0', '0', '1')";
$inf_insertdbrow[2] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES('Drivers', 
'kls', 
'openside(\"Klasyfikacja Kierowców\");
if (!defined(\"DBFKLSIS\")) { include_once INFUSIONS.\"kls_special/db_functions.php\";}
display_kls(\"drivers\");
closeside();', '4', '3', 'php', '0', '0', '1')";
$inf_insertdbrow[3] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('pdrivers', 'Klasyfikacja kierowców', '".$inf_folder."')";
$inf_insertdbrow[4] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('pteams', 'Klasyfikacja zespołów', '".$inf_folder."')";
$inf_insertdbrow[5] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('visible_drivers', '5', '".$inf_folder."')";
$inf_insertdbrow[6] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('visible_teams', '5', '".$inf_folder."')";

//$inf_droptable[1] = DB_KLS_PUNKTACJA;

$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='kls'";
$inf_deldbrow[2] = DB_SETTINGS_INF." WHERE settings_inf='".$inf_folder."'";

$inf_adminpanel[1] = array(
	"title" => $locale['KLS_admin1'],
	"image" => "../infusions/kls_special/images/kls.gif",
	"panel" => "kls_admin.php",
	"rights" => "KLS"
);
?>