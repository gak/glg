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

function sOpen($path, $name) {

	sGC(0);
	
	return true;	

}

function sClose() {

	return true;	

}

function sRead($id) {

	global $db;
	$dat = $db->quickquery("select * from session where id = '$id'");

	if ($db->count() == 0) {

		return "";

	}

	return $dat->value;

}

function sWrite($id, $data) {

	global $db;
	global $_SERVER;
	
 	$data = str_replace("'", "''", $data);

	$db->query("select * from session where id = '$id'");

	$page = $_SERVER["REQUEST_URI"];

	if ($page == "/style.php")
		$page = "";
	
	if ($db->count()) {
	
		if ($page != "")			
			$sqlpage = ",page = '$page'";
		else
			$sqlpage = "";

		$db->query("

			update session
			set
				time = ".time().",
				value = '$data'
				$sqlpage

			where id = '$id'

		");
		
	} else {
		 
		$db->query("

			insert into session
			(id, time, start, value)
			values
			('$id', ".time().", ".time().", '$data')

		");
		
	}

	return true;

}

function sDestroy($id) {

	$db->query("delete from session where id = '$id'");

	return true;

}

function sGC($life) {

	global $db;
	
	$ses_life = strtotime("-7 days"); 

	$db->query("delete from session where time < $ses_life");

	return true;

}

?>