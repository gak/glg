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

$res = $db->query('

   select clan.*, member.name as membername, member.acssrid
   from clan
   left join member on member.clanid = clan.id and member.isrep = 1
   order by name
   
');

htmlStart();
	
	while ($dat = $db->fetchObject($res)) {

		clanDetails($dat,1);
		
	}


htmlStop();

?>
