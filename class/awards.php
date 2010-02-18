<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: class/awards.php
| Build 1
| <Changes>
| 
| <Purpose>
| Core Awards Class.
|
| TRSM1.0 is (c) Nolan 2003-2004, and is covered by the GPL License (See gpl.txt for more information)
*/

class awards {
    
    var $awdid;
    var $name;
    var $desc;
    var $auto;
    var $image;
    
    function create() {
        global $sql, $table;
        $query = "INSERT INTO " . $table['awards'] . " (name, `desc`, auto, image) VALUES ('" . $this->name . "', '" . $this->desc . "', '" . $this->auto . "', '" . $this->image . "')";
        $sql->insquery($query);
        $this->awdid = $sql->lastid;
    }
    
    function update() {
        global $sql, $table;
        $update = "UPDATE " . $table['awards'] . " SET name = '" . $this->name . "', `desc` = '" . $this->desc . "', auto = '" . $this->auto . "', image = '" . $this->image . "' WHERE awardid = '" . $this->awdid . "'";
        $sql->queryme($update);
    }
    function get($id) {
        global $sql, $table;
        $get = "SELECT * FROM " . $table['awards'] . " AS awards WHERE awardid = '$id'";
        $result = $sql->queryme($get);
        $awrd = $sql->sql_array($result);
        $this->awdid = $awrd['awardid'];
        $this->name = $awrd['name'];
        $this->desc = $awrd['desc'];
        $this->auto = $awrd['auto'];
        $this->image = $awrd['image'];
    }
    function destruct() {
        //This function takes an auto award string and rips it into its parts
        $type[1] = trim(strstr($this->auto," "));
        $new = str_replace($type[1],"",$this->auto);
        $new = str_replace("+","",$new);
        $type[2] = trim($new);
        return $type;
    }
    function delete($id) {
        global $sql, $table;
        $delete = "DELETE FROM " . $table['awards'] . " WHERE awardid = '$id'";
        $sql->queryme($delete);
    }
}

class awarded {    
    var $award;
    var $date;
    var $reason;
    var $crewm;
    var $cname;
    
function assign() {
    global $sql, $table;
    $query = "INSERT INTO " . $table['awarded'] . " (crewid, awardid, date, reason) VALUES ('" . $this->crewm . "', '" . $this->award . "', '" . time() . "', '" . $this->reason . "')";
    $sql->queryme($query);    
}

function removebycrew($crewid) {
    global $sql, $table;
    $remove_a = "DELETE FROM " . $table['awarded'] . " WHERE crewid = '$crewid'";
    $sql->queryme($remove_a);
}
function new_auto($awardid, $auto) {
    global $sql, $table;
    $cycle = "SELECT crewid FROM " . $table['crew'] . " AS crew";
    $res = $sql->queryme($cycle);
    while($crewm = $sql->sql_array($res)) {
        $insq = "INSERT INTO " . $table['awarded'] . "(crewid, awardid, date, reason) VALUES ('" . $crewm['crewid'] . "', '$awardid', '" . strtotime($auto) . "', 'Automatic Time Served Award')";
        $sql->queryme($insq);
    }
}
function crew_update($crewid) {
    global $sql, $table;
    $awdcyc = "SELECT awardid, auto FROM " . $table['awards'] . " AS awards WHERE auto <> ''";
    $rescyc = $sql->queryme($awdcyc);
    while($awards = $sql->sql_array($rescyc)) {
        $ins = "INSERT INTO " . $table['awarded'] . "(crewid, awardid, date, reason) VALUES ('$crewid', '" . $awards['awardid'] . "', '" . strtotime($awards['auto']) . "', 'Automatic Time Served Award')";
        $sql->queryme($ins);
    }
}
function update_ondel($id, $name) {
    global $sql, $table;
    $query = "UPDATE " . $table['awarded'] . " SET crewm = '$name', crewid = '0' WHERE crewid = '$id'";
    $sql->queryme($query);
}
}
?>