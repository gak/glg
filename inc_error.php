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

function error($message = "") {

	if (error_reporting() == 0)
		return;
	
	// do a rollback
	global $db;
	//$db->query("ROLLBACK");
	
	global $nohtml;

	if (ob_get_length()) {

		$html = ob_get_contents();
		ob_end_clean();
		ob_start();
		
	} else {
	
		$html = "";
	
	}
	
	if (!isset($nohtml)) {
	
		$nohtml = 0;
		
	}
	
	htmlStart();
?>
	<h2>OOOPS!</h2>

	<p>

	There has been an error on this web site. It might be a little glitch... Refreshing may or may not help!<br><br>

	Every error that happens on AUSOURCE is recorded so I can tell what went wrong, when and where.<br><br>

	If this error keeps happening please try the page at a later time...

	</p>

<?
	htmlStop();
		
	$bt = debug_backtrace();

	$out = "";
	
	
	global $_POST, $_GET, $_COOKIE;

	function dumpArray($t, $a, &$out) {

		if (sizeof($a) == 0)
			return;

		$out .= "<table class=\"error\">";
		$out .= "<tr><td class=\"error\">";
	
		$out .= "<tr><th class=\"error\" colspan=\"2\">$t";
		foreach($a as $k=>$p) {
			$out .= "<tr><td>$k<td>$p";
		}
		
		$out .= "</table><br>";

	}
	
	$out .= "<table class=\"error\">";
	$out .= "<tr><td>";
		
	$out .= $message;

	$i = 0;

	foreach($bt as $t) {
	
		$i++;
		if ($i <= 2)
			continue;

		$out .= "<tr><td>";

		if (isset($t["file"]) && isset($t["line"])) {
			$out .= $t["file"] . ":" . $t["line"];
		}
		
		$out .= "<td>";
			
		$out .= $t["function"];
		
	}

	$out .= "</table>";
	$out .= "<pre>";
	
	$out .= $html;

	$out .= "</pre>";

	dumpArray('POST', $_POST, $out);
	dumpArray('GET', $_GET, $out);

	$out .= "<table class=\"error\">";
	$out .= "<td>URL<td>". $_SERVER['SCRIPT_FILENAME'];
	$out .= "</table>";

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: AUSOURCE Error <email>\r\n";

	die('email address here');
	mail("email", $message, $out, $headers);

	die();	

}

function errorhandler($errno, $errstr, $errfile, $errline) {

	error("$errfile:$errline $errstr ($errno)");

}

?>
