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

require_once("include.php");

$id = $_GET['id'];

$dat = $db->quickquery('

	select clan.*, member.name as membername, member.acssrid
	from clan
	left join member on member.clanid = clan.id and member.isrep = 1
	where clan.id = \''.$id.'\'');

htmlStart();

clanDetails($dat);


// The code below used to communicate with the ACSSR ladder. This needs to be modified to work only with one DB.

/*

   htmlHeading('Members');
	htmlDiv();
	echo '<p>';

	echo '
	
<table width="100%" border="0">

  <tr align="center" bgcolor="#5E839B">
    <td style="background-color: white;"><span class="white"><strong></strong></span></td>
    <td colspan="6"><span class="white"><strong>ACSSR Stats</strong></span></td>
  </tr>
  <tr align="center" bgcolor="#5E839B">
    <td><span class="white"><strong>Name</strong></span></td>
    <td><span class="white"><strong>#</strong></span></td>
    <td><span class="white"><strong>Score</strong></span></td>
    <td><span class="white"><strong>P/M</strong></span></td>
    <td><span class="white"><strong>Points</strong></span></td>
    <td><span class="white"><strong>Time</strong></span></td>
    <td><span class="white"><strong>Server/Seen</strong></span></td>
  </tr>

	';


	$t = DB_DATABASE2;
	$res = $db->query("
	
	select

	member.*, player.id as playerid,
	player.rank, player.score, player.totaltime, player.totalfrags, player.ppm,
	player.curserverid, player.lastserverwhen, server.name as servername

	from member
	left join $t.player on player.ename = member.name
	left join $t.server on server.id = player.curserverid
	where member.clanid = '{$id}'
	order by rank
	
	");
	
	while ($dat = $db->fetchObject($res)) {

		if ($dat->isrep) {
			
			echo '<tr bgcolor="#F1F1F1"><td>';
				
		} else {
		
			echo '<tr><td>';
		
		}
		
		if ($dat->playerid > 0)
				echo '<a href="http://acssr.slowchop.com/playerdetails.php?id='.$dat->playerid.'">';
				
		echo $dat->name;
		
		if ($dat->playerid) {
			echo '</a>';
			echo '<td>' . $dat->rank;
			echo '<td>' . $dat->score;
			echo '<td>' . $dat->ppm;
			echo '<td>' . $dat->totalfrags;
			echo '<td>' . humanTime($dat->totaltime, true);
			echo '<td>';


			if ($dat->curserverid > 0)
				echo "<a href=\"http://acssr.slowchop.com/ladder.php?online={$dat->curserverid}\">$dat->servername</a>";
			else
				echo humanTime(time() - $dat->lastserverwhen, true) . " ago";

		} else {
		
			echo '<td colspan=6 align=center>';
		
		}
	
	}
	
	echo '</table>';
	
	echo '</p>';
	htmlDivEnd();

*/

    htmlHeading('Recent Matches');
	htmlDiv();
	echo '<p>';

	$res = resultsQuery(array('clanid'=>$id));
	if ($db->count() > 0) {

		smallMatchTableStart();
		smallMatchTableRows($res);	
		smallMatchTableEnd();

	} else {

		echo "No matches played";

	}
	
	echo '</p>';
	htmlDivEnd();
	

htmlStop();

?>
