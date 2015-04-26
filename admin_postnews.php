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

checkAdmin();

htmlStartAdmin();

htmlSubHeading("News Post");

htmlDiv();

if (isset($_POST["subject"])) {

	$db->query("insert into news (subject, body, `when`, who) values ('{$_POST["subject"]}', '{$_POST["body"]}', UNIX_TIMESTAMP(), {$admin->id})");

	echo "<p>Done.</p>";

} else {

?>
<br>
<form method="post" action="admin_postnews.php">

Subject:<br>
<input type="text" size=30 name="subject"><br>

Body:<br>
<textarea rows=5 cols=40 name="body"></textarea><br>

<input type="submit" value="submit news">

</form>

<?

}

htmlDivEnd();

htmlStop();

?>
