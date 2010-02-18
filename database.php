<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: database.php
| Build 2
| <Changes>
| Slight Table tweak to match site alignments
|
| <Purpose>
| Pages to view the system database. Powered by Document Juggler 2
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/

$pagename = "Database";
include "header.php";
include "./class/djmain.php";

?>
<img src="<? echo $imagepath; ?>header-database.gif">

<table width="95%" align="center"><tr><td>
<!-- Document Juggler 2
Copyright (C) by Oleksandr Missa and Valentyn Stashko, 2000 - 2002, info@ddtstudio.de
All rights reserved -->

    <? if(!empty($_SERVER[QUERY_STRING])) { echo $DJ->get_rubricname(); } ?>

<p>

    <? echo $DJ->get_currentpath(); ?>

<p>

    <? echo $DJ->get_content(); ?>

<p>

    <? echo $DJ->get_links(); ?>

<p align="center">

    <? echo $DJ->get_catalogindex(1); ?>

<p>

    <? echo $DJ->get_credits(); ?>

</td></tr></table>
<?
include "footer.php";
?>