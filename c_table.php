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

class Table {

	var $html;

	function Table($extra="") {
	
		$this->html = '<table width="100%" border="0"'.$extra.'>';
	
	}
	
	function HeaderRow($cells) {
	
		$this->html .= '<tr bgcolor="#5E839B" align="center">';
	
		foreach($cells as $cell) {
		
			$this->html .= '<td';
		
			if (isset($cell['colspan'])) {
			
				$this->html .= ' colspan="'.$cell['colspan'].'"';
			
			}
		
			$this->html .= '><span class="white"><strong>';
			$this->html .= $cell['v'];
			$this->html .= '</strong></span></td>';
		
		}
	
		$this->html .= '</tr>';

	}
	
	function Row($cells) {
	
		$this->html .= '<tr bgcolor="#F1F1F1">';
	
		foreach($cells as $cell) {
		
			$this->html .= '<td';
		
			if (isset($cell['colspan']))
				$this->html .= ' colspan="'.$cell['colspan'].'"';
		
			if (isset($cell['rowspan']))
				$this->html .= ' rowspan="'.$cell['rowspan'].'"';
		
			if (isset($cell['width']))
				$this->html .= ' width="'.$cell['width'].'"';
		
			if (isset($cell['align']))
				$this->html .= ' align="'.$cell['align'].'"';
		
			$this->html .= '>';
			$this->html .= $cell['v'];
			$this->html .= '</td>';
		
		}
		
		$this->html .= '</tr>';
	
	}
	
	function output() {
	
		$this->html .= '</table>';
		
		echo $this->html;
	
	}
	
}

?>