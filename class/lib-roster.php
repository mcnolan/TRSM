<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.01
|
| File: class/lib-roster.php
| Build 2
| <Changes>
| Added CoC support classes
|
| <Purpose>
| Core Crew and Roster Classes.
|
| TRSM1.0 is (c) Nolan 2003-2006, and is covered by the GPL License (See gpl.txt for more information)
*/

class crew {

var $cid;
var $fn;
var $mn;
var $ln;
var $rnk;
var $species;
var $gender;
var $email;
var $joined;
var $active;
var $loa;
var $rankn;
var $ranki;
var $npc = false;

function add() {
	global $sql, $table;
	if($this->npc) {
		$this->active = 3;
	}
	$newb = "INSERT INTO " . $table['crew'] . "(first_n, middle_n, last_n, rankid, species, gender, email, joined, active, loa) VALUES ('" . $this->fn . "', '" . $this->mn . "', '" . $this->ln . "', '" . $this->rnk . "', '" . $this->species . "', '" . $this->gender . "', '" . $this->email . "', '" . $this->joined . "', '" . $this->active . "', '0')";
	$sql->insquery($newb);
	$this->cid = $sql->lastid;
}

function get($id) {
	global $sql, $table;
	$get = "SELECT * FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE crewid = '" . $id . "'";
	$r = $sql->queryme($get);
	$c = $sql->sql_array($r);
	$this->cid = $id;
	$this->fn = $c['first_n'];
	$this->mn = $c['middle_n'];
	$this->ln = $c['last_n'];
	$this->rnk = $c['rankid'];
	$this->species = $c['species'];
	$this->gender = $c['gender'];
	$this->email = $c['email'];
	$this->joined = $c['joined'];
	$this->active = $c['active'];
	$this->loa = $c['loa'];
	$this->rankn = $c['name'];
	$this->ranki = $c['url'];
	if($this->active == 3) { $this->npc = true; }
}

function update() {
	global $sql, $table;
	$update = "UPDATE " . $table['crew'] . " SET first_n = '" . $this->fn . "', middle_n = '" . $this->mn . "', last_n = '" . $this->ln . "', rankid = '" . $this->rnk . "', species = '" . $this->species . "', gender = '" . $this->gender . "', email = '" . $this->email . "', joined = '" . $this->joined . "', active = '" . $this->active . "', loa = '" . $this->loa . "' WHERE crewid = '" . $this->cid . "'";
	$sql->queryme($update);
}

function tgloa() {
	global $sql, $table;
	if($this->loa == 1) {
		$this->loa = 0;
	} else {
		$this->loa = 1;
	}
	$update = "UPDATE " . $table['crew'] . " SET loa = '" . $this->loa . "' WHERE crewid = '" . $this->cid . "'";
	$sql->queryme($update);
}

function tgactive() {
	global $sql, $table;
	if($this->active == 1) {
		$this->active = 0;
	} else {
		$this->active = 1;
	}
	$update = "UPDATE " . $table['crew'] . " SET active = '" . $this->active . "' WHERE crewid = '" . $this->cid . "'";
	$sql->queryme($update);
}

function del($id) {
	global $sql, $mod_awards, $mod_report, $mod_records, $remove_posts, $remove_awards, $table;
	//begin by destroying all regular data
	$this->get($id);
	$fldc = new f_cont;
	$del = "DELETE FROM " . $table['crew'] . " WHERE crewid = '$id'";
	$fixus = "UPDATE " . $table['users'] . " SET crewid = '0', userlevel = '0' WHERE crewid = '$id'";
	$fixps = "UPDATE " . $table['positions'] . " SET crewid = '0' WHERE crewid = '$id'";
	$sql->queryme($del);
	$sql->queryme($fixus);
	$sql->queryme($fixps);
	$fldc->del_bycrewid($id);
	//Destory all custom mod data as specified in the settings file
	if($mod_awards) {
		$awd = new awarded;
		if($remove_awards) {
			$awd->removebycrew($id);
		} else {
			$awd->update_ondel($id, $this->fname());
		}
	}
	if($mod_report) {
		$psts = new posts;
		if($remove_posts) {
			$psts->removebycrew($id);
		} else {
			$psts->update_ondel($id, $this->fname());
		}
	}
	if($mod_records) {
		$rec = new record;
		$rec->deletebycrew($id);
	}
}
function fname() {
	return $this->rankn . " " . $this->fn . " " . $this->ln;
}
function gtotal() {
	global $sql, $table;
	$total = "SELECT crewid FROM " . $table['crew'] . " AS crew";
	$res = $sql->queryme($total);
	$t = $sql->sql_rows($res);
	return $t;
}

function newbies() {
	global $sql, $table;
	$since = strtotime("-3 days");
	$get = "SELECT crew.crewid, crew.first_n, crew.last_n, rank.name FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE joined > '$since' AND active = '1'";
	$resg = $sql->queryme($get);
	return $resg;
}
function toString() {
	global $sql, $table;
	$this->get($this->cid);
	$string .= $this->fname() . "\n\n";
	$string .= "Species/gender: " . $this->species . "/" . $this->gender . "\n";
	$string .= "Email: " . $this->email . "\n\n";
	$fetchflds = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds LEFT JOIN " . $table['biodata'] . " AS feild_data ON(cust_feilds.fid = feild_data.fid) WHERE feild_data.crewid = '" . $this->cid . "' ORDER BY ord ASC";
	$result = $sql->queryme($fetchflds);
	while($fld = $sql->sql_array($result)) {
		$string .= $fld['name'] . ": " . $fld['info'] . "\n";
	}
	return $string;
}
} //End of crew class

//Core Department Class
//Author: Nolanator
//Version: 1.0

class departments {
   
   function next_ord() {
   global $sql, $table;
   $last = "SELECT max(ord) AS last FROM " . $table['departments'] . " AS departments";
   $res = $sql->queryme($last);
   $last = $sql->sql_array($res);
   return $last['last'] + 1;
   }
} //end of departments class

class department {

var $depid;
var $name;
var $order;
var $css;

function get($id) {
   global $sql, $table;
   $get = "SELECT * FROM " . $table['departments'] . " AS departments WHERE deptid = " . $id;
   $result = $sql->queryme($get);
   $dept = $sql->sql_array($result);
   $this->depid = $dept['deptid'];
   $this->name = $dept['name'];
   $this->order = $dept['ord'];
   $this->css = $dept['class'];
}
function addnew() {
	global $sql, $table;
        $dep = new departments;
	$add = "INSERT INTO " . $table['departments'] . "(name, class, ord) VALUES ('" . $this->name . "', '" . $this->css . "', '" . $dep->next_ord() . "')";
	$sql->queryme($add);
}

function update() {
   global $sql, $table;
   $update = "UPDATE " . $table['departments'] . " SET name = '" . $this->name . "', class = '" . $this->css . "' WHERE deptid = '" . $this->depid . "'";
   $sql->queryme($update);
}

function del($id) {
   global $sql, $table;
   $del = "DELETE FROM " . $table['departments'] . " WHERE deptid = " . $id;
   $sql->queryme($del);
}
} //end of department class


//Core Position Class
//Author: Nolanator
//Version: 1.0

class positions {

function next_ord($dep) {
	global $sql, $table;
	$next = "SELECT max(ord) AS max FROM " . $table['positions'] . " AS positions WHERE deptid = '$dep'";
	$resn = $sql->queryme($next);
	$ordn = $sql->sql_array($resn);
	return $ordn['max'] + 1;
}
} //End of positions class

class position {

var $pid;
var $name;
var $department;
var $desc;
var $senior;
var $enlist;
var $order;
var $repeat;
var $crewm;

function get($id) {
	global $sql, $table;
	$get = "SELECT * FROM " . $table['positions'] . " AS positions WHERE posid = '$id'";
	$res = $sql->queryme($get);
	$position = $sql->sql_array($res);
	$this->pid = $position['posid'];
	$this->name = $position['name'];
	$this->department = $position['deptid'];
	$this->desc = $position['description'];
	$this->senior = $position['senior'];
	$this->enlist = $position['enlisted'];
	$this->repeat = $position['repeating'];
	$this->order = $position['ord'];
	$this->crewm = $position['crewid'];
}

function new_pos() {
	global $sql, $table;
	$pos = new positions;
	$add = "INSERT INTO " . $table['positions'] . "(name, deptid, description, senior, enlisted, repeating, ord, crewid) VALUES ('" . $this->name . "', '" . $this->department . "', '" . $this->desc . "', '" . $this->senior . "', '" . $this->enlist . "', '" . $this->repeat . "', '" . $pos->next_ord($this->department) . "', '')";
	$sql->queryme($add);
}

function update() {
	global $sql, $table;
	$update = "UPDATE " . $table['positions'] . " SET name = '" . $this->name . "', deptid = '" . $this->department . "', description ='" . $this->desc . "', senior = '" . $this->senior . "', enlisted = '" . $this->enlist . "', repeating = '" . $this->repeat . "', ord = '" . $this->order . "', crewid = '" . $this->crewm . "' WHERE posid = '" . $this->pid . "'";
	$sql->queryme($update);
}

function del($id) {
	global $sql, $table;
	$del = "DELETE FROM " . $table['positions'] . " WHERE posid = '$id'";
	$sql->queryme($del);
}

function countrep() {
	global $sql, $table;
	$count = "SELECT posid FROM " . $table['positions'] . " AS positions WHERE name = '" . $this->name . "' AND deptid = '" . $this->department . "'";
	$cres = $sql->queryme($count);
	return $sql->sql_num_rows($cres);
}
} //end of position class


//Core Custom field class
//Author: Nolanator
//Version: 1.0

class cfields {

var $fid;
var $name;
var $type;
var $order;

function get($id) {
	global $sql, $table;
	$get = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds WHERE fid = '$id'";
	$result = $sql->queryme($get);
	$feild = $sql->sql_array($result);
	$this->fid = $feild['fid'];
	$this->name = $feild['name'];
	$this->type = $feild['type'];
	$this->order = $feild['ord'];
}

function newf() {
	global $sql, $table;
	$crew = new crew;
	$fc = new f_cont;
	$new = "INSERT INTO " . $table['customfields'] . "(name, type) VALUES('" . $this->name . "', '" . $this->type . "')";
	$sql->insquery($new);
	$nf = $sql->lastid;
		$crewup = "SELECT crewid FROM " . $table['crew'] . " AS crew";
		$crewres = $sql->queryme($crewup);
		while($crewid = $sql->sql_array($crewres)) {
			$fc->crewid = $crewid['crewid'];
			$fc->info = "";
			$fc->fldid = $nf;
			$fc->set();
		} //end for loop
}

function deletef($id) {
	global $sql, $table;
	$fldc = new f_cont;
	$del = "DELETE FROM " . $table['customfields'] . " WHERE fid = '$id'";
	$sql->queryme($del);
	$fldc->del_byfield($id);	
}

function update() {
	global $sql, $table;
	$upd = "UPDATE " . $table['customfields']. " SET name = '" . $this->name . "', type = '" . $this->type . "', ord = '" . $this->order ."' WHERE fid = '" . $this->fid . "'";
	$sql->queryme($upd);
}
} //end of custom fields class

class f_cont {

var $conid;
var $crewid;
var $info;
var $fldid;

function set() {
	global $sql, $table;
	$set = "INSERT INTO " . $table['biodata'] . "(fid, info, crewid) VALUES ('" . $this->fldid . "', '" . $this->info . "', '" . $this->crewid . "')";
	$sql->queryme($set);
}

function del_bycrewid($id) {
	global $sql, $table;
	$del = "DELETE FROM " . $table['biodata'] . " WHERE crewid = '$id'";
	$sql->queryme($del);
}

function del_byfield($fldid) {
	global $sql, $table;
	$del = "DELETE FROM " . $table['biodata'] . " WHERE fid = '$fldid'"; 
	$sql->queryme($del);
}

function change() {
	global $sql, $table;
	$change = "UPDATE " . $table['biodata'] . " SET info = '" . $this->info . "' WHERE fid = '" . $this->fldid . "' AND crewid = '" . $this->crewid . "'";
	$sql->queryme($change);
}
} //end of the field content class

class deptcss {
	var $sid;
	var $color;
	var $name;
	
function remove($id) {
	global $sql, $table;
	$del = "DELETE FROM " . $table['styles'] . " WHERE sid = '" . $id . "'";
	$sql->queryme($del);
}
function addnew() {
	global $sql, $table;
	$addq = "INSERT INTO " . $table['styles'] . "(sid, name, color) VALUES ('" . $this->sid. "', '" . $this->name . "', '" . $this->color . "')";
	$sql->queryme($addq);
}
function edit() {
	global $sql, $table;
	$editq = "UPDATE " . $table['styles'] . " SET name = '" . $this->name . "', color = '" . $this->color . "' WHERE sid = '" . $this->sid . "'";
	//echo $editq;
	$sql->queryme($editq);
}

function get($id) {
	global $sql, $table;
	$getq = "SELECT * FROM " . $table['styles'] . " WHERE sid = '" . $id . "'";
	$result = $sql->queryme($getq);
	$css = $sql->sql_array($result);
	$this->sid = $id;
	//echo $this->sid;
	$this->name = $css['name'];
	$this->color = $css['color'];
}

} //end Department Css class

class CoC {
	
	
} //end CoC class

class rank {

var $rid;
var $name;
var $url;
var $color;
var $rating;

function get($id) {
	global $sql, $table;
	$getrank = "SELECT * FROM " . $table['ranks'] . " WHERE rankid = '" . $id . "'";
	$result = $sql->queryme($getrank);
	$rank = $sql->sql_array($result);
	$this->rid = $rank['rankid'];
	$this->name = $rank['name'];
	$this->url = $rank['url'];
	$this->color = $rank['color'];
	$this->rating = $rank['rating'];
}
function update() {
	global $sql, $table;
	$updrank = "UPDATE " . $table['ranks'] . " SET name = '" . $this->name . "', url = '" . $this->url . "', color = '" . $this->color . "', rating = '" . $this->rating . "' WHERE rankid = '" . $this->rid . "'";
	$sql->queryme($updrank);
}
function delete($id) {
	global $sql, $table;
	$removerank = "DELETE FROM " . $table['ranks'] . " WHERE rankid = '" . $id . "' LIMIT 1";
	$sql->queryme($removerank);
}
function add() {
	global $sql, $table;
	$addrank = "INSERT INTO " . $table['ranks'] . "(name, url, color, rating) VALUES ('" . $this->name . "', '" . $this->url . "', '" . $this->color . "', '" . $this->rating . "')";
	$sql->queryme($addrank);
}
}

?>