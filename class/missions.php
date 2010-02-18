<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: class/missions.php
| Build 1
| <Changes>
| 
| <Purpose>
| Core Missions Class.
|
| TRSM1.0 is (c) Nolan 2003-2004, and is covered by the GPL License (See gpl.txt for more information)
*/

class mission {
    
    var $mid;
    var $name;
    var $desc;
    var $start;
    var $fin;
    
function get($id) {
    //This gets a requested mission. an input of 0 means get the current mission
    global $sql, $table;
    $query = "SELECT * FROM " . $table['missions'] . " AS missions";
    if($id == 0) {
        //$query .= " WHERE finish = '0' LIMIT 1";
        $query .= " WHERE finish = '0'";
    } else {
        $query .= " WHERE missionid = '$id'";
    }
    $res = $sql->queryme($query);
    $mission = $sql->sql_array($res);
    $this->mid = $mission['missionid'];
    $this->name = $mission['mname'];
    $this->desc = $mission['desc'];
    $this->start = $mission['start'];
    $this->fin = $mission['finish'];
    }

function update() {
    global $sql, $table;
    $upd = "UPDATE " . $table['missions'] . " SET mname = '" . $this->name . "', `desc` = '" . $this->desc . "', start = '" . $this->start . "', finish = '" . $this->fin . "' WHERE missionid = '" . $this->mid . "'";
    $sql->queryme($upd);
}

function add() {
    global $sql, $table;
    //Finish old mission
    $old = new mission;
    $old->get(0);
    $old->fin = time();
    $old->update();
    //Insert new mission
    $new = "INSERT INTO " . $table['missions'] . "(mname, `desc`, start) VALUES ('" . $this->name . "', '" . $this->desc . "', '" . $this->start . "')";
    $sql->insquery($new);
    
}

function delete($id) {
    global $sql, $table;
    $del = "DELETE FROM " . $table['missions'] . " WHERE missionid = '$id'";
    $sql->queryme($del);
}
}
    