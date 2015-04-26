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

class TableEditor {

	function TableEditor($table, $page) {

		$this->InternalInit();

		$this->table = $table;
		$this->page = $page;


	}

	function InternalInit() {

		$this->extrafields = array(
			'lte',		// link to edit (in list page)
			'edit',		// editable
			'inputtext',	// edit as input type=text
			'inputarea',	// edit as textarea
			'inputcheckbox',// edit as checkbox
			'dontlist',	// dont list
			'external'	// has an external value in $field[][value]
		);

	}

	function AddField($name, $desc, $extra = '') {

		foreach($this->extrafields as $field) {

			$$field = false;

		}

		$extralist = explode(',', $extra);
		foreach($extralist as $ex) {

			foreach($this->extrafields as $field) {

				if ($field == $ex)
					$$field = true;
		

			}

		}

		$r = array('name'=>$name, 'desc'=>$desc);

		foreach($this->extrafields as $field) {

			$r[$field] = $$field;

		}

		$this->fields[] = $r;

	}

	function EditField($f, $n, $v) {
	
		for ($i = 0; $i < sizeof($this->fields); $i++) {
		
			$field = &$this->fields[$i];

			if ($field['name'] == $f) {
			
				$field[$n] = $v;
				return;

			}

		}

	}

	function Show() {

		global $_GET;

		if (isset($_GET['edit']))
			$this->ShowEditRow($_GET['edit']);
		else if (isset($_GET['posted']))
			$this->ProcessEditRow();
		else
			$this->ShowList();

	}

	function ShowList() {

		global $db;

		$res = $db->query('select * from ' . $this->table);

		echo '<table border=1>';

		echo '<tr>';

		foreach($this->fields as $field) {

			if ($field['dontlist'])
				continue;
				
			echo '<th>' . $field['desc'];
		}

		while (($dat = $db->fetchObject($res))) {
			

			echo '<tr>';
			foreach($this->fields as $field) {
			
				if ($field['dontlist'])
					continue;

				echo '<td valign=top>';
				
				if ($field['lte'])
					echo '<a href="'.$this->page.'?edit='.$dat->id.'">';
				
				echo $dat->$field['name'];

				if ($field['lte'])
					echo '</a>';
			}

		}

		echo '</table>';

	}

	function ShowEditRow($id) {

		global $db;

		$dat = $db->quickquery('select * from ' . $this->table . ' where id = ' . $id);

		echo '<form method="post" action="'.$this->page.'?posted=1">';

		echo '<input type="hidden" name="id" value="'.$id.'">';

//		echo '<table border=1>';
		htmlFormTableStart();

		foreach($this->fields as $field) {

			if ($field['external']) {
			
				if (!isset($field['value']))
					user_error("external flag set, but value not set");
			
				echo '<input type="hidden" name="'.$field['name'].'" value="'.$field['value'].'">';
			}

			if ($field['dontlist'])
				continue;

//			echo '<tr>';
//			echo '<th>' . $field['desc'];
			htmlFormHeading($field['desc']);
			
//			echo '<td>';

			$o = '';

			if ($field['edit']) {

				if ($field['inputtext'])
					$o .= '<input type="text" size="40" name="'.$field['name'].'" value="'.$dat->$field['name'] . '">';

				if ($field['inputarea'])
					$o .= '<textarea rows="20" cols="60" name="'.$field['name'].'">' . $dat->$field['name'] . '</textarea>';

				if ($field['inputcheckbox']) {
				
					$o .= '<input'.(($dat->$field['name'])?' CHECKED':'').' value="1" type="checkbox" name="' . $field['name'] . '">';

				}

			}

			htmlFormRow($o);

		}

//		echo '<tr>';
//		echo '<th>';
//		echo '<td><input type="submit" value="update">';
		htmlFormRow('<input type="submit" value="update">');

//		echo '</table>';
		htmlFormTableEnd();

		echo '</form>';

	}

	function ProcessEditRow() {

		$g = new generateSQL($this->table, 'update', $_POST['id']);
		foreach($this->fields as $field) {

			if ($field['edit']) {

				$name = $field['name'];
				
				if ($field['inputcheckbox'] && !isset($_POST[$name]))
					$_POST[$name] = 0;

				$g->field($name, $_POST[$name]);

			}

		};

		global $db;
		$db->query($g->sql());

		Header('Location: ' . $this->page);
		die();

	}

}

?>
