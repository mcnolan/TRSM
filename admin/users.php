<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/users.php
| Build 2
| <Changes>
| Css Revision
|
| <Purpose>
| User login administration. Change passwords, assign characters and assign userlevels to users.
|
| <Access>
| Level 5 only.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<br><br>
<table width="90%" align="center">
<tr>
    <td colspan="6" class="AuserTop">Login Accounts</td>
</tr>
<tr class="AuserHeader">
    <td>Username</td>
    <td>Reg. Date</td>
    <td>Userlevel</td>
    <td>Crew Member</td>
    <td>&nbsp;</td>
</tr>
<?
$getl = "SELECT minibb_users.user_id, minibb_users.userlevel, minibb_users.username, minibb_users.user_email, minibb_users.user_regdate, crew.first_n, crew.last_n, rank.name, crew.crewid FROM " . $table['users'] . " AS minibb_users LEFT JOIN " . $table['crew'] . " AS crew ON (minibb_users.crewid = crew.crewid) LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid) ORDER BY userlevel DESC";
//echo $get1;
$resl = $sql->queryme($getl);
while($accts = $sql->sql_array($resl)) {
?>
<tr class="AuserRows">
    <td><? echo $accts['username']; ?></td>
    <td><? echo date("j M Y", strtotime($accts['user_regdate'])); ?></td>
    <td><a href="javascript: var t=window.open('admin/forms.php?form=change_ul&uid=<? echo $accts['user_id']; ?>&ul=<? echo $accts['userlevel']; ?>','posPop','width=300,height=150,scrollbars=no')"><? echo $accts['userlevel']; ?></a></td>
    <td><a href="javascript: var t=window.open('admin/forms.php?form=usr_change&uid=<? echo $accts['user_id']; ?>','posPop','width=300,height=150,scrollbars=no')"><? if($accts['crewid'] <> NULL) { echo reformatData($accts['name'] . " " . $accts['first_n'] . " " . $accts['last_n']); } else { echo "No Character"; } ?></a></td>
    <td><a href="javascript: var t=window.open('admin/forms.php?form=change_pw&uid=<? echo $accts['user_id']; ?>','posPop','width=300,height=200,scrollbars=no')">Change Password</a> </td>
</tr>
<?
}
?>
</table>