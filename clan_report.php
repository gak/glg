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

include ("include.php");

checkClan();

htmlStartClan();

htmlHeading('Report a Match');

if (isset($_POST) && isset($_POST['clanid'])) {

	$cid1 = $clan->id;
	$cid2 = $_POST['clanid'];
	$r = $_POST['result'];
	$when = strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day']) + TIMEOFFSET;

	if ($when > now() + 60*60*24) {
		$err[] = 'Your reported date is in the future! Impossible!';
	}

	if ($when < now() - 60*60*24 * 30) {
		$err[] = 'The match date is more then a month old.';
	}

	if ($r == '') {

		$err[] = "Please select if you lost or drawn.";

	}

	$sql = array();

	if (!isset($err)) {

		$c1 = $db->quickquery("select * from clan where id = '$cid1'");	// reporting clan. loss or draw.
		$c2 = $db->quickquery("select * from clan where id = '$cid2'"); // opponent. win or draw.

		// clan 1 lost
		if ($r == 0) {

			$result = workOutNewRating($c1->rating, $c2->rating, 0, 1);
	
			// update c1's values
			if ($c1->streak > 0)
				$c1->streak = -1;
			else
				$c1->streak--;

			if ($c1->streak > $c1->beststreak)
				$c1->beststreak = $c1->streak;

			if ($c1->streak < $c1->worststreak)
				$c1->worststreak = $c1->streak;
				
			$c1->loss++;
			$c1->games++;
		
			// update c2's values
			if ($c2->streak < 0)
				$c2->streak = 1;
			else
				$c2->streak++;

			if ($c2->streak > $c2->beststreak)
				$c2->beststreak = $c2->streak;

			if ($c2->streak < $c2->worststreak)
				$c2->worststreak = $c2->streak;
				
				
			$c2->win++;
			$c2->games++;
			
			$sql[] = "update clan set rating = '{$result->NRa}', win = {$c1->win}, loss = {$c1->loss}, games = {$c1->games}, streak = {$c1->streak}, beststreak = {$c1->beststreak}, worststreak = {$c1->worststreak}, lp = $when where id = '$cid1'";
			$sql[] = "update clan set rating = '{$result->NRb}', win = {$c2->win}, loss = {$c2->loss}, games = {$c2->games}, streak = {$c2->streak}, beststreak = {$c2->beststreak}, worststreak = {$c2->worststreak}, lp = $when where id = '$cid2'";
	
		} else {
		
			$result = workOutNewRating($c1->rating, $c2->rating, 0.5, 0.5);

			$c1->streak = 0;
			$c1->draw++;
			$c1->games++;

			$c2->streak = 0;
			$c2->draw++;
			$c2->games++;
			
			$sql[] = "update clan set rating = '{$result->NRa}', draw = {$c1->draw}, games = {$c1->games}, streak = {$c1->streak}, lp = $when where id = '$cid1'";
			$sql[] = "update clan set rating = '{$result->NRb}', draw = {$c2->draw}, games = {$c2->games}, streak = {$c2->streak}, lp = $when where id = '$cid2'";
			
		}

		// now add the result rows
		$sql[] = "
				insert into result
				(result, clan1, clan2, clan1ratingbefore, clan1ratingafter, clan2ratingbefore, clan2ratingafter, ts, comment)
				VALUES
				($r, $cid1, $cid2, {$c1->rating}, {$result->NRa}, {$c2->rating}, {$result->NRb}, $when, '{$_POST['comment']}' )
		
		";


		$db->query("begin");

		foreach($sql as $s) {
		
			echo "<!-- 1 $s -->\n";
			$db->query($s);

		}

		unset($sql);
		
		// loop though the maps and see if they're ticket and filled outa
		// this needs to run after "insert into result" because we need the result id
		$sql = array();
		
		$resultid = mysql_insert_id();
	
		$maps = getMapList();
		foreach ($maps as $mapid=>$map) {
	
			$them = $_POST["map_{$map}_them"];
			$us = $_POST["map_{$map}_us"];

			if ($them != "" && $us != "" && $them >= 0 && $us >= 0) {

				$sql[] = "
				
					insert into resultmaps
					(resultid, mapid, clan1score, clan2score)
					VALUES
					('$resultid', '$mapid', '$us', '$them')
				
				";

			}

		}
		
		foreach($sql as $s) {
		
			echo "<!-- 2 $s -->\n";
			$db->query($s);

		}
		
		$db->query("commit");

		htmlDiv();
		echo htmlGetcontent("members_report_done");
		htmlDivEnd();

		htmlStop();
		die();

	}

} 

htmlDiv();
echo htmlGetcontent("members_report");
if (isset($err)) {

	echo '<p class="error">';
	foreach($err as $e) {

		echo $e;
		echo '<br>';

	}
	echo '</p>';

}
htmlDivEnd();

htmlDiv();

?>

<form name="reportaloss" method="post" action="clan_report.php">

<?
htmlFormTableStart();

// Clan Played      
htmlFormHeading('Clan Played', 'Select the name of the clan which defeated or drawed you from the list below');
htmlFormRow(htmlClanDD('clanid', $clan->id));

// Win or Draw?
htmlFormHeading('Result', 'Please select either a loss or a draw. If you did draw with your opponent, only one clan should report it.');

htmlFormRow('
<select name="result">
<option value="">
<option value="0">Loss
<option value="1">Draw
</select>
');


// date of match
htmlFormHeading('Date of Match', 'Fill out the date of the match. Please use DD/MM/YYYY format');
htmlFormRow('
<input name="day" type="text" value="'.date('j',now()).'" size="2"> /
<input name="month" type="text" value="'.date('m',now()).'" size="2"> / <input name="year" type="text" value="2005" size="4">
');

$maplist = array('de_dust', 'de_dust2', 'de_cbble', 'de_prodigy', 'cs_italy');
htmlFormHeading('Map Scores', 'Fill out the scores of each map that you played. Please leave the unplayed map scores blank. If the map isn\'t in the list please add it to the comments.');

$t = '<table><tr><td><strong>Map</strong><td><strong>Your Score</strong><td><strong>Their Score</strong>';

$maps = getMapList();
foreach ($maps as $map) { 

	$t .= "<tr><td>{$map}<td><input type=\"text\" size=\"5\" name=\"map_{$map}_us\"><td><input type=\"text\" size=\"5\" name=\"map_{$map}_them\">";
}

$t.='</table>';

htmlFormRow($t);

htmlFormHeading('Closing Comments');
htmlFormRow('<textarea name="comment" cols="50" wrap="VIRTUAL"></textarea>');

htmlFormRow('<input type="submit" value="Report Match">');

htmlFormTableEnd();

echo '</form>';

htmlDivEnd();

htmlStop();

?>
