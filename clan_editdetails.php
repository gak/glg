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
htmlHeading('Modify Clan Details');
echo htmlGetcontent("members_clan_edit");
htmlDivEnd();

htmlDiv();

$e = new TableEditor('clan', 'clan_editdetails.php');
$e->AddField('name', 'Name', 'lte,edit,inputtext');
$e->AddField('tag', 'Tag', 'lte,edit,inputtext');
$e->AddField('url', 'Web Site', 'edit,inputtext');
$e->AddField('other', 'Other Contact', 'edit,inputtext');
$e->AddField('available', 'Are you available for matches?', 'edit,inputcheckbox');
$e->AddField('recruiting', 'Are you recruiting?', 'edit,inputcheckbox');

if (isset($_GET['posted'])) {
	
	// just incase someone fakes the form
	$_POST['id'] = $clan->id;
	
	$e->ProcessEditRow();
	
} else {

	$e->ShowEditRow($clan->id);

}

htmlDivEnd();

htmlStop();

?>
