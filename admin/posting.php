<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/posting.php
| Build 2
| <Changes>
| Fixed slight error with globals_off handling
|
| <Purpose>
| Posting system administration. Add & Edit point values, add posts & generate a emailable report.
|
| <Access>
| Level 2 and above. (level 2 access to Add post only)
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }

if(@$_GET['action'] == 'post') {
    $notpts = array('crewm', 'subject', 'date', 'synop', 'mission', 'is_jp', 'jp_members');
    $points = 0;
    foreach($_POST as $key => $value) {
        if(!in_array($key, $notpts)) {
            $points += $value;
            $comments .= $key . "<br>";
        }
    }
    $inspost = "INSERT INTO " . $table['posts'] . "(subject, synop, missionid, crewid, date, comments, points) VALUES ('" . $_POST['subject'] . "', '" . $_POST['synop'] . "', '" . $_POST['mission'] . "', '" . $_POST['crewm'] . "', '" . strtotime($_POST['date']) . "', '$comments', '$points')";
    $sql->queryme($inspost);
    if($_POST['is_jp'] == 1) {
        foreach($_POST['jp_members'] as $value) {
            $insjp = "INSERT INTO " . $table['posts'] . "(subject, synop, missionid, crewid, date, comments, points) VALUES ('" . $_POST['subject'] . "', '" . $_POST['synop'] . "', '" . $_POST['mission'] . "', '" . $value . "', '" . strtotime($_POST['date']) . "', '$comments', '$points')";
            $sql->queryme($insjp);
        }
    }
    $message = "<br>Post Added!";
}

$mis = new mission;
$mis->get(0);


$result = $sql->queryme("SELECT crewid, first_n, last_n, rank.name AS rankn FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE active = '1'");
while($crewm = $sql->sql_array($result)) {
    $crewselect .= "<option value=\"" . $crewm['crewid'] . "\">" . reformatData($crewm['rankn'] . " " . $crewm['first_n'] . " " . $crewm['last_n']) . "</option>";
}

?>
<a href="javascript: var t=window.open('admin/forms.php?form=genrpt','posPop','width=300,height=400,scrollbars=no')">Generate Report</a>
<br>
<p class="ApostingMessage"><? echo @$message; ?></p><br>

<form action="admin.php?page=posting.php&action=post" method="post">
<table width="70%" align="center">
<tr>
    <td colspan="5" class="ApostingTitle">Add Post To Database</td>
</tr>
<tr>
    <td class="ApostingHeader">Poster</td>
    <td colspan="3"><select name="crewm">
        <? echo $crewselect; ?>
        </select>
    </td>
</tr>

<tr>
    <td class="ApostingHeader">Joint Post? <input type="checkbox" name="is_jp" value="1"></td>
    <td colspan="2"><select name="jp_members[]" multiple><? echo $crewselect; ?></select></td>
</tr>

<tr>
    <td class="ApostingHeader">Subject</td>
    <td><input type="text" name="subject" size="25"></td>
    <td class="ApostingHeader">Date</td>
    <td><input type="text" name="date" value="now" size="15"></td>
</tr>
<?
if($show_synop) {
?>
<tr>
    <td colspan="4"><textarea name="synop" cols="40" rows="3">Optional Synopsis</textarea></td>
</tr>
<?
}
?>
<tr>
    <td colspan="4"  class="ApostingHeader">
<?
$get_pt = "SELECT * FROM " . $table['points'] . " AS points";
$res_pt = $sql->queryme($get_pt);
$count = 0;
while($pts = $sql->sql_array($res_pt)) {
$count++;
?>
    <input type="checkbox" value="<? echo $pts['pchange']; ?>" name="<? echo $pts['pcomm']; ?>"> <? echo $pts['pcomm']; ?>&nbsp;&nbsp;
<?
if($count == 4) { $count = 0; echo "<br>"; }
}
?>
    </td>
<tr>
    <td colspan="4"><input type="submit" value="Add Post"> <input type="reset"></td>
</tr>
</table>

<input type="hidden" name="mission" value="<? echo $mis->mid; ?>">
</form>

<br><br>

<table width="40%" align="center">
<tr>
    <td colspan="3" class="ApostingTitle">Posting Points</td>
</tr>
<tr class="ApostingPtsHeader">
    <td>Point Comment</td>
    <td>Point Value</td>
    <td></td>
</tr>
<?
$pts = "SELECT * FROM " . $table['points'] . " AS points";
$respt = $sql->queryme($pts);
while($points = $sql->sql_array($respt)) {
?>
<tr class="ApostingPtsRows">
    <td><? echo reformatData($points['pcomm']); ?></td>
    <td><? echo $points['pchange']; ?></td>
    <td><a href="javascript: var t=window.open('admin/do.php?task=delpt&ptid=<? echo $points['ptid']; ?>','posPop','width=300,height=100,scrollbars=no')">Delete</a></td>
</tr>
<?    
}
?>
<tr>
    <td colspan="3"><a href="javascript: var t=window.open('admin/forms.php?form=addpt','posPop','width=300,height=150,scrollbars=no')">Add New Point Value</a></td>
</tr>
</table>