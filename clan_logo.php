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

checkClan();

$maxsizeimage = 5000;
$maxsizeimagesmall = 1200;

function processUpload($name, $maxsize) {
	
	global $_FILES;
	global $err;
	global $db;
	global $clan;

	if (isset($_FILES[$name])) {

		if ($_FILES[$name]['size'] == 0)
			return;

		$tmp_name = $_FILES[$name]['tmp_name'];
		$original_name = $_FILES[$name]['name'];
		$img = file_get_contents($tmp_name);

		if (substr($img, 1, 3) != "PNG") {

			$err[] = $original_name . " is not a PNG file!";

		}
		
		if ($_FILES[$name]['size'] > $maxsize) {

			$err[] = $original_name . " has a filesize that is too big!";

		}

		$f = getimagesize($tmp_name);

		if ($name == 'image') {

			if ($f[0] > 100 || $f[1] > 100) {

				$err[] = $original_name . " is too big in width/height.";

			}

		} else {

			if ($f[0] != 16 || $f[1] != 16) {

				$err[] = $original_name . " isn't 16x16.";
			

			}

		}

		if (isset($err)) {
			return;
		}

		$img = addSlashes($img);

		$q = "update clan set $name = '$img' where id = {$clan->id}";
		$db->query($q);

	}

}

unset($err);
if (isset($_POST)) {

	processUpload('image', $maxsizeimage);
	processUpload('imagesmall', $maxsizeimagesmall);

}

htmlStartClan();

htmlDiv();
htmlHeading('Clan Logo');
echo htmlGetcontent("members_logo");
if (isset($err)) {
	htmlSubHeading('Upload Failed');
	echo '<p class="error">';
	foreach($err as $e) {
		echo ' - '.$e.'<br>';
	}
	echo '</p>';
}
htmlDivEnd();

htmlDiv();

htmlSubHeading('Current Main Logo');
echo '<p><table width="115" height="115"><tr><td bgcolor="#F1F1F1" align="center">';
echo '<img src="img.php?id='.$clan->id.'">';
echo '</table></p>';

htmlSubHeading('Current Small Logo');
echo '<p><table><tr><td bgcolor="#F1F1F1" width="16" height="16">';
echo '<img src="img.php?id='.$clan->id.'&sml=1">';
echo '</table></p>';

htmlDivEnd();

htmlDiv();

htmlFormTableStart();
echo '<form action="clan_logo.php" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$maxsizeimage.'">';
htmlFormHeading('Upload Main Logo', 'Must be a <b>PNG</b>, less then <b>'.$maxsizeimage.' bytes</b> in size and have a resolution that is less then <b>100x100</b>.');
htmlFormRow('<input type="file" name="image">');
htmlFormHeading('Upload Small Logo', 'Must be a <b>PNG</b>, less then <b>'.$maxsizeimagesmall.' bytes</b> in size and has to be <b>exactly 16x16</b>.');
htmlFormRow('<input type="file" name="imagesmall">');
htmlFormRow('<input type="submit" value="Upload New Logos">');
echo '</form>';

htmlFormTableEnd();
htmlDivEnd();

htmlStop();

?>
