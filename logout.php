<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: logout.php
| Build 2
| <Changes>
| Message Tweak
|
| <Purpose>
| Logout script. Destroy login cookies & redirect to site main.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
include_once "inc.inc";
setcookie($cookiename, "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
setcookie('logged', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
setcookie('admin', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
setcookie('crewid', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
setcookie('userl', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);

include "header.php";
?>
    <META HTTP-EQUIV="REFRESH" content="2;url=index.php">
    Logout Successfull...returning to <? echo $shipname; ?> index
<? include "footer.php"; ?>