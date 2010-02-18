<?
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
| Display the Administration menu
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
?>
<span class="menuHeading"><img src="<? echo $imagepath; ?>menu-admin.gif"></span><br>
<? if(has_rights(4)) { ?><a href="<? echo $path; ?>admin.php?page=crew.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Crew</a><br><? } ?>
<? if(has_rights(4)) { ?><a href="<? echo $path; ?>admin.php?page=positions.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Positions</a><br><? } ?>
<? if(has_rights(5)) { ?><a href="<? echo $path; ?>admin.php?page=custom.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Custom Bios</a><br><? } ?>
<? if(has_rights(4) && $mod_awards) { ?><a href="<? echo $path; ?>admin.php?page=awards.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Awards</a><br><? } ?>
<? if(has_rights(2) && $mod_report) { ?><a href="<? echo $path; ?>admin.php?page=posting.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Posting</a><br><? } ?>
<? if(has_rights(4) && $mod_missions) { ?><a href="<? echo $path; ?>admin.php?page=missions.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Missions</a><br><? } ?>
<? if(has_rights(2)) { ?><a href="<? echo $path; ?>admin.php?page=news.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">News</a><br><? } ?>
<? if(has_rights(5)) { ?><a href="<? echo $path; ?>admin.php?page=users.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Users</a><br><? } ?>
<? if(has_rights(5) && $mod_specs) { ?><a href="<? echo $path; ?>admin.php?page=specs.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Specs</a><br><? } ?>
<? if(has_rights(4) && $mod_database) { ?><a href="<? echo $path; ?>admin.php?page=database.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Database</a><br><? } ?>
<? if(has_rights(4) && $mod_records) { ?><a href="<? echo $path; ?>admin.php?page=records.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Records</a><br><? } ?>