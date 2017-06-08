<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: klasyfikacja.php
| Author: Szymon Kargol (Shymi)
+--------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/header.php";

include_once INFUSIONS."kls_special/infusion_db.php";
include_once INCLUDES."infusions_include.php";

include INFUSIONS."kls_special/locale/English.php";
$inf_settings = get_settings("kls_special");
opentable($locale['KLS_pdrivers']);
$numrows = dbcount("(pts_id)", DB_KLS_PUNKTACJA, "pts_hidden='0'");
$result = dbquery(
	"SELECT p.pts_id, p.pts_name, p.pts_team, p.pts_points, tu.user_id, tu.user_name, tu.user_status
	FROM ".DB_KLS_PUNKTACJA." p
	LEFT JOIN ".DB_USERS." tu ON p.pts_name=tu.user_id
	WHERE pts_hidden='0'
	AND pts_team=''
	ORDER BY p.pts_points DESC"
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
	echo "</table>\n";
} else {
	echo "<div>".$locale['KLS_empty']."</div>\n";
}
closetable();
opentable($locale['KLS_pteams']);
$numrows = dbcount("(pts_id)", DB_KLS_PUNKTACJA, "pts_hidden='0'");
$result = dbquery(
	"SELECT pts_team, sum(pts_points) AS pts_points
	FROM ".DB_KLS_PUNKTACJA."
	WHERE pts_hidden='0'
	AND pts_name=''
	GROUP BY pts_team
	ORDER BY pts_points DESC"
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
		//echo "<td><span>".$data['pts_team']."</span></td>";
		echo "<td><span>".$data['pts_points']."</span></td>";
		echo "</tr>\n";
		$i++;
		//if ($i != $numrows) { echo "<br />\n"; }
	}
	echo "</table>\n";
} else {
	echo "<div>".$locale['KLS_empty']."</div>\n";
}
closetable();
opentable($locale['KLS_info']);
	echo "<p><b>Punkty w wyścigu są przyznawane według następującego klucza:</b><br>
<br>
- 1 miejsce - 18 punktów<br>
- 2 miejsce - 15 punktów<br>
- 3 miejsce - 12 punktów<br>
- 4 miejsce - 10 punktów<br>
- 5 miejsce - 8 punktów<br>
- 6 miejsce - 6 punktów<br>
- 7 miejsce - 4 punkty<br>
- 8 miejsce - 3 punkty<br>
- 9 miejsce - 2 punkty<br>
- 10 miejsce - 1 punkt<br></p>";
	echo "<p class='author'>System punktacji by Shymi</p>";
closetable();
require_once THEMES."templates/footer.php";
?>