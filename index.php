<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: index.php
| Build 2
| <Changes>
| Css Changes
|
| <Purpose>
| Front page of SPMS, display news & current mission
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
$pagename = "Index";
include "header.php";
$crew = new crew;
?>
<table width="95%" align="center">
<tr>
    <td rowspan="2"><img src="<? echo $imagepath; ?>trsm_logo_large.gif"></td>
    <td class="indexName">Uss Testing</td>
</tr>
<tr>
    <td class="indexWelcome">Place your welcome message here.</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td colspan="2">
    <p>
    <img src="<? echo $imagepath; ?>news.gif">

        <table class="indexNews" cellspacing="0" width="100%">
<?
$newq = "SELECT * FROM " . $table['news'] . " AS news ORDER BY date DESC LIMIT 3";
$nwr = $sql->queryme($newq);
while($news = $sql->sql_array($nwr)) {
?>
        <tr>
            <td class="newsHeader"><? echo reformatData($news['title']); ?></td>
        </tr>
        <tr>   
            <td class="newsContent"><? echo reformatData(nl2br($news['content'])); ?></td>
        </tr>
        <tr>
            <td class="newsFooter">Submitted By: <? echo reformatData($news['poster']); ?> on the <? echo date("jS of F Y", $news['date']); ?></td>
        </tr>
<?
}
?>
        </table>
    </p>

    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>

<? if($mod_missions) { ?>

<tr>
    <td colspan="2">
    <img src="<? echo $imagepath; ?>mission.gif">

<?
//Get current mission
$cmis = new mission;
$cmis->get(0);
if($cmis->mid == '') {
    $cmis->name = "No Missions have been started";
    $cmis->desc = "Contact the Co for more information";
    $cmis->start = time();
}
?>
        <table class="indexMissions">
        <tr>
            <td class="missionName">"<? echo reformatData($cmis->name); ?>"</td>
        </tr>
        <tr>
            <td class="missionTitle">Synopsis</td>
        </tr>
        <tr>
            <td class="missionContent"><? echo reformatData($cmis->desc); ?></td>
        </tr>
        <tr>
            <td class="missionFooter">Started: <? echo date("j M Y", $cmis->start); ?></td>
        </tr>
        </table>
<!--End Current Mission Table-->
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>

<?

} //End missions Section

$new = $crew->newbies();
while($newb = $sql->sql_array($new)) {
    $new_characters .= "<a href=\"" . $path . "crewbio.php?cid=" . $newb['crewid'] . "\">" . $newb['name'] . " " . reformatData($newb['first_n']) . " " . reformatData($newb['last_n']) . "</a><br>";
}
if($new_characters != "") {
?>
<tr>
    <td class="indexSub"><img src="<? echo $imagepath; ?>newbies.gif"></td>
</tr>
<tr>
    <td class="indexNewbies">
        <? echo $new_characters; ?>
    </td>
</tr>
<?
}
?>

</table>

<? include "footer.php"; ?>