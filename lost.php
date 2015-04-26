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

htmlStart();

htmlHeading('Lost Password');

if (isset($_GET['post'])) {

		$p = "";
		for ($i = 0; $i < 8; $i++) {


		}
		
		$e =
"Hello,

A new password has been generated for you. The password is:

$newpassword

You can log in with the new password here:

http://ausource.slowchop.com/clan_home.php

Make sure you change your password to something you can remember.

AUSource Team";

	mail($email, "AUSource Password Reset", $e, "From: AUSource <dontreply@slowchop.com>\r\n");

	htmlDiv();
	echo htmlGetContent('lost_sent');
	htmlDivEnd();

	htmlStop();

	die();

}

htmlDiv();

echo htmlGetContent('lost');

htmlDivEnd();

htmlDiv();

?>
<form method="post" action="lost.php">
<?

htmlFormTableStart();
htmlFormHeading('Your Clan', 'Select your clan from the list and a new password will be sent to you.');

htmlFormRow(htmlClanDD('id'));
htmlFormRow('<input type="submit" value="New Password">');

htmlFormTableEnd();

?>
</form>
<?

htmlDivEnd();

htmlStop();
  
?>
