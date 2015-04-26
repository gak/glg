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

function makeActCode($id, $name) {
	
	return dechex(crc32($id . $name));

}

function checkClan($err = "") {

	global $_SESSION;
	global $clan;

	if (isset($_SESSION["clan"])) {

		$clan = $_SESSION["clan"];
		$clan->refresh();

	} else {

		htmlStart();

		htmlHeading("Clan Management");

		htmlDiv();

		echo htmlGetContent("clan_login");
		
		if ($err != "")
		echo "<p>$err</p>";

?>

<form method="post" action="clan_login.php">

  <table width="100%" border="0">
	<tr> 
	  <td width="80"><span class="darkred">Email</span></td>
	  <td><input name="user" type="text" id="name"></td>
	</tr>
	<tr> 
	  <td width="80"><span class="darkred">Password</span></td>
	  <td><input name="pass" type="password" id="password"></td>
	</tr>
	<tr> 
	  <td rowspan="2">&nbsp;</td>
	  <td><input type="submit" name="Submit" value="Log In"></td>
	</tr>
	<tr> 
	  <td>Clan not registered? Click <a href="join.php">here</a></td>
	</tr>
  </table>

</form>

<?

		htmlDivEnd();

		htmlStop();

		die();

	}

}

function htmlStartClan() {

	global $clan;

	htmlStart();
	htmlHeading("Clan Management", "You are logged in as " . $clan->name );

	htmlDiv();

	$links = array(

		array('Home', 'clan_home.php')
		,array('Report a Loss/Draw', 'clan_report.php')
		,array('Hypothetical', 'clan_pretend.php')
		,array('Details', 'clan_editdetails.php')
		,array('Members', 'clan_members.php')
		,array('Logo', 'clan_logo.php')
		,array('Log Out', 'clan_logout.php')

	);

	foreach($links as $link) {
	
		echo '<a href="'.$link[1].'">'.$link[0].'</a> | ';

	}
	
	htmlDivEnd();

}

class Clan {

	function Clan() {}

        function loadFromEmail($user) {

                global $db;

                $user = addSlashes($user);
                $dat = $db->quickquery("select * from clan where email='$user'");
                $this->loadFromData($dat);

        }

        function loadFromID($user) {

                global $db;
                $dat = $db->quickquery("select * from clan where id='$user'");
                $this->loadFromData($dat);

        }

        function loadFromData($dat) {

                $this->name = $dat->name;
                $this->email = $dat->email;
                $this->pass = $dat->pass;
                $this->id = $dat->id;
                $this->val = $dat->val;

        }

        function login($u, $p) {

                $this->loadFromEmail($u);

                if (!isset($this->name))
                        return 1;

                if (md5($p) != $this->pass)
                        return 3;

				if ($this->val == 0)
					return 4;

                return 0;

        }

	function refresh() {

		if (isset($this->id))
			$this->loadFromID($this->id);
		else {

			global $_SESSION;
			unset($_SESSION["clan"]);
			Header("Location: clan_home.php");

		}
			
	
	}

}

?>
