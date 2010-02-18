<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: player_menu.php
| Build 1
| <Changes>
| 
| <Purpose>
| Display the Player menu
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
?>
<span class="menuHeading"><img src="<? echo $imagepath; ?>menu-crew.gif"></span><br>
<? if(is_crewmember()) { ?>
    <a href="<? echo $path; ?>change_bio.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Change Bio</a><br>
<? }
if($mod_forums) { ?>
    <a href="<? echo $path; ?>forum/forum.php?action=prefs"><img src="<? echo $imagepath; ?>dot.gif" border="0">Preferences</a><br>
<? } ?>
    <a href="<? echo $path; ?>logout.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Logoff</a><br>