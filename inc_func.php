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

function humanTime($seconds, $long = false) {

	if ($seconds < 60 * 2) {
	
		if ($long)
			return $seconds . " seconds";
		
		return $seconds . "s";
	
	} else if ($seconds < 60 * 60 * 2) {
	
		if ($long)
			return floor($seconds / 60) . " minutes";

		return floor($seconds / 60) . "m";
	
	} else if ($seconds < 60 * 60 * 24 * 2) {
	
		if ($long)
			return floor($seconds / 60 / 60) . " hours";
			
		return floor($seconds / 60 / 60) . "h";
	
	} else {
	
		if ($long)
			return floor($seconds / 60 / 60 / 24) . " days";

		return floor($seconds / 60 / 60 / 24) . "d";
	
	}

}

// midnight today ... this is GMT+10!
function now() {

	return time() + TIMEOFFSET;

}

function mylongdate($ts) {

	return date("l dS F Y h:i:sA", $ts);

}

function myshortdate($ts) {

	return date("j/n/y", $ts);

}

function mydate($format) {

	return date($format, now());

}

function today() {

	return mktime(0, 0, 0, mydate("m"), mydate("d"), mydate("Y"));

}

function getday($i) {

	return mktime(0, 0, 0, mydate("m"), mydate("d") + $i, mydate("Y"));

}

function gethour() {

	return mktime(mydate("G"), 0, 0, mydate("m"), mydate("d"), mydate("Y"));

}

function utime() {

	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec); 

}

// needs
// Ra: Clan1's rating
// Rb: Clan2's rating
// result1: Clan1's Score - (maybe 1 for win, 0.5 for draw and 0 for loss)
// result2: Clan2's Score
// returns
// NR = new rank
// AR = added rank
function workOutNewRating($Ra, $Rb, $result1, $result2) {

	global $db;

	$D = 400;
	$K = 100;

	$Ea = 1 / (1 + pow(10, ($Rb-$Ra) / $D ) );
	$Eb = 1 / (1 + pow(10, ($Ra-$Rb) / $D ) );

	$ARa = $K * ($result1 - $Ea);
	$ARb = $K * ($result2 - $Eb);

	$NRa = $Ra + $ARa;
	$NRb = $Rb + $ARb;

	unset($ret);
	$ret->NRa = $NRa;
	$ret->NRb = $NRb;
	$ret->ARa = $ARa;
	$ret->ARb = $ARb;
	$ret->Ea = $Ea;

	return $ret;

}

function getMapList() {

	global $db;

	$db->query("select * from maps order by name");

	$maps = array();
	while ($dat = $db->fetchObject()) {

		$maps[] = $dat->name;

	}

	return $maps;

}

function resultsQuery($s) {

    global $db;

    $sqlwhere = '';
	$sqllimit = '';

    if (isset($s['clanid'])) {

        $sqlwhere .= " AND (a.id = {$s['clanid']} OR b.id = {$s['clanid']})";

    }

	if (isset($s['limit'])) {

		$sqllimit = 'LIMIT ' . $s['limit'];

	} else {

		$sqllimit = 'LIMIT 10';

	}

    $sql = "

        select

            result, ts,
            a.id as id1, a.tag as tag1,
            b.id as id2, b.tag as tag2

        from result
        inner join clan a on a.id = result.clan1
        inner join clan b on b.id = result.clan2

        where 1 = 1

        $sqlwhere

		$sqllimit

    ";

    return $db->query($sql);

}

function smallMatchTableStart() {
?>
<table width="100%" border="0">
  <tr align="center" bgcolor="#5E839B">
    <td><span class="white"><strong>Match</strong></span></td>
    <td><span class="white"><strong>Winner</strong></span></td>
    <td><span class="white"><strong>Date</strong></span></td>
<!--    <td><span class="white"><strong>Details</strong></span></td>-->
  </tr>
<?

}

function smallMatchTableRows($res) {

    global $db;
    while ($dat = $db->fetchObject($res)) {

        echo '<tr>';
        echo '<td><a href="clandetails.php?id='.$dat->id2.'">'.$dat->tag2.'</a> vs ';
        echo '<a href="clandetails.php?id='.$dat->id1.'">'.$dat->tag1.'</a>';

        if ($dat->result == 0) // clan1 lost
            echo '<td align="center"><a href="clandetails.php?id='.$dat->id2.'">'.$dat->tag2.'</a>';
        else
            echo '<td align="center">Draw';

        echo '<td align="center">'.myshortdate($dat->ts);

    }

}

function smallMatchTableEnd() {
    echo '</table>';
}

function DoName($dat, $name) {

    global $dbACSSR;
	global $db;

//	$acssrid = $dat->acssrid;

	$acssrid = 0;

	if (!($acssrid > 0)) {

	    $datacssr = $dbACSSR->quickquery("select id from ".DB_DATABASE2.".player where ename = '$name'");

    	if ($dbACSSR->count()) {

			$db->query("update member set acssrid = {$datacssr->id} where id = {$dat->id}");
			return "<a href=\"http://acssr.slowchop.com/playerdetails.php?id={$datacssr->id}\">$name</a>";

		}

	}

	if ($acssrid != "")
	    return "<a href=\"http://acssr.slowchop.com/playerdetails.php?id={$acssrid}\">$name</a>";
	else
		return $name;

}

?>
