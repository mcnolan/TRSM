<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: core.php
| Build 6
| <Changes>
| 
|
| <Purpose>
| Contains all the classes & functions needed to run the system.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/

class mysql {
//	mySQL connection & query class	//

var $hostname;
var $username;
var $password;
var $database;
var $link;

var $lastid;

function startup() {
	//Start the connection to the mySQL database
        $this->link = mysql_connect($this->hostname, $this->username, $this->password) or die("Fatal mySQL Error, could not connect. Check mySQL settings!<br>" . mysql_error());
	if(!mysql_select_db($this->database,$this->link)) {
		echo mysql_error();
	}
}

function close() {
	//Close active connection
        mysql_close($this->link);
}

function queryme($query) {
	mysql_connect($this->hostname, $this->username, $this->password);
	mysql_select_db($this->database);
	
	$result = mysql_query($query) or die(mysql_error());
	mysql_close();
	return $result;
}

function insquery($query) {
	mysql_connect($this->hostname, $this->username, $this->password);
	mysql_select_db($this->database);
	
	$result = mysql_query($query) or die(mysql_error());
	$this->lastid = mysql_insert_id();
	mysql_close();
	return $result;
}

function mysql() {
	global $host,$user,$pass,$datab;
	$this->hostname = $host;
	$this->username = $user;
	$this->password = $pass;
	$this->database = $datab;
}

}

class sql extends mysql {
//	New extended faster version of the mysql class	//
function queryme($query, $repress = false) {
	if($repress == false) {
		$result = mysql_query($query, $this->link) or die(mysql_error());
	} else {
		$result = mysql_query($query, $this->link);
	}
	return $result;
}

function insquery($query) {
	$result = mysql_query($query,$this->link) or die(mysql_error());
	$this->lastid = mysql_insert_id();
	return $result;
}

function sql() {
	global $host,$user,$pass,$datab;
	$this->hostname = $host;
	$this->username = $user;
	$this->password = $pass;
	$this->database = $datab;
	$this->startup();
	if(!$this->installstatus()) {
		echo "<<  This copy of TRSM has not been installed correctly, please contact the webmaster";
		die();
	}
}

function installstatus() {
	global $installed, $table;
	$query = "SELECT joined FROM " . $table['crew'] . " AS crew WHERE crewid = '1'";
	if($date = $this->queryme($query,true)) {
		$row = mysql_fetch_row($date);
		$installed = $row[0];
		return true;
	} else {
		return false;
	}
}

function sql_array($resource) {
	return mysql_fetch_array($resource);
}

function sql_row($resource) {
	return mysql_fetch_row($resource);
}
function sql_num_rows($resource) {
	return mysql_num_rows($resource);
}
function sql_object($resource) {
	return mysql_fetch_object($resource);
}
} //End of sql class

function select_list($current, $max) {
	for($i=1;$i<=$max;$i++) {
		if($i == $current) {
		$output .= "<option value=$i selected>$i</option>";
		} else {
		$output .="<option value=$i>$i</option>";
		}
	}
return $output;
}

function now() {
	return date("Y-m-d H:i:s");
}

function checkcreds() {
	global $cookiename, $table;
	$sql = new sql;
	if(isset($_COOKIE[$cookiename])) {
		$cook = explode("|", $_COOKIE[$cookiename]);
		$check = "SELECT crewid, userlevel FROM " . $table['users'] . " WHERE username = '" . $cook[0] . "' AND user_password = '" . $cook[1] . "'";
		$res = $sql->queryme($check);
		if($sql->sql_num_rows($res) != 0) {
			$row = $sql->sql_array($res);
			if(!empty($_COOKIE['userl'])) { $_COOKIE['userl'] = $row['userlevel']; }
			if(!empty($_COOKIE['crewid'])) { $_COOKIE['crewid'] = $row['crewid']; }
		} else {
			die("Your credentials may be incorrect, contact your website administrator");
		}
	}
}

function is_loggedin() {
	global $cookiename;
	checkcreds();
	if($_COOKIE['logged'] == 'yes' && !empty($_COOKIE[$cookiename])) {
		return true;
	} else {
		return false;
	}
}
function is_crewmember() {
	if(is_loggedin() && ($_COOKIE['crewid'] != 0) && $_COOKIE['userl'] != 0) {
		return true;
	} else {
		return false;
	}
}
function is_admin() {
	//Check to see if the logged in user has any admin access
        if(($_COOKIE['admin'] == 'rar') && ($_COOKIE['userl'] > 1)) {
		return true;
	} else {
		return false;
	}
}
function has_rights($accessl) {
	//Check to see if currently logged user has the rights to access this
        if($_COOKIE['userl'] >= $accessl) {
		return true;
	} else {
		return false;
	}
}
function emails() {
	global $email_level, $table;
	$sql = new sql;
	$getemails = "SELECT email FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['users'] . " AS minibb_users ON(crew.crewid = minibb_users.crewid) WHERE minibb_users.userlevel >= '$email_level'";
	$resmail = $sql->queryme($getemails);
	$c = 0;
	while($emails = $sql->sql_object($resmail)) {
		if($c == 0) { $emailstr = $emails->email; $c++; } else {
		$emailstr .= ", " . $emails->email;
		}
	}
	return $emailstr;
}
function dataCheck($string) {
	return addslashes($string);
}
function reformatData($string) {
	return stripslashes($string);
}

//	Additional Modules	//

include_once "class/lib-roster.php";
include_once "class/lib-site.php";

if($mod_awards) { include_once "class/awards.php"; }
if($mod_missions) { include_once "class/missions.php"; }
if($mod_report) { include_once "class/posting.php"; }
if($mod_records) { include_once "class/record.php"; }
?>