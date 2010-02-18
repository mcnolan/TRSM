<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: header.php
| Build 2
| <Changes>
| Css changes
|
| <Purpose>
| Header file for every page, makes sure styles and common functions are attached correctly, and provides theme
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
//Include all major files
include_once "inc.inc"; 
?>

<html>
	<head>
		<title><? echo $shipname . " :: " . $pagename; ?></title>

<? include "styles.php"; ?>

		<SCRIPT language="JavaScript">
		/*
		| Function to build an email address based on variables supplied by the calling link
		*/
		function unScramble(eMail1,eMail2,linkText,subjectText,statusText){
		var a,b,c,d,e;a=eMail1;c=linkText;b=eMail2.substring(0,eMail2.length-5);b=eMail2;
		if(subjectText!=""){d="?subject="+escape(subjectText);}else{d="";}
		if(statusText!=""){e=" onMouseOver=\"top.status=\'"+statusText+ 
		"\'\;return true\;\" onMouseOut=\"top.status=\'\'\;return true\;\"";}else{e="";}
		document.write("<A HREF=\"mai"+"lto:"+a+"@"+b+d+"\""+e+">");}
		</SCRIPT>
	</head>
<body>
<!--TRSM Version 1.02-->
<table width="800" align="center" valign="top">
<tr>
	<td colspan="2"><img src="<? echo $imagepath; ?>top_banner.jpg"></td>
</tr>
<tr>
	<td class="leftFrame">

	<table>
	<tr>
		<td class="menuHeading"><img src="<? echo $imagepath; ?>menu-top.gif"></td>
	</tr>
	<tr>
		<td valign="top">
		<span class="menus">
		<!-- Begin Main Menu -->
		<a href="<? echo $path; ?>index.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Index</a><br>
		<a href="<? echo $path; ?>manifest.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Manifest</a><br>
		<? if($mod_coc){ ?><a href="<? echo $path; ?>coc.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Coc</a><br><? } ?>
		<? if($mod_awards){ ?><a href="<? echo $path; ?>awards.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Awards</a><br><? } ?>
		<? if($mod_report){ ?><a href="<? echo $path; ?>posting.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Posting</a><br><? } ?>
		<? if($mod_missions){ ?><a href="<? echo $path; ?>missions.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Missions</a><br><? } ?>
		<? if($mod_specs){ ?><a href="<? echo $path; ?>specifications.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Specifications</a><br><? } ?>
		<? if($mod_database){ ?><a href="<? echo $path; ?>database.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Database</a><br><? } ?>
		<a href="<? echo $path; ?>news.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">News</a><br>
		<? if($mod_forums) { ?><a href="<? echo $path; ?>forum/forum.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Forum</a><br><? } ?>
		<a href="<? echo $path; ?>join.php"><img src="<? echo $imagepath; ?>dot.gif" border="0">Join</a><br>
<? 
if(is_loggedin()) {
	include_once "player_menu.php";
	if(is_admin()) {
		include_once "admin/admin_menu.php";
	}
} else {
	$pos = "side";
	include_once "login.php";
}
?>
		<!-- End Main Menu -->
		</span>

		</td>
	</tr>
	</table> 

	</td>

	<td class="rightFrame">
	<!--Start Center Page-->
	<table height="100%" width="100%" cellspacing="0">
	<!--
	<tr>
		<td class="pageName"><? echo $pagename; ?></td>
	</tr>
	-->
	<tr>
		<td valign="top">