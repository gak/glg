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

require_once("include.php");

htmlStart();

	htmlHeading("About AUSource CS:S Ladder", "Your Australian CS:S Ladder");

	htmlDiv();
	
		echo htmlGetContent("home_introduction");

	htmlDivEnd();

	htmlDiv();

		$dat = $db->quickquery('select count(*) as c from clan');
		$clans = $dat->c;

		$dat = $db->quickquery('select * from stats');
		$hits = $dat->hits;

		htmlSubHeading('Quick Statistics');
		echo '<p>';
		echo "There have been $hits hits since AUSource opened in Late January 2005. ";
		echo "There are currently $clans clans registered. ";
		echo '</p>';

	htmlDivEnd();

	htmlDiv();

		htmlSubHeading('Latest News');

		$res = $db->query("select *, admin.name from news left join admin on admin.id = news.who order by `when` desc limit 5");
		while (($dat = $db->fetchObject($res))) {
			htmlNewsEntry($dat->subject, $dat->when, $dat->body, $dat->name);
		}

	htmlDivEnd();

htmlStop();

?>
