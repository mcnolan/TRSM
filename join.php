<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: join.php
| Build 5
| <Changes>
| Changed call to action variable, to comply with globals off
| Changed Css
| Fixed application setup error
| Changed where email is sent in an attempt to circumvent a problem that exists with webhosts that do not support the mail() function 
|
| <Purpose>
| Join form and processor.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
$pagename = "Join";
include "header.php";
if($_GET['action'] != "submit") {
?>
<img src="<? echo $imagepath; ?>header-app.gif">
<p>
    This is the official application form to apply for service aboard <? echo $shipname; ?>. Please fill out every field except when instructed, to submit this form and apply to join the ship.
</p>
<form action="join.php?action=submit" method="post">
<table width="90%" align="center">
<tr>
    <td class="joinLabel" width="30%">Real Name</td>
    <td><input type="text" name="rname" size="20"></td>
</tr>
<tr>
    <td class="joinLabel">Real Age</td>
    <td><input type="text" name="rage" size="4" maxlength="3"></td>
</tr>
<tr>
    <td class="joinLabel">Email Address</td>
    <td><input type="text" name="email"></td>
</tr>
<tr>
    <td class="joinLabel">PBEM Experience</td>
    <td><textarea name="pbem_exp" cols="25" rows="3"></textarea></td>
</tr>
<tr>
    <td class="joinLabel">Site Username</td>
    <td><input type="text" name="usern" maxlength="40" size="20"></td>
</tr>
<tr>
    <td class="joinLabel">Password</td>
    <td><input type="password" name="passwd" maxlength="40" size="15"></td>
</tr>
<tr>
    <td class="joinLabel">Again</td>
    <td><input type="password" name="pass2" maxlength="40" size="15"></td>
</tr>
<tr>
    <td class="joinLabel">Requested Position</td>
    <td><select name="position">
<?
$getpos = "SELECT * FROM " . $table['positions'] . " AS positions LEFT JOIN " . $table['crew'] . " AS crew ON(positions.crewid = crew.crewid) WHERE positions.crewid = '0' OR crew.active = '3' ORDER BY deptid, ord";
$respos = $sql->queryme($getpos);
while($position = $sql->sql_array($respos)) {
?>
        <option value="<? echo $position['posid']; ?>"><? echo $position['name']; ?></option>
<?
}
?>
    </select>
    </td>
</tr>
<tr>
    <td class="joinLabel">Character Name</td>
    <td><input type="text" name="f_name" value="First" maxlength="40" size="20"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type="text" name="m_name" value="Middle" maxlength="40" size="20"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type="text" name="l_name" value="Last" maxlength="40" size="20"></td>
</tr>
<tr>
    <td class="joinLabel">Gender</td>
    <td><select name="gender"><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select></td>
</tr>
<tr>
    <td class="joinLabel">Species</td>
    <td><input type="text" name="species" size="15"></td>
</tr>
<?
$getcustom = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds ORDER BY ord ASC";
$rescustom = $sql->queryme($getcustom);
while($custom = $sql->sql_array($rescustom)) {
?>
<tr>
    <td class="joinLabel"><? echo $custom['name']; ?></td>
    <td>
<? switch($custom['type']) {

case "line" :
	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"$line_size\">";
break;

case "box" :
	echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"$box_cols\" rows=\"$box_rows\"></textarea>";
break;

case "line_small" :
	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"$linesmall_size\">";
break;

case "line_med" :
	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"$linemed_size\">";
break;

case "box_large" :
	echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"$boxlarge_cols\" rows=\"$boxlarge_rows\"></textarea>";
break;
}
?>
    </td>
</tr>
<?
}
?>
<tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit Application"> <input type="reset" value="Reset"></td>
</tr>
</table>
<?
} else {
//Start loop, check all variables
$crew = new crew;
$error = 0;
foreach($_POST as $pkey => $pvalue) {

	switch($pkey) {
	
	case "rname" :
		if($pvalue == "") {
			echo "Real Name Missing<br>";
			$error++;
		} else {
			$message .= "Real name: $pvalue\n";
		}
	break;
	case "rage" :
		if($pvalue == "") {
			echo "Real Age Missing<br>";
		} else {
			$message .= "Real Age: $pvalue\n";
		}
	break;
	case "pbem_exp" :
		if($pvalue == "") {
			echo "PBEM Experience Missing<br>";
			$error++;
		} else {
			$message .= "PBEM Exp: $pvalue\n\n";
		}
	break;
	case "f_name" :
		if($pvalue == "" || $pkey == "First") {
			echo "First Name Missing<br>";
			$error++;
		} else {
			$message .= "First name: $pvalue\n";
			$crew->fn = dataCheck($pvalue);
		}
	break;
	case "m_name" :
		$message .= "Middle name: $pvalue\n";
		if($pvalue <> "Middle") { $crew->mn = dataCheck($pvalue); }
	break;
	case "l_name" :
		$message .= "Last name: $pvalue\n\n";
		if($pvalue <> "Last") { $crew->ln = $pvalue; }
	break;
	case "email" :
		if($pvalue == "") {
			echo "You need an email address<br>";
			$error++;
		} else {
			$message .= "Email Addy: $pvalue\n\n";
			$crew->email = $pvalue;
		}
	break;
	case "usern" :
		if($pvalue == "") {
			echo "Need a username for the site<br>";
			$error++;
		} else {
			$findun = "SELECT user_id FROM " . $table['users'] . " AS minibb_users WHERE username = '" . $pvalue . "'";
			$res = $sql->queryme($findun);
			if($sql->sql_num_rows($res) == 0) {
			$message .= "Site Username: $pvalue\n\n";
			} else {
			echo "Username is already taken, please select another";
			$error++;
			}
		}
	break;
	case "passwd" :
		if($pvalue == "") {
			echo "Need A Password For The Site<br>";
			$error++;
		} else {
		if($_POST['passwd'] <> $_POST['pass2']) {
			echo "Password Fields Do Not match<br>";
			$error++;
		} else {
				
		}
		}
	break;
	case "pass2" :
		if($pvalue == "") {
			echo "You need to repeat your password for security purposes<br>";
			$error++;
		}
	break;
	case "gender" :
		$message .= "Gender: $pvalue\n";
		$crew->gender = $pvalue;
	break;
	case "species" :
		if($pvalue == "") {
			echo "Species has been left blank<br>";
			$error++;
		} else {
			$message .= "Species: $pvalue\n";
			$crew->species = dataCheck($pvalue);
		}
	break;
	case "position" :
		$pos = new position;
		$pos->get($_POST['position']);
		$message .= "Requested Position: " . $pos->name . "\n";
	break;
	default:
		$f = new cfields;
		$f->get($pkey);
		if($pvalue == "") {
			echo $f->name . " has been left blank<br>";
			$error++;
		} else {
			$message .= $f->name . ": $pvalue\n";
			$write[$pkey] = $pvalue;
		}
	break;
	} //switch

} //foreach post loop

if($error == 0) {
	$crew->rnk = 1;
	$crew->active = 0;
	$crew->joined = time();
	$begin = "The following Application was recieved " . date("M j Y") . "\n\n";
	$fmessage = $begin . $message;
	//find Co email
        $to = emails();
	$subject = $shipname . " Application";
	$from = "From: " . $crew->fn . " " . $crew->ln . "<" . $crew->email . ">";

	$crew->add();
		//Add the pending player into the position data
		$pos->crewm = $crew->cid;
		$pos->update();
	foreach($write as $wkey => $wvalue) {
		$content = new f_cont;
		$content->fldid = $wkey;
		$content->info = dataCheck($wvalue);
		$content->crewid = $crew->cid;
		$content->set();
	} //write foreach
        //Add pending future time served awards auto
        if($mod_awards) {
            $awds = new awarded;
            $awds->crew_update($crew->cid);
        }
	//This section handles adding the new user to the forums and setting up their site wide username and password
	$inslogin = "INSERT INTO " . $table['users'] . "(username, user_regdate, user_password, user_email, crewid, userlevel) VALUES ('" . $_POST['usern'] . "', '" . now() ."', '" . md5($_POST['passwd']) ."', '" . $_POST['email'] . "', '" . $crew->cid . "', '1')";
	$sql->queryme($inslogin);
	/**/
	mail($to,$subject,$fmessage,$from) or print("Could Not Send Email, Check with the Co for a copy.\n");
	echo "Application has been successful. The Commanding Officer will reply to you within a few days.";
	echo nl2br($fmessage);
} else {
	echo "<br>Application has not been sent for the above reasons, please press back on your browser and make sure all fields are complete";
} //error check

} //end main action if
include "footer.php"; ?>