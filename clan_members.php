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

htmlHeading('Members');

$rep = $db->quickquery("select * from member where clanid = '{$clan->id}' and isrep = 1");

if (isset($_POST) && isset($_POST['rep'])) {

	$db->query("update member set name = '{$_POST['rep']}' where id = '{$rep->id}'");
	$rep->name = $_POST['rep'];
	
	// existing members
//	print_r($_POST);
	foreach($_POST as $key=>$memname) {
	
		if (substr($key, 0, 3) != 'mem')
			continue;
			
//		echo $key . "<br>";
			
		$memid = substr($key, 3);
		
//		echo $memid . "<br>";
		
		if (trim($memname) == "") {
		
	
			$db->query("delete from member where id = '$memid' and clanid = '{$clan->id}' and isrep = 0");
		
		} else {

			$db->query("select id from member where clanid = {$clan->id} and name = '$memname'");
			if ($db->count())
				continue;

			$db->query("update member set name = '$memname' where id = '$memid' and clanid = '{$clan->id}' and isrep = 0");
			
		}
	
	}
	
	// new members
	foreach($_POST['newmem'] as $mem) {
	
		if (trim($mem) == "")
			continue;
			
		$db->query("select id from member where clanid = {$clan->id} and name = '$mem'");
		if ($db->count())
			continue;
			
		$q = "insert into member (clanid, name) VALUES ({$clan->id}, '{$mem}')";
		$db->query($q);

	}

} 

$res = $db->query("select * from member where clanid = '{$clan->id}' and isrep = 0");

htmlDiv();
echo htmlGetcontent("members_members");
htmlDivEnd();

htmlDiv();

?>

<form method="post" action="clan_members.php">

<?
htmlFormTableStart();

htmlFormHeading('Clan Representitive', 'Please enter your clan representitive name as it appears in-game');
htmlFormRow('<input type="text" size="30" name="rep" value="'.$rep->name.'">');

htmlFormHeading('Members', 'Please enter the rest of the members as it appears in-game. You may add multiple names at once.');

while ($dat = $db->fetchObject($res)) {

	htmlFormRow('<input type="text" size="30" name="mem'.$dat->id.'" value="'.$dat->name.'">');

}

for ($i = 0; $i < 4; $i++)
	htmlFormRow('<input type="text" size="30" name="newmem[]">');

htmlFormRow('<input type="submit" value="Update">');

htmlFormTableEnd();

echo '</form>';

htmlDivEnd();

htmlStop();

?>
