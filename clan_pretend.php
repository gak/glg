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

htmlDiv();
echo htmlGetcontent("members_pretend");
htmlDivEnd();

htmlDiv();
echo '<form action="clan_pretend.php" method="get">';
echo htmlClanDD('id',$clan->id);
echo '<input type="submit" value="go">';
echo '</form>';
htmlDivEnd();

if (isset($_GET) && isset($_GET['id'])) {

	$id = $_GET['id'];

	$c1 = $db->quickquery("select * from clan where id = '{$clan->id}'");
	$c2 = $db->quickquery("select * from clan where id = '$id'");

	$rWin = workOutNewRating($c1->rating, $c2->rating, 1, 0);
	$rLoss = workOutNewRating($c1->rating, $c2->rating, 0, 1);
	$rDraw = workOutNewRating($c1->rating, $c2->rating, 0.5, 0.5);

	htmlDiv();
	
	htmlSubHeading('Predicted results against ' . $c2->name);

	echo '<p>Based on both your ratings, the probable chance of winning is <b>'. floor($rWin->Ea*100) .'%</b>.</p>';

	htmlSubHeading('If you <b>win</b> against ' . $c2->name);
	echo '<p>';
	echo 'You will take <b>' . floor($rWin->ARa) . '</b> rating points and end up with a rating of <b>' . floor($rWin->NRa) . '</b>.<br>';
	echo '</p>';
	
	htmlSubHeading('If you <b>lose</b> against ' . $c2->name);
	echo '<p>';
	echo 'You will lose <b>' . abs(floor($rLoss->ARa)) . '</b> rating points and end up with a rating of <b>' . floor($rLoss->NRa) . '</b>.<br>';
	echo '</p>';

	htmlSubHeading('If you <b>draw</b> with ' . $c2->name);
	echo '<p>';
	echo 'You will '.(($rDraw->ARa >= 0)?'take':'lose').' <b>' . abs(floor($rDraw->ARa)) . '</b> rating points and end up with a rating of <b>' . floor($rDraw->NRa) . '</b>.<br>';
	echo '</p>';
	
	htmlDivEnd();

}

htmlStop();

?>
