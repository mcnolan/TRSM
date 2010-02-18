<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: posting.php
| Build 2
| <Changes>
| Css Change
| 
| <Purpose>
| Display the posting report. This summarises posts by player in three custom catergorys.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
$pagename = "Posting Report";
include "header.php";
$popup = 'width=600,height=200,scrollbars=yes';
?>
<img src="<? echo $imagepath; ?>header-posting.gif">
<br><br>
<table width="80%" align="center">
<tr class="postingHeader">
    <td>&nbsp;</td>
    <td colspan="4" align="center">Post Numbers</td>
    <td>&nbsp;</td>
</tr>

<tr class="postingHeader">
    <td>Crew Member</td>
    <td align="Center"><? echo $time_period1; ?></td>
    <td align="Center"><? echo $time_period2; ?></td>
    <td align="Center"><? echo $time_period3; ?></td>
    <td align="Center">Total</td>
    <td align="Center">Points</td>
</tr>
<?
$report = new report;
$crew = "SELECT crewid, loa, first_n, last_n, rank.name AS rankn FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE active = '1'";
$res = $sql->queryme($crew);
while($crew = $sql->sql_array($res)) {
    $report->generate($crew['crewid']);
?>
<tr class="postingRows">
    <td<? if($crew['loa'] == 1) { echo " class=\"onLOA\""; } ?>><? echo $crew['rankn'] . " " . reformatData($crew['first_n']) . " " . reformatData($crew['last_n']); ?></td>
    <td align="Center"><? if($report->cat1_posts != 0) { echo "<a href=\"javascript: var t=window.open('info.php?info=posts&tp=1&crewid=" . $crew['crewid'] . "','posPop','$popup')\">"; } echo $report->cat1_posts; ?></a></td>
    <td align="Center"><? if($report->cat2_posts != 0) { echo "<a href=\"javascript: var t=window.open('info.php?info=posts&tp=2&crewid=" . $crew['crewid'] . "','posPop','$popup')\">"; } echo $report->cat2_posts; ?></a></td>
    <td align="Center"><? if($report->cat3_posts != 0) { echo "<a href=\"javascript: var t=window.open('info.php?info=posts&tp=3&crewid=" . $crew['crewid'] . "','posPop','$popup')\">"; } echo $report->cat3_posts; ?></a></td>
    <td align="Center" class="postingEm"><? if($report->total != 0) { echo "<a href=\"javascript: var t=window.open('info.php?info=posts&tp=4&crewid=" . $crew['crewid'] . "','posPop','$popup')\">"; } echo $report->total; ?></td>
    <td align="Center" class="postingEm"><? echo $report->point_total; ?></td>
</tr>
<?
}
?>
</table>
<?
include "footer.php";
?>