<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.01
|
| File: change_bio.php
| Build 3
| <Changes>
| Changed Css
| Bug fix for globals off users
|
| <Purpose>
| Forms that allow a user to change his/her bio
|
| TRSM1.0 is (c) Nolan 2003-2006, and is covered by the GPL Licence (See gpl.txt for more information)
*/

$pagename = "Alter Your Bio";
include "header.php";

if($_GET['action'] <> "change") {
if(is_crewmember()) {
    $crew = new crew;
    $crew->get($_COOKIE['crewid']);
    ?>
    <img src="<? echo $imagepath; ?>header-altbio.gif"><br><br>
    <table width="90%" align="center">
    <tr>
        <td>
        <form action="change_bio.php?action=change" method="post">
    <?
    echo "<span class=\"altBioLabel\">Character Name:</span> " . $crew->rankn . " " . reformatData($crew->fn) . " " . reformatData($crew->ln) . "<br><br>";
    $getextras = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds LEFT JOIN " . $table['biodata'] . " AS feild_data ON(cust_feilds.fid = feild_data.fid) WHERE crewid = '" . $_COOKIE['crewid'] . "' ORDER BY ord ASC";
    $extras = $sql->queryme($getextras);
    while($fields = $sql->sql_array($extras)) {
        echo "<span class=\"altBioLabel\">";
        switch($fields['type']) {
            
        case "line" :
            echo reformatData($fields['name']) . ": <input type=\"text\" name = \"" . $fields['fid'] ."\" value = \"" . reformatData($fields['info']) . "\" size = \"$line_size\"><br>";
        break;
    
        case "box" :
            echo reformatData($fields['name']) . "<br> <textarea name=\"" . $fields['fid'] . "\" cols = \"$box_cols\" rows = \"$box_rows\">" . reformatData($fields['info']) . "</textarea><br><br>";
        break;
        
        case "line_small" :
            echo reformatData($fields['name']) . ": <input type=\"text\" name = \"" . $fields['fid'] ."\" value = \"" . reformatData($fields['info']) . "\" size = \"$linesmall_size\"><br>";
        break;
    
        case "line_med" :
            echo reformatData($fields['name']) . ": <input type=\"text\" name = \"" . $fields['fid'] ."\" value = \"" . reformatData($fields['info']) . "\" size = \"$linemed_size\"><br>";
        break;
    
        case "box_large" :
            echo reformatData($fields['name']) . "<br> <textarea name=\"" . $fields['fid'] . "\" cols = \"$boxlarge_cols\" rows = \"$boxlarge_rows\">" . reformatData($fields['info']) . "</textarea><br><br>";
        break;
        }
    }
    ?>
        <br><input type="submit" value="Change"> <input type="reset"></form>
        </td>
    </tr>
    </table>
    <?
} else {
    echo "This account does not have a bio associtated with it.";
}
} elseif($_GET['action'] == "change") {
    foreach($_POST as $pkey => $pval) {
        $updatestr = "UPDATE " . $table['biodata'] . " SET info = '" . dataCheck($pval) . "' WHERE fid = '$pkey' AND crewid = '" . $_COOKIE['crewid'] . "'";
        $sql->queryme($updatestr);
    }
    echo "Update Successful";
}
include "footer.php"; ?>