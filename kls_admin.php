<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: kls_admin.php
| Author: Szymon Kargol (Shymi)
+--------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";

include INFUSIONS."kls_special/infusion_db.php";
include INFUSIONS."kls_special/locale/English.php";

if (!checkrights("S") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }

$nav = "<table cellpadding='0' cellspacing='0' class='tbl-border' align='center' style='width:400px; margin-bottom:20px; text-align:center;'>\n<tr>\n";
$nav .= "<td class='".(!isset($_GET['page']) || $_GET['page'] != "settings" && $_GET['page'] != "pointing" ? "tbl2" : "tbl1")."'><a href='".FUSION_SELF.$aidlink."'>".$locale['KLS_admin1']."</a></td>\n";
$nav .= "<td class='".(isset($_GET['page']) && $_GET['page'] == "settings" ? "tbl2" : "tbl1")."'><a href='".FUSION_SELF.$aidlink."&amp;page=settings'>".$locale['KLS_settings']."</a></td>\n";
$nav .= "<td class='".(isset($_GET['page']) && $_GET['page'] != "settings" && $_GET['page'] == "pointing" ? "tbl2" : "tbl1")."'><a href='".FUSION_SELF.$aidlink."&amp;page=pointing'>".$locale['KLS_add_points']."</a></td>\n";
$nav .= "</tr>\n</table>\n";

if (!isset($_GET['page']) || $_GET['page'] != "settings" && $_GET['page'] != "pointing") {
	opentable($locale['KLS_title']);
	echo $nav;
	echo "<div class=\"side-panel\">\n
<div style=\"text-align:center\">".$locale['KLS_desc']."</div>\n
<div style=\"background: rgba(0, 0, 0, 0.2); padding: 4px;\">".$locale['KLS_tut']."<br>
</div>\n
</div>\n";
	closetable();
} else if ($_GET['page'] == "settings"){
	include INCLUDES."infusions_include.php";
	if (isset($_POST['kls_settings'])) {
		if (isset($_POST['visible_drivers']) && isnum($_POST['visible_drivers'])) {
			$setting = set_setting("visible_drivers", $_POST['visible_drivers'], "kls_special");
		}
		if (isset($_POST['visible_teams']) && isnum($_POST['visible_teams'])) {
			$setting = set_setting("visible_teams", $_POST['visible_teams'], "kls_special");
		}
		redirect(FUSION_SELF.$aidlink."&amp;page=settings&amp;status=update_ok");
	}
	if (isset($_GET['status'])) {
		if ($_GET['status'] == "delall" && isset($_GET['numr']) && isnum($_GET['numr'])) {
			$message = number_format(intval($_GET['numr']))." ".$locale['SB_shouts_deleted'];
		} elseif ($_GET['status'] == "update_ok") {
			$message = $locale['KLS_update_ok'];
		}
	}
	if (isset($message) && $message != "") {	echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }

	$inf_settings = get_settings("kls_special");
	opentable($locale['KLS_settings']);
	echo $nav;
	echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;page=settings'>\n";
	echo "<table cellpadding='0' cellspacing='0' align='center' class='tbl-border' style='width:300px; margin-top:20px;'>\n";
	echo "<tr>\n";
	echo "<td class='tbl1'>".$locale['KLS_visible_drivers']."</td>\n";
	echo "<td class='tbl1'><input type='text' name='visible_drivers' class='textbox' value='".$inf_settings['visible_drivers']."' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<tr>\n";
	echo "<td class='tbl1'>".$locale['KLS_visible_teams']."</td>\n";
	echo "<td class='tbl1'><input type='text' name='visible_teams' class='textbox' value='".$inf_settings['visible_teams']."' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<tr>\n";
	echo "<td class='tbl1' colspan='2' style='text-align:center;'><input type='submit' name='kls_settings' value='".$locale['KLS_submit']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n";
	echo "</form>\n";
	closetable();
} else if ($_GET['page'] == "pointing") {
	include INCLUDES."infusions_include.php";
	$inf_settings = get_settings("kls_special");	
	opentable($locale['KLS_add_points']);
	echo $nav;
	
if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "pa") {
		$message = $locale['KLS_added'];
	} else if ($_GET['status'] == "da") {
		$message = $locale['KLS_addedd'];
	} else if ($_GET['status'] == "e") {
		$message = $locale['KLS_e'];}
	if ($message) {	echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
} else {
	if (isset($_POST['savepts']) && (isset($_GET['pts_id']) && isnum($_GET['pts_id']))) {
		$pts = $_POST['pts_points'];
		$name = $_POST['pts_name'];
		if ($_POST['savepts']) {
			$result = dbquery("UPDATE ".DB_KLS_PUNKTACJA." SET pts_points='$pts', pts_name='$name' WHERE pts_id='".$_GET['pts_id']."'");
			redirect(FUSION_SELF.$aidlink."&amp;page=pointing&status=pa");
		} else {
			redirect(FUSION_SELF.$aidlink."&amp;page=pointing&status=e");
		}
	} else if (isset($_POST['addd']) && (isset($_GET['pts_id']) && $_GET['pts_id']=='new')) {
		if ($_GET['pts_id']) {
			$name = $_POST['pts_name'];
			$team = $_POST['pts_team'];
			$pts = $_POST['pts_points'];
			$result = dbquery("INSERT INTO ".DB_KLS_PUNKTACJA." (pts_name, pts_team, pts_points) VALUES ('$name', '$team', '$pts')");
			redirect(FUSION_SELF.$aidlink."&amp;page=pointing&status=da");
		} else {
			redirect(FUSION_SELF.$aidlink."&amp;page=pointing&status=e");
		}
	}
}
	
$numrows = dbcount("(pts_id)", DB_KLS_PUNKTACJA, "pts_hidden='0'");
$result = dbquery(
	"SELECT p.pts_id, p.pts_name, p.pts_team, p.pts_points, tu.user_id, tu.user_name, tu.user_status
	FROM ".DB_KLS_PUNKTACJA." p
	LEFT JOIN ".DB_USERS." tu ON p.pts_name=tu.user_id
	WHERE pts_hidden='0'
	ORDER BY p.pts_team, p.pts_points DESC"
);
?>
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
.kls_pts td:nth-child(3) {
width: 130px;
padding-left: 10px;
text-align: left;}
.kls_pts td:nth-child(4) {
wdith: 40px;}
.kls_pts span {
color: #fff;}
.kls_pts a {
color: #fff;}
.kls_pts input {
width: 50px;}
.kls_pts input.l {
width: 150px;}
</style>
<?php
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
		
		echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."&amp;page=pointing&amp;pts_id=".$data['pts_id']."'>\n";
		
		if($data['pts_team']=='')
		{
		echo "<td><input type='text' name='pts_name' maxlength='32' class='l' value='".$data['pts_name']."'></td>";
		} else {
		echo "<td><span>".$data['pts_team']."</span></td>";
		}
		
		echo "<td><span>".$data['pts_points']."</span></td>";
	
		
		echo "<td align='center'>
		<input type='text' name='pts_points' maxlength='3' value='".$data['pts_points']."'>
		<input type='submit' name='savepts' value='".$locale['KLS_set']."' class='button' /></td>\n";
		echo "</form>";
		
		echo "</tr>\n";
		$i++;
	}
		$lp = $lp + 1;
		echo "<tr>";
		echo "<td><span>".$lp."</span></td>";
		
		echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."&amp;page=pointing&amp;pts_id=new'>\n";
		
		echo "<td align='center'>
		<input type='text' name='pts_name' placeholder='ID profilu lub Imię i Nazwisko 'class='l' maxlength='32' value='".$data['pts_points']."'>
		</td>\n";
		
		echo "<td align='center'>
		<input type='text' name='pts_team' placeholder='Nazwa zespołu' class='l' maxlength='16' value='".$data['pts_points']."'>
		</td>";
		echo "<td align='center'>
		<input type='text' name='pts_points' maxlength='3' placeholder='0'>
		</td>\n";
		echo "<td>
		<input type='submit' name='addd' value='".$locale['KLS_addd']."' class='button l' /></td>\n";
		
		echo "</form>";
		echo "</tr>\n";
	
	echo "</table>\n";
} else {
	echo "<div>".$locale['KLS_empty']."</div>\n";
		$lp = 1;
		echo "<table class='kls_pts' align='center' cellpadding='3'>
				<tr>";
		echo "<td><span>".$lp."</span></td>";
		
		echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."&amp;page=pointing&amp;pts_id=new'>\n";
		
		echo "<td align='center'>
		<input type='text' name='pts_name' placeholder='ID profilu lub Imię i Nazwisko 'class='l' maxlength='32' value='".$data['pts_points']."'>
		</td>\n";
		
		echo "<td align='center'>
		<input type='text' name='pts_team' placeholder='Nazwa zespołu' class='l' maxlength='16' value='".$data['pts_points']."'>
		</td>";
		echo "<td align='center'>
		<input type='text' name='pts_points' maxlength='3' placeholder='0'>
		</td>\n";
		echo "<td>
		<input type='submit' name='addd' value='".$locale['KLS_addd']."' class='button l' /></td>\n";
		
		echo "</form>";
		echo "</tr>
		</table>";
}


}
require_once THEMES."templates/footer.php";
?>