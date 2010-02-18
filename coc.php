<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.01
|
| File: coc.php
| Build 3
| <Changes>
| Changed Css
| New CoC system, feeding off crew table
|
| <Purpose>
| Display Chain Of Command
|
| TRSM1.0 is (c) Nolan 2003-2006, and is covered by the GPL Licence (See gpl.txt for more information)
*/

$pagename = "Chain Of Command";
include "header.php";
?>
<img src="<? echo $imagepath; ?>header-coc.gif">
<br><br>
<table align="center">
<?
$query_coc = "SELECT first_n, last_n, rank.name, rank.url FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE crew.active = '1' ORDER BY crew.coc ASC";
$res_coc = $sql->queryme($query_coc);
$count = 0;
while($coc = $sql->sql_array($res_coc)) {
    $count++;
?>
<tr>
    <td class="cocRows"><? echo $count; ?></td>
    <td><img src="<? echo $imagepath . "ranks/" . $coc['url']; ?>" alt="<? echo $coc['name']; ?>"></td>
    <td><? echo $coc['name'] . " " . reformatData($coc['first_n']) . " " . reformatData($coc['last_n']); ?></td>
</tr>
<?
}
?>
</table>
<?
include "footer.php";
?>