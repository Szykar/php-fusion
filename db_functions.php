<style type="text/css">
.side_panel {
background: #222;}
.kls_pts {
width: 100%;}
.kls_pts tr {
background: #444;
text-align: center;
color: #fff;}
.kls_pts tr:nth-child(even) {
background: #222;}
.kls_pts td:nth-child(1) {
width: 20px;}
.kls_pts td:nth-child(2) {
width: 130px;
padding-left: 10px;
text-align: left;}
.kls_pts td:last-child {
width: 40px;}
.kls_pts span {
color: #fff;}
.kls_pts a {
color: #fff;}
</style>
<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: db_functions.php
| Author: Szymon Kargol (Shymi)
+--------------------------------------------------------*/
define("DBFKLSIS", "TRUE");

function display_kls($type)
{
include INFUSIONS."kls_special/infusion_db.php";
include INFUSIONS."kls_special/locale/English.php";
include_once INCLUDES."infusions_include.php";
$inf_settings = get_settings("kls_special");
if($type == 'drivers')
{
$numrows = dbcount("(pts_id)", DB_KLS_PUNKTACJA, "pts_hidden='0'");
$result = dbquery(
	"SELECT p.pts_id, p.pts_name, p.pts_team, p.pts_points, tu.user_id, tu.user_name, tu.user_status
	FROM ".DB_KLS_PUNKTACJA." p
	LEFT JOIN ".DB_USERS." tu ON p.pts_name=tu.user_id
	WHERE pts_hidden='0'
	AND pts_team=''
	ORDER BY p.pts_points DESC 
	LIMIT 0,".$inf_settings['visible_drivers']
);
if (dbrows($result)) {
	echo "<table class='kls_pts' align='center' cellpadding='3'>
<tbody>\n";
	$i = 0;
	while ($data = dbarray($result)) {
		$lp = $i + 1;
		echo "<tr>";
		echo "<td><span>".$lp."</span></td>";
		echo "<td><span>";

		if ($data['user_name']) {
			echo "<span class='side'>".profile_link($data['pts_name'], $data['user_name'], $data['user_status'])."</span>";
		} else {
			echo $data['pts_name'];
		}
		
		echo "</span></td>";
		//echo "<td><span>".$data['pts_team']."</span></td>";
		echo "<td><span>".$data['pts_points']."</span></td>";
		echo "</tr>\n";
		$i++;
		//if ($i != $numrows) { echo "<br />\n"; }
	}
	if ($numrows > $inf_settings['visible_drivers']) {
		echo "<tr><td colspan='4'>\n<a href='".INFUSIONS."kls_special/klasyfikacja.php' class='side'><img src='".INFUSIONS."kls_special/images/more.png' width='20'></a>\n</td></tr>\n";
	}
	echo "</table>\n";
} else {
	echo "<div>".$locale['KLS_empty']."</div>\n";
}
} else {


$numrows = dbcount("(pts_id)", DB_KLS_PUNKTACJA, "pts_hidden='0'");
$result = dbquery(
	"SELECT pts_team, sum(pts_points) AS pts_points
	FROM ".DB_KLS_PUNKTACJA."
	WHERE pts_hidden='0'
	AND pts_name=''
	GROUP BY pts_team
	ORDER BY pts_points DESC 
	LIMIT 0,".$inf_settings['visible_teams']
);
if (dbrows($result)) {
	echo "<table class='kls_pts' align='center' cellpadding='3'>
<tbody>\n";
	$i = 0;
	while ($data = dbarray($result)) {
		$lp = $i + 1;
		echo "<tr>";
		echo "<td><span>".$lp."</span></td>";
		echo "<td><span>".$data['pts_team']."</span></td>";
		echo "<td><span>".$data['pts_points']."</span></td>";
		echo "</tr>\n";
		$i++;
	}
	if ($numrows > $inf_settings['visible_teams']) {
		echo "<tr><td colspan='4'>\n<a href='".INFUSIONS."kls_special/klasyfikacja.php' class='side'><img src='".INFUSIONS."kls_special/images/more.png' width='20'></a>\n</td></tr>\n";
	}
	echo "</table>\n";
} else {
	echo "<div>".$locale['KLS_empty']."</div>\n";
}


}
}
?>