<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: news.php
| Build 2
| <Changes>
| Changed Css
|
| <Purpose>
| News Archive display
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
$pagename = "News Archive";
include "header.php";
?>
<img src="<? echo $imagepath; ?>header-news.gif">
<?
$newq = "SELECT * FROM " . $table['news'] . " AS news ORDER BY date DESC";
$nwr = $sql->queryme($newq);
while($news = $sql->sql_array($nwr)) {
?>

<p>
    <table width="95%" align="center">
    <tr>
        <td class="newsHeader"><? echo reformatData($news['title']); ?></td>
    </tr>
    <tr>
        <td class="newsContent"><? echo reformatData(nl2br($news['content'])); ?></td>
    </tr>
    <tr>
        <td class="newsFooter">Submitted By: <? echo $news['poster']; ?> on the <? echo date("jS of F Y", $news['date']); ?></td>
    </tr>
    </table>
</p>
<?
}
include "footer.php";
?>