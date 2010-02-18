<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: class/record.php
| Build 2
| <Changes>
| Changed Css Output
|
| <Purpose>
| Core Service Records Class.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
class record {
    
    //Layout, obsolete
    var $crewid;
    var $pchange = 0;
    var $comment;
    var $date;
    var $admin;
    
    function display_form($display = true) {
        global $mod_report, $table;
        if($display) {
        ?>
        <span class="ApopupTitle">Record Entry</span><br><br>
        <span class="ApopupHeader">Reason: <input type="text" name="record_reason" size="30"></span><br>
        <?
        }
        if($mod_report) {
        ?>
        <span class="ApopupHeader">Point Change: <input type="text" name="record_change" size="5"></span><br>
        <?
        }
    }
    
    function make($crewid,$pchange,$comment,$reason) {
        global $sql, $table;
        $make = "INSERT INTO " . $table['records'] . "(crewid, pchange, comment, date, admin, reason) VALUES ('$crewid', '$pchange', '$comment', '" . time() . "', '" . $_COOKIE['crewid'] . "', '$reason')";
        $sql->queryme($make);
    }
    function fetch_pts($crewid) {
        global $sql, $table;
        $fetch = "SELECT SUM(pchange) FROM " . $table['records'] . " AS record WHERE crewid = '$crewid'";
        $res = $sql->queryme($fetch);
        $row = $sql->sql_row($res);
        return $row[0];
    }
    function deletebycrew($crewid) {
        global $sql, $table;
        $delete = "DELETE FROM " . $table['records'] . " WHERE crewid = '$crewid'";
        $sql->queryme($delete);
    }
}

?>