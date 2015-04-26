<?

// Copyright 2004, 2005 Gerald Kaszuba

/*

This file is part of Game Ladder Groper.

Game Ladder Groper is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

Game Ladder Groper is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Game Ladder Groper; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

function htmlStart($drawmenu = true) {

	header("Content-type: text/html; charset=utf-8");
	header("Cache-Control: no-cache");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

	<meta name="title" content="AUSource" />
	<meta name="copyright" content="Copyright Gerald Kaszuba 2004, 2005" />
	<meta name="author" content="Gerald Kaszuba" />

<title>AUSource Counter-Strike Source Clan Ladder</title>

	<style type="text/css" media="screen">
		@import url(style/ausource.php);
	</style>


</head>

<body>

<div id="container">

	<div id="header" title="AUSource Counter-Strike Source Clan Ladder"><span>AUSource CS:S Clan Ladder</span></div>
	<? if ($drawmenu) { ?>
	<div id="mainnav">
		<ul>
			<li><a href=".">Home</a></li>
			<li><a href="ladder.php">Standings</a></li>
			<li><a href="clans.php">Clans</a></li>
			<li><a href="results.php">Results</a></li>
			<li><a href="rules.php">Rules</a></li>
			<li><a href="clan_home.php">Clan Management</a></li>
			<li><a href="join.php">Register Clan</a></li>
			<li><a href="forum">Forums</a></li>
			<!--<li><a href="help.php">Help</a></li>-->
			
		</ul>		
	</div>
	<?}?>
	<div id="content">

<?

}

function htmlStop() {

	global $db;
	$db->query('update stats set hits = hits + 1');

?>

	</div>

	<div id="footer">&copy; Gerald Kaszuba 2004, 2005</div>

	<div id="theend"></div>

</div>

<br/>

</body>
</html>

<?

}

function htmlHeading($t,$s = "") {

	echo "<h1><strong>$t</strong></h1>";

	if ($s != "")
		echo '<h4><img src="images/h4bullet.png" alt="Bullet" />'.$s.'</h4>';

}

function htmlSubHeading($t) {

	echo '<h1><span class="darkred">'.$t.'</span></h1>';

}

function htmlNewsEntry($h, $d, $t, $n) {

	echo '<h3><strong>- '.$h.'</strong> @ '.myshortdate($d). '</h3>';
	echo '<h4>Posted by ' . $n .'</h4>';
	echo '<p>'.$t.'</p>';
	
}

function htmlDiv() {

	echo '<div class="blogpost">';

}

function htmlDivEnd() {

	echo '</div>';
	
}

function htmlGetContent($n) {

	global $db;
	$dat = $db->quickquery('select * from content where name = "'.$n.'"');

	if ($db->count() == 0) {

		$db->query('insert into content (name, content) values ("'.$n.'", "Empty Content!")');
		return 'Empty Content! Created New Row';

	}
	
	return $dat->content;	

}

function htmlClanDD($name = 'clan', $except = 0, $startblank = true) {

	global $db;
	$resEnemyClans = $db->query('select * from clan where id != '.$except.' order by name');

	$o = '';

	$o .= '<select name="'.$name.'" size="1">';
        
	if ($startblank)
		$o .= "<option value=\"\">";

        while (($dat = $db->fetchObject($resEnemyClans))) {

                if ($dat->tag != $dat->name)
                        $t = $dat->tag . " " . $dat->name;
                else
                        $t = $dat->tag;

                $o .= "<option value=\"{$dat->id}\">$t";

        }

      $o .= '</select>';

      return $o;

}

function htmlFormTableStart() {
    echo '<table width="100%" border="0">';
}

function htmlFormTableEnd() {
    echo '</table>';
}

function htmlFormHeading($h, $s = "") {
    echo '<tr><td style="padding-left: 0px;"><h1><span class="darkred">'.$h.'</span></h1>';
    if ($s!='')
        echo '<tr><td style="padding-left: 0px;"><h4>'.$s.'</h4>';
}

function htmlFormRow($t) {
    echo '<tr><td style="padding-left: 0px;">'.$t.'</td></tr>';
}

function clanDetails($dat, $morelink=false) {

	htmlHeading('Team <span class="darkred">' . $dat->name . '</span> Information');

	htmlDiv();
	echo '<p>';

		$t = new Table();
		$t->HeaderRow(array(

			array('v' => 'Clan Information', 'colspan' => 2)
			,array('v' => 'Logo / Image')

		));

		if (strlen($dat->image))
			$logo = '<img src="img.php?id='.$dat->id.'">';
		else
			$logo = '';


////

		$meh = array();
		$meh['Clan Tag'] = $dat->tag;
		$meh['Clan Representitive'] = DoName($dat, $dat->membername);
		
// 		$meh['Email'] = $dat->email;

		if ($dat->rank > 5) {
			$meh['Rank'] = $dat->rank;
			$meh['Rating'] = $dat->rating;
		}
		
//		$meh['Joined'] = (($dat->joined > 0)?mylongdate($dat->joined):'');
		
		if ($dat->lp > 0)
			$meh['Last Match'] = (($dat->lp > 0)?mylongdate($dat->lp):'');
			
		if (substr($dat->url, 0, 7) == 'http://') {
			$dat->url = '<a href="'.$dat->url.'">'.$dat->url.'</a>';
		} else {
			$dat->url = '';
		}
			
		if ($dat->url != "")
			$meh['Web Site'] = $dat->url;

		if ($dat->other != "")
			$meh['Other Contact'] = $dat->other;

//


		$t->Row(array(
		
			array('v' => 'Clan Name', 'width' => 150)
			,array('v' => $dat->name)
			,array('v' => $logo, 'rowspan' => sizeof($meh)+1, 'align' => 'center')
		
		));

		
		foreach($meh as $k=>$m) {
		
			$t->Row(array(array('v' => $k, 'width' => "40", 'height' => 10), array('v' => $m, 'width' => "250")));
			
		}

		$t->output();
		
		if ($morelink) {
			echo "<a href=\"clandetails.php?id={$dat->id}\">More Details...</a>";
		}


	echo '</p>';
	htmlDivEnd();
	
}

?>
