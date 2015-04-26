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

htmlStart();

htmlHeading('Register a Clan');

$flds = array('name','tag','tagalign','clanrep','url','other','email','pass');

foreach($flds as $fld) {

	$$fld = '';

}

if (isset($_GET['post'])) {

	foreach($flds as $fld) {
	
		if (!isset($_POST[$fld]))
			Header('Location: join.php');
		
		$$fld = $_POST[$fld];
	
	}

	if (strlen($name) < 1)
		$err[] = "Your Team Name needs to be filled out";
	
	$dat = $db->quickquery('select count(id) as c from clan where name = "'.$name.'"');
	if ($dat->c > 0)
		$err[] = "Your Team Name already exists in AUSource!";

	if (strlen($tag) < 1)
		$err[] = "You need to enter a Team Tag";

	if (strlen($clanrep) < 1)
		$err[] = "Please enter your clan representitive";

	if (strlen($pass) < 4)
		$err[] = "Your password would probably work better if it was longer then 3 characters";

	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
		$err[] = "Your email address doesn't look right";

        $dat = $db->quickquery('select count(id) as c from clan where email = "'.$email.'"');
        if ($dat->c > 0)
                $err[] = "Your email already exists in AUSource!";

	if (!isset($err)) {

		$g = new generateSQL('clan', 'insert');
		foreach($flds as $fld) {

			if ($fld == 'clanrep')
				continue;

			if ($fld == "pass")
				$g->field($fld, 'MD5(\'' . $$fld . '\')', 'number');
			else
				$g->field($fld, $$fld);

		}

		$sql = $g->sql();

		$res = $db->query($sql);
		
		$dat = $db->quickquery('select last_insert_id() as id');
		$id = $dat->id;
		
	
//		$id = mysql_insert_id();
		
		$g = new generateSQL('member', 'insert');
		$g->field('clanid', 'last_insert_id()', 'number');
		$g->field('email', $email);
		$g->field('isrep', 1);
		$g->field('active', 1);
		$g->field('name', $clanrep);
		
		$q = $g->sql();
		$db->query($q);
		
	    $c = makeActCode($id, $name);
		$activationlink = "http://ausource.slowchop.com/activate.php?id=$id&c=$c";

		$e =
"Hello,

Thank's for joining AUSource. To activate your clan's account click on the link below.

$activationlink

Make sure you visit the forums for any questions or feedback!

";

mail($email, "AUSource Activation Information.", $e, "From: AUSource <dontreply@slowchop.com>\r\n");
				

		htmlDiv();

		echo htmlGetContent('join_done');

		htmlDivEnd();

		htmlStop();

		die();
		

	}

}

htmlDiv();

echo htmlGetContent('join');

if (isset($err)) {
	
	echo '<p><b>There has been errors with your registration:</b><br>';
	
	echo '<ul class="post">';
	
	foreach($err as $e)
		echo '<li>' . $e . '</li>';

	echo '</ul>';

	echo '</p>';

}

htmlDivEnd();

htmlDiv();

//if (isset($_GET['d'])) {

?>

<form name="clanregister" method="post" action="join.php?post=1&showerror=1">
  <table width="100%" border="0">
    <tr> 
      <td height="30" colspan="2"><h1><span class="darkred">Clan Information</span></h1></td>
    </tr>
    <tr> 
      <td width="240"><span class="darkred">*</span> <strong>Full Team Name</strong></td>
      <td><input name="name" type="text" id="clanname" size="40" value="<?=$name?>"> </td>

    </tr>
    <tr> 
      <td width="240"><span class="darkred">*</span> <strong>Team Tag</strong></td>
      <td><input name="tag" type="text" id="clantag" size="40" value="<?=$tag?>"> </td>
    </tr>
    <tr> 
      <td width="240"><span class="darkred">*</span> <strong>Tag Position</strong></td>

      <td><select name="tagalign" id="tagposition">
          <option value="0"<?if($tagalign==0)echo' SELECTED';?>>Left</option>
          <option value="1"<?if($tagalign==1)echo' SELECTED';?>>Right</option>
        </select> </td>
    </tr>
    <tr> 
      <td width="240"><span class="darkred">*</span> <strong>Clan Member Ladder Representitive</strong></td>
      <td><input name="clanrep" type="text" id="clanrep" size="40" value="<?=$clanrep?>"></td>
    </tr>
    <tr> 
      <td width="240">URL</td>
      <td><input name="url" type="text" id="clanwebsite" size="40" value="<?=$url?>"> </td>
    </tr>
    <tr> 
      <td width="240">Other Contact</td>

      <td><input name="other" type="text" id="othercontact" size="40" value="<?=$other?>"></td>
    </tr>
    <tr> 
      <td width="240"><span class="darkred">*</span> <strong>Email Address</strong></td>
      <td><input name="email" type="text" id="emailaddress" size="40" value="<?=$email?>"></td>
    </tr>
    <tr> 
      <td width="240"><span class="darkred">*</span> <strong>Clan Password</strong></td>

      <td><input name="pass" type="password" id="password" size="40" value=""></td>
    </tr>
    <tr> 
      <td width="240">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2"><h1><span class="darkred">Join Ladder</span></h1></td>
    </tr>

    <tr> 
      <td><input type="submit" name="Submit" value="Join Ladder"></td>
      <td>Make sure you have read and understood the <a href="rules.php">ladder rules</a> and double check your clan 
        information before submitting the information.</td>
    </tr>
  </table>
  
<?
// }
// echo "<p><b>Due to unforseen issues, I won't be able to fix this page until tomorrow or Sunday. Sorry for the inconvenience.</b></p>";

htmlDivEnd();

htmlStop();
  
?>
