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

	error_reporting(E_ALL);

	if (!isset($dontob))
		ob_start();

	require("inc_error.php");

	if (!isset($_GET['d']))
		set_error_handler("errorhandler");

	define('TIMEOFFSET', 36000);
	
	require("inc_func.php");
	require("inc_html.php");
	require("inc_session.php");
	require("conf_db.php");
	require("c_database.php");
	require("c_generatesql.php");
	require("c_table.php");
	require("c_tableeditor.php");
	require("c_admin.php");
	require("c_clan.php");

	$db = new Database();

	if (!isset($nosession)) {

		ini_set('session.gc_maxlifetime', 60*60*24*7);
			session_name("AUSOURCE");
			session_set_cookie_params (60*60*24*7);
			session_set_save_handler('sOpen', 'sClose', 'sRead', 'sWrite', 'sDestroy', 'sGC');
		session_start();

	}

?>
