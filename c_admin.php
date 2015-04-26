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

function checkAdmin() {

	global $_SESSION;
	global $admin;

        if (isset($_SESSION["admin"])) {

                $admin = $_SESSION["admin"];
		$admin->refresh();

        } else {

		htmlStart();

		htmlHeading("AUSource Administration Access", "If you can read this you probably don't belong here!");

		htmlDiv();

                ?>
                <form action="admin_login.php" method="post">
                Username: <input type="text" name="user">Password: <input type="password" name="pass">
                <input type="submit" value="doit">
                </form>
                <?

		htmlDivEnd();

		htmlStop();

                die();

        }

}

function htmlStartAdmin() {

	global $admin;

	htmlStart();
	htmlHeading("AUSource Administration", "You are logged in as " . $admin->name );

	htmlDiv();

	$links = array(

		array('Admin Home', 'admin.php')
		,array('Post News', 'admin_postnews.php')
		,array('Content', 'admin_content.php')
		,array('Clans', 'admin_clans.php')
		,array('Logout', 'admin_logout.php')

	);

	foreach($links as $link) {
	
		echo '<a href="'.$link[1].'">'.$link[0].'</a> | ';

	}
	
?>


<?
	htmlDivEnd();

}

class Admin {

	function Admin() {}

        function loadFromName($user) {

                global $db;

                $user = addSlashes($user);
                $dat = $db->quickquery("select * from admin where name='$user'");
                $this->loadFromData($dat);

        }

        function loadFromID($user) {

                global $db;
                $dat = $db->quickquery("select * from admin where id='$user'");
                $this->loadFromData($dat);

        }

        function loadFromData($dat) {

                $this->name = $dat->name;
                $this->pass = $dat->pass;
		$this->access = $dat->access;
                $this->id = $dat->id;

        }

        function login($u, $p) {

                $this->loadFromName($u);

                if (!isset($this->name))
                        return 1;

                if (md5($p) != $this->pass)
                        return 3;

                return 0;

        }

	function refresh() {

		$this->loadFromID($this->id);
	
	}

}

?>
