<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: class/lib_site.php
| Build 1
| <Changes>
| 
| <Purpose>
| Core Classes & Functions that power the sites non-roster based functions.
|
| TRSM1.0 is (c) Nolan 2003-2004, and is covered by the GPL License (See gpl.txt for more information)
*/
   
class specs {
    
    var $specid;
    var $sname;
    var $svalue;
    var $smulti;
    var $sord;
    
    function get($id) {
        global $sql, $table;
        $query = "SELECT * FROM " . $table['specs'] . " AS stats WHERE statid = '$id'";
        $res = $sql->queryme($query);
        $stats = $sql->sql_array($res);
        $this->specid = $stats['statid'];
        $this->sname = $stats['sname'];
        $this->svalue = $stats['value'];
        $this->smulti = $stats['multil'];
        $this->sord = $stats['sord'];
    }
    function newstat() {
        global $sql, $table;
        $ord = "SELECT max(sord) AS next FROM " . $table['specs'] . " AS stats";
        $res = $sql->queryme($ord);
        $next = $sql->sql_array($res);
        $new = "INSERT INTO " . $table['specs'] . "(sname, value, sord, multil) VALUES ('" . $this->sname . "', '" . $this->svalue . "', '" . $next['next'] . "', '" . $this->smulti . "')";
        $sql->insquery($new);
        $this->specid = $sql->lastid;
    }
    function update() {
        global $sql, $table;
        $update = "UPDATE " . $table['specs'] . " SET sname = '" . $this->sname . "', value = '" . $this->svalue . "', multil = '" . $this->smulti . "', sord = '" . $this->sord . "' WHERE statid = '" . $this->specid . "'";
        $sql->queryme($update);
    }
    function delete($id) {
        global $sql, $table;
        $getord = "SELECT sord FROM " . $table['specs'] . " AS stats WHERE statid = '$id'";
        $resord = $sql->queryme($getord);
        $ord = $sql->sql_array($resord);
        $update = "UPDATE " . $table['specs'] . " SET sord = sord -1 WHERE sord > '" . $ord['sord'] . "'";
        $sql->queryme($update);
        $del = "DELETE FROM " . $table['specs'] . " WHERE statid = '$id'";
        $sql->queryme($del);
    }
} //End of specifications class


//Core Login class, based on minibb
//Version: 1.0

class mbblogin {
    
    function addnew($un, $pw, $cid, $email) {
	global $sql, $table;
        $inslogin = "INSERT INTO " . $table['users'] . "(username, user_regdate, user_password, user_email, crewid, userlevel) VALUES ('$un', '" . now() ."', '" . md5($pw) ."', '$email', '$cid', '1')";
	$sql->queryme($inslogin);
    }
    
    
} //end of old minibb login class

class users extends mbblogin {
    
    var $uid;
    var $uname;
    var $pwd;
    var $ulevel;
    var $player;
    
    function get($id) {
	global $sql, $table;
	$usrq = "SELECT * FROM " . $table['users'] . " AS minibb_users WHERE user_id = '$id'";
	$resusr = $sql->queryme($usrq);
	$user = $sql->sql_array($resusr);
	$this->uid = $user['user_id'];
	$this->uname = $user['username'];
	$this->pwd = $user['user_password'];
	$this->ulevel = $user['userlevel'];
	$this->player = $user['crewid'];
    }
    function changepwd() {
	global $sql, $table;
	$query = "UPDATE " . $table['users'] . " SET user_password = '" . $this->pwd . "' WHERE user_id = '" . $this->uid . "'";
	$sql->queryme($query);
    }
    function update() {
	global $sql, $table;
	$query = "UPDATE " . $table['users'] . " SET userlevel = '" . $this->ulevel . "', crewid = '" . $this->player . "' WHERE user_id = '" . $this->uid . "'";
	$sql->queryme($query);
    }
} //end of users class

class menu {
    
    var $menu; //keeps the fetched menu
    var $count = 1; //keeps a count of what item is currently on stack
    
    var $name;
    var $img;
    var $link;
    var $acc;
    
    function menu() {
	global $sql, $table;
	$query = "SELECT * FROM menu ORDER BY section ASC";
	$result = $sql->queryme($query);
	while($menu = $sql->sql_object($result)) {
	    $this->menu[$menu->section][$menu->ord][name] = $menu->name;
	    $this->menu[$menu->section][$menu->ord][img] = $menu->image;
	    $this->menu[$menu->section][$menu->ord][link] = $menu->link;
	    $this->menu[$menu->section][$menu->ord][accl] = $menu->access;
	}
    }
    function next_item($section) {
	if($this->menu[$section][$this->count][link] == "") {
	    $this->count = 1;
	    return false;
	} else {
	    $this->name = $this->menu[$section][$this->count][name];
	    $this->img = $this->menu[$section][$this->count][img];
	    $this->link = $this->menu[$section][$this->count][link];
	    $this->acc = $this->menu[$section][$this->count][accl];
	    $this->count++;
	    return true;
	}
    }
} //menu class end

//Extra non-class associated
function sincewhen($timestamp) {
	//Function that returns a time value that reflects how much time has passed since that timestamp.
        //Author: Nolanator
        //Version 1.0
        $now = time();
	$diff = $now - $timestamp;
	$days = floor($diff / ((60 * 60) * 24));
	$hours = floor(($diff - ($days * ((60 * 60) * 24))) / (60 * 60));
	$minutes = floor(($diff - ($days * ((60 * 60) * 24)) - ($hours * (60 * 60))) / 60);
	$secounds = $diff - ($days * ((60 * 60) * 24)) - ($hours * (60 * 60)) - ($minutes * 60);
	if($days == 1) { $l_day = " Day, "; } else { $l_day = " Days, "; }
	if($hours == 1) { $l_hour = " Hour, "; } else { $l_hour = " Hours, "; }
	if($minutes == 1) { $l_min = " Minute, "; } else { $l_min = " Minutes, "; }
	if($minutes == 1) { $l_sec = " Secound "; } else { $l_sec = " Secounds "; }
	$since = $days . $l_day . $hours . $l_hour . $minutes . $l_min . $secounds . $l_sec . "since this site was installed"; 
	return $since;
}

// Core News Archive Class
// Version: 1.0
class news {
    
    var $nid;
    var $title;
    var $content;
    var $poster;
    var $date;
    
function fetch($id) {
    global $sql, $table;
    $get = "SELECT * FROM " . $table['news'] . " AS news WHERE newsid = '$id'";
    $res = $sql->queryme($get);
    $ns = $sql->sql_array($res);
    $this->nid = $ns['newsid'];
    $this->title = $ns['title'];
    $this->content = $ns['content'];
    $this->poster = $ns['poster'];
    $this->date = $ns['date'];
}

function create() {
    global $sql, $table;
    $query = "INSERT INTO " . $table['news'] . "(title, content, poster, date) VALUES ('" . $this->title . "', '" . $this->content . "', '" . $this->poster . "', '" . $this->date . "')";
    $sql->insquery($query);
    $this->nid = $sql->lastid;
}

function update() {
    global $sql, $table;
    $edquery = "UPDATE " . $table['news'] . " SET title = '" . $this->title . "', content = '" . $this->content . "', poster = '" . $this->poster . "', date = '" . $this->date . "' WHERE newsid = '" . $this->nid . "'";
    $sql->queryme($edquery);
}

function delete($id) {
    global $sql, $table;
    $delquery = "DELETE FROM " . $table['news'] . " WHERE newsid = '$id'";
    $sql->queryme($delquery);
}

}
?>