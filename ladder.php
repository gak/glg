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

include('include.php');

htmlStart();
htmlHeading('Standings');

htmlDiv();
	echo htmlGetContent('standings');
htmlDivEnd();

htmlDiv();

htmlSubHeading('Active Clans');
echo '<br>';
$res = $db->query('select * from clan where games >= 5 order by rating desc');
htmlDrawLadder($res);

htmlSubHeading('New Clans');
echo '<br>';
$res = $db->query('select * from clan where games < 5 order by rating desc');
htmlDrawLadder($res);

function htmlDrawLadder($res) {

	global $db;

	$thl = array('#','R','','Name','W','L','D','M','S','LPD','BR','Status','Ava');
	$thw = array(5,5,1,30,5,5,5,5,5,5,5,10,5);
	$thsL = '<td align="center" width="';
	$thsR = '%"><strong><span class="white">';
	$the = '</span></strong>';

	$tdc1 = 'F1F1F1';
	$tdc2 = 'DDDFE3';

	echo '<table>';

	echo '<tr bgcolor="#5E839B">';

	$i = 0;
	foreach($thl as $t) {

		echo $thsL.$thw[$i].$thsR.$t.$the;
		$i++;

	}

	$rup = '<img src="images/ladder_up.png" alt="Moved Up" title="Moved Up" />';
	$rdn = '<img src="images/ladder_down.png" alt="Moved Down" title="Moved Down" />';
	$rhot = '<img src="images/ladder_hot.png" alt="Good Winning Streak" title="Good Winning Streak" />';
	$rcold = '<img src="images/ladder_cold.png" alt="Bad Losing Streak" title="Bad Losing Streak" />';
	$newclan = '<img src="images/ladder_new.png" alt="New Clan" title="New Clan" />';
	$avail = '<img src="images/ladder_tick.png" alt="Available for clan wars" title="Available for clan wars" />';
	$notavail = '<img src="images/ladder_cross.png" alt="Not Available for clan wars" title="Not Available for clan wars" />';
	$rec = '<img src="images/ladder_recruiting.png" alt="Recruiting" title="Recruiting" />';

	$tdc = $tdc2;
	while (($dat = $db->fetchObject($res))) {
	
		$isnew = ($dat->games < 5);

		if ($tdc == $tdc2)
			$tdc = $tdc1;
		else
			$tdc = $tdc2;

		echo '<tr bgcolor="#' . $tdc . '">';

		if ($isnew)
			echo '<td class="ladderl"><strong>-</strong>';
		else
			echo '<td class="ladderl"><strong>'.$dat->rank.'</strong>';
			
		echo '<td class="ladderr">'.floor($dat->rating);
		

		if (strlen($dat->imagesmall) == 0) {
			echo '<td style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px;"><img src="images/clear.gif" width=16 height=16>';
		} else {
			echo '<td style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px;"><img src="img.php?id='.$dat->id.'&sml=1" width=16 height=16>';
		}
		echo '<td class="ladderl">';
		echo '<a title="'.$dat->name.'" href="clandetails.php?id='.$dat->id.'">'.$dat->tag.'</a>';
		echo '<td class="ladderr">'.$dat->win;
			
		echo '<td class="ladderr">'.$dat->loss;
		echo '<td class="ladderr">'.$dat->draw;
		echo '<td class="ladderr">'.$dat->games;
		echo '<td class="ladderr">';
		if ($dat->streak > 0)
			echo '+' . $dat->streak;
		else if ($dat->streak < 0)
			echo $dat->streak;
		else
			echo '-';

		echo '<td class="ladderr">';
		if ($dat->lp) {

			$daysago = floor(($dat->lp - now()) / 60 / 60 / 24);
			echo $daysago;
			
		} else
			echo '-';
		
		echo '<td class="ladderr">'.$dat->bestrank;
		
		echo '<td>';

			if ($isnew) {
				echo $newclan;
			}

			if ($dat->streak >= 3) {
				echo $rhot;
			}

			if ($dat->streak <= -3) {
				echo $rcold;
			}

		echo '<td class="ladderr">';
		if ($dat->available)
			echo $avail;
		else
			echo $notavail;

	}

	echo '</table>';

}

htmlDivEnd();

htmlDiv();

?>
<!--
<table width="100%" border="0">
  <tr> 
    <td align='center' bgcolor='#E8E8E8'>There are currently <strong>3</strong> active clans and <strong>15</strong> inactive clans</td>
  </tr>
</table>
-->

<? htmlDivEnd(); ?>

<? htmlDiv(); ?>

<h1><strong>Ladder Legend</strong></h1>
<p>The above columns mean:</p>

<table width="100%" border="0">
  <tr>
    <td width='100'><strong>#</strong></td>
    <td>Current Rank</td>
  </tr>
  <tr>
    <td><strong>R</strong></td>
    <td>Current Rating</td>
  </tr>
  <tr>
    <td><strong>Name</strong></td>
    <td>Clan Tag - Move your mouse over the Clan Tag to get the Full Clan Name</td>
  </tr>
  <tr>
    <td><strong>W</strong></td>
    <td>Total Wins</td>
  </tr>
  <tr>
    <td><strong>L</strong></td>
    <td>Total Loses</td>
  </tr>
  <tr>
    <td><strong>D</strong></td>
    <td>Total Draws</td>
  </tr>
  <tr>
    <td><strong>M</strong></td>
    <td>Total Matches</td>
  </tr>
  <tr>
    <td><strong>S</strong></td>
    <td>Current Winning or Losing Streak</td>
  </tr>
  <tr>
    <td><strong>LPD</strong></td>
    <td>Last Played Days - How many days ago the clan played</td>
  </tr>
  <tr>
    <td><strong>BR</strong></td>
    <td>Best Rating</td>
  </tr>
  <tr>
    <td><strong>Status</strong></td>
    <td>Clan's Status</td>
  </tr>
  <tr>
    <td><strong>Ava</strong></td>
    <td>Available for a clan match</td>
  </tr>
</table>

<h1><strong>Icon Legend</strong></h1>
<p>The above icons mean:</p>

<table width="100%" border="0">
  <tr>
    <td width='100'><img src="images/ladder_recruiting.png" alt="Recruiting" title="Recruiting" /></td>
    <td>Clan is currently Recruiting</td>
  </tr>
  <tr>
    <td><img src="images/ladder_up.png" alt="Moved Up" title="Moved Up" /></td>
    <td>Clan has moved up the ladder</td>
  </tr>
  <tr>
    <td><img src="images/ladder_down.png" alt="Moved Down" title="Moved Down" /></td>
    <td>Clan has moved down the ladder</td>
  </tr>
  <tr>
    <td><img src="images/ladder_hot.png" alt="3+ Winning Streak" title="3+ Winning Streak" /></td>
    <td>Clan has won 3 or more matches in a row</td>
  </tr>
  <tr>
    <td><img src="images/ladder_cold.png" alt="-3 Winning Streak" title="-3 Winning Streak" /></td>
    <td>Clan has lost 3 or more matches in a row</td>
  </tr>
  <tr>
    <td><img src="images/ladder_new.png"/></td>
    <td>Clan rank is not counted because they have played less then 5 matches</td>
  </tr>
</table>

<?

htmlDivEnd();

htmlStop();

?>
