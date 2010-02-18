<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.02
|
| File: admin/forms.php
| Build 5
| <Changes>
| Css changes
| Added CoC form, to alter CoC manually
| Added forms to support rank editing
| Changed mission editing forms to support old missions
|
| <Purpose>
| Contains all Forms used by the admin system popups
|
| <Access>
| Dependant on parent form.
|
| TRSM1.02 is (c) Nolan 2003-2006, and is covered by the GPL Licence (See gpl.txt for more information)
*/

include_once "../settings.php";
include_once "../core.php";

if(is_admin()) {
   
?>
<html>
<head>
<title><? echo $shipname . " Admin System"; ?></title>
<?
$sql = new sql;
include_once "../styles.php";
?>
<script type="text/javascript">
function checkDetails(nun,npwd) {
      if(nun.value == "") {
         alert("New Username cannot be Blank");
         return false;
      } elseif(npwd.value == "") {
         alert("New Password Cannot be Blank");
         return false;
      }
   }
}
</script>
</head>
<body>
<?php

switch($_GET['form']) {

case "cbio" :
   $crew = new crew;
   $crew->get($_GET['crewid']);
   ?>
   <p class="ApopupTitle">
      Alter Bio for <? echo reformatData($crew->fn . " " . $crew->mn . " " . $crew->ln); ?>
   </p>
   <form action="do.php?task=cbio&crewid=<? echo $crew->cid; ?>" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" name="first" size="20" value="<? echo reformatData($crew->fn); ?>"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="text" size="20" name="middle" value="<? echo reformatData($crew->mn); ?>"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="text" size="20" name="last" value="<? echo reformatData($crew->ln); ?>"></td>
   </tr>
   <tr>
      <td>Species</td>
      <td><input type="text" size="20" name="race" value="<? echo reformatData($crew->species); ?>"></td>
   </tr>
   <tr>
      <td>Gender</td>
      <td><select name="gender"><option value="Male" <? if($crew->gender == "Male") { echo "selected"; }?>>Male</option><option value="Female" <? if($crew->gender == "Female") { echo "selected"; }?>>Female</option><option value="Other" <? if($crew->gender == "Other") { echo "selected"; }?>>Other</option></select></td>
   </tr>
   <tr>
      <td>Email</td>
      <td><input type="text" size="25" name="email" value="<? echo $crew->email; ?>"></td>
   </tr>
   <?
   $get_c = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds LEFT JOIN " . $table['biodata'] . " AS feild_data ON(feild_data.fid = cust_feilds.fid) WHERE feild_data.crewid = '" . $crew->cid . "' ORDER BY ord ASC";
   $res_c = $sql->queryme($get_c);
   while($custom = $sql->sql_array($res_c)) {
   ?>
   <tr>
      <td><? echo $custom['name']; ?></td>
      <td>
   <? switch($custom['type']) {
      
   case "line" :
      echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" value=\"" . reformatData($custom['info']) . "\" size=\"$line_size\">";
   break;
   
   case "box" :
      echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"$box_cols\" rows=\"$box_rows\">" . reformatData($custom['info']) . "</textarea>";
   break;
   
   case "line_small" :
      echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" value=\"" . reformatData($custom['info']) . "\" size=\"$linesmall_size\">";
   break;

   case "line_med" :
      echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" value=\"" . reformatData($custom['info']) . "\" size=\"$linemed_size\">";
   break;

   case "box_large" :
      echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"$boxlarge_cols\" rows=\"$boxlarge_rows\">" . reformatData($custom['info']) . "</textarea>";
   break;
   }
   ?>
      </td>
   </tr>
   <?
   }
   ?>
   <tr>
      <td>Date Joined</td>
      <td><input type="text" size="10" name="joined" value="<? echo date("j M Y", $crew->joined); ?>"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Edit"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "changerank" :
	$crew = new crew;
	$crew->get($_GET['crewid']);
	$ranklist = "SELECT * FROM " . $table['ranks'] . " AS rank ORDER BY color";
	$resrank = $sql->queryme($ranklist);
	?>
	<p class="ApopupTitle">
	Change Rank for <? echo reformatData($crew->fn . " " . $crew->ln); ?>
	</p>
	<form action="do.php?task=changerank&crewid=<? echo $_GET['crewid']; ?>" method="post">
	<p>
	<select name="newrank">
	<?
	while($rank = $sql->sql_array($resrank)) {
		if($rank['rankid'] == $crew->rnk) {
		?>
		<option value="<? echo $rank['rankid']; ?>" selected><? echo reformatData($rank['name']) . "(" . $rank['color'] . ")"; ?></option>
		<?
		} else {
		?>
		<option value="<? echo $rank['rankid']; ?>"><? echo reformatData($rank['name']) . "(" . $rank['color'] . ")"; ?></option>
		<?
		}
	}
		?>
	</select>
	</p>
           <?
      if($mod_records) {
      ?>
      <p>
      <? $rec = new record;
      $rec->display_form(true);
      ?>
      </p>
      <?
      }
      ?>
	<p>
	<input type="submit" value="change">
	</p>
	</form>
<?
break;

case "adddept" :
	?>
	<p class="ApopupTitle">
	Add New Department
	</p>
	<form action="do.php?task=adddep" method="post">
	<table class="ApopupTable">
	<tr>
		<td>Name</td>
		<td><input type="text" name="ndepname"></td>
	</tr>
	<tr>
		<td>Class</td>
		<td><select name="ndepclass">
                <?
                  $getdepc = "SELECT name FROM " . $table['styles'] . " AS styles";
                  $resdep = $sql->queryme($getdepc);
                  while($class = $sql->sql_array($resdep)) {
               ?>
               <option value="<? echo $class['name']; ?>"><? echo $class['name']; ?></option>
               <?
                  }
               ?>
                </select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Add"></td>
	</tr>
	</table>
	</form>
	<? 
break;

case "orddept" :
	?>
	<p class="ApopupTitle">
	Order Departments
	</p>
	<form action="do.php?task=orddept" method="post">
	<table class="ApopupTable">
	<tr>
		<td>Department Name</td>
		<td>Order</td>
	</tr>
	<?
	$getdepts = "SELECT * FROM " . $table['departments'] . " AS departments ORDER BY ord ASC";
	$resdepts = $sql->queryme($getdepts);
	while($depts = $sql->sql_array($resdepts)) {
	$max = $sql->sql_num_rows($resdepts);
	?>
	<tr class="ApopupSubHeader">
		<td><? echo reformatData($depts['name']); ?></td>
		<td><select name="<? echo $depts['deptid']; ?>"><? echo select_list($depts['ord'], $max); ?></select></td>
	</tr>
	<?
	}
	?>
	<tr>
		<td></td>
		<td><input type="submit" value="Order"></td>
	</tr>	
	</table>
	</form>
	<?
break;

case "changedp" :
	$dept = new department;
	$dept->get($_GET['deptid']);
	?>
        <p class="<? echo $dept->css; ?>">
        Alter <? echo $dept->name; ?> Department
        </p>
      <form action="do.php?task=updept" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Name</td>
         <td><input type="text" name="newname" value="<? echo reformatData($dept->name); ?>"></td>
      </tr>
      <tr>
         <td>Class</td>
         <td><select name="newclass"> value="<? echo $dept->css; ?>">
        <?
        $css = "SELECT name FROM " . $table['styles'] . " AS styles";
        $rescss = $sql->queryme($css);
        while($css = $sql->sql_array($rescss)) {
         if($css['name'] == $dept->css) {
        ?>
        <option value="<? echo $css['name']; ?>" selected><? echo $css['name']; ?></option>
        <?
        } else {
        ?>
        <option value="<? echo $css['name']; ?>"><? echo $css['name']; ?></option>
        <?
        }
        }
        ?>
        </select>
         </td>
      </tr>
      <tr>
         <td>&nbsp;<input type="hidden" name="did" value="<? echo $dept->depid; ?>"></td>
         <td><input type="submit" value="Edit"></td>
      </tr>
      </table>
      </form>
	<?
break;

case "addpos" :
	?>
        <p class="ApopupTitle">
        Add New Position
        </p>
	<form action="do.php?task=addpos" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Name</td>
         <td><input type="text" name="posname" size="20"></td>
      </tr>
      <tr>
         <td>Department</td>
         <td><select name="posdept">
	<?
	$getdepts = "SELECT * FROM " . $table['departments'] . " AS departments ORDER BY ord ASC";
	$resdepts = $sql->queryme($getdepts);
	while($dep = $sql->sql_array($resdepts)) {
	if($_GET['deptid'] == $dep['deptid']) {
	?>
	<option value="<? echo $dep['deptid']; ?>" selected><? echo reformatData($dep['name']); ?></option>
	<?
	} else {
	?>
	<option value="<? echo $dep['deptid']; ?>"><? echo reformatData($dep['name']); ?></option>	
	<?
	}
	}
	?>
	</select>
         </td>
      </tr>
      <tr>
         <td colspan="2"><textarea name="posdesc" cols="30" rows="3">Position Description</textarea></td>
      </tr>
      <tr>
         <td>Senior Staff <input type="checkbox" name="sen" value="1"></td>
         <td>Enlisted <input type="checkbox" name="enlist" value="1"></td>
      </tr>
      <tr>
         <td colspan="2">Repeating? <input type="checkbox" name="rept" value="1"></td>
      </tr>
      <tr>
         <td><input type="submit" value="Add"></td>
      </tr>
      </table>
      </form>
	<?
break;

case "changepos" :
	$post = new position;
	$post->get($_GET['posid']);
	?>
        <p class="ApopupTitle">
        Alter Position
        </p>
      <form action="do.php?task=editpos" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Name</td>
         <td><input type="text" name="newposn" value="<? echo reformatData($post->name); ?>" size="20"></td>
      </tr>
      <tr>
         <td>Department</td>
         <td><select name="newposd">
	<?
	$fetchdep = "SELECT * FROM " . $table['departments'] . " AS departments ORDER By ord ASC";
	$resdep = $sql->queryme($fetchdep);
	while($depart = $sql->sql_array($resdep)) {
	if($depart['deptid'] == $post->department) {
	?>
	<option value="<? echo $depart['deptid']; ?>" selected><? echo reformatData($depart['name']); ?></option>
	<?
	} else {
	?>
	<option value="<? echo $depart['deptid']; ?>"><? echo reformatData($depart['name']); ?></option>
	<?
	}
	}
	?>
         </select>
         </td>
      </tr>
      <tr>
         <td colspan="2"><textarea name="newposds" cols="30" rows="3"><? echo reformatData($post->desc); ?></textarea></td>
      </tr>
      <tr>
         <td>Senior <input type="checkbox" value="1" name="newsen"<? if($post->senior == 1) { echo " checked"; } ?>> </td>
         <td>Enlisted <input type="checkbox" value="1" name="newenl"<? if($post->enlist == 1) { echo " checked"; } ?>></td>
      </tr>
      <tr>
         <td colspan="2">Repeating? <input type="checkbox" value="1" name="newrep"<? if($post->repeat == 1) { echo " checked"; } ?>></td>
      </tr>
      <tr>
         <td>&nbsp;<input type="hidden" name="posid" value="<? echo $_GET['posid']; ?>"></td>
         <td><input type="submit" value="Edit"></td>
      </tr>
      </table>
      </form>
	<?
break;

case "orderpos" :
	$sql = new sql;
	$getpos = "SELECT name, ord, posid FROM " . $table['positions'] . " AS positions WHERE deptid = '" . $_GET['deptid'] . "'";
	$respos = $sql->queryme($getpos);
	?>
        <p class="ApopupTitle">
        Order Positions
        </p>
      <form action="do.php?task=orderpos" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Position name</td>
         <td>Order</td>
      </tr>
	<?
	while($pos = $sql->sql_array($respos)) {
	$max = $sql->sql_num_rows($respos);
	?>	
      <tr class="ApopupSubHeader">
         <td><? echo reformatData($pos['name']); ?></td>
         <td><select name="<? echo $pos['posid']; ?>">
		<? echo select_list($pos['ord'], $max);?>
         </select>
         </td>
      </tr>
	<?
	}
	?>
      <tr>
         <td><input type="submit" value="Order"></td>
      </tr>
      </table>
      </form>
	<?
break;

case "assign" :
	$pos = new position;
	$pos->get($_GET['posid']);
	?>
      <p class="ApopupTitle">
         Assign Crew To <? echo $pos->name; ?>
      </p>
      <form action="do.php?task=assign" method="post">
      <table class="ApopupTable">
      <tr>
         <td><select name="newcmem">
	
	<option value="0">Available</option>
	<?
	$getmembers = "SELECT crewid, first_n, last_n, name FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid)";
	$resmem = $sql->queryme($getmembers);
	while($mem = $sql->sql_array($resmem)) {
	?>
	<option value="<? echo $mem['crewid'] ?>"<? if($pos->crewm == $mem['crewid']) { echo " selected"; } ?>><? echo reformatData($mem['name'] . " ". $mem['first_n'] . " " . $mem['last_n']); ?></option>
	<?
	}
	?>
         </select>
         <input type="hidden" name="posiid" value="<? echo $_GET['posid']; ?>">
         </td>
      </tr>
      <tr>
         <td><input type="submit" value="Change"></td>
      </tr>
      </table>
      </form>
	<?
break;

case "newfield" :
	?>
      <p class="ApopupTitle">
        Add Bio Field
      </p>
      <form action="do.php?task=addfield" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Field Name</td>
         <td><input type="text" name="fname" size="20"></td>
      </tr>
      <tr>
         <td>Field Type</td>
         <td><select name="ftype">
            <option value="box">Text Box</option>
            <option value="box_large">Large Text Box</option>
            <option value="line">Single Line</option>
            <option value="line_small">Small Line(numbers)</option>
            <option value="line_med">Medium Line</option>
         </select>
         </td>
      </tr>
      <tr>
         <td><input type="submit" value="Add"></td>
      </tr>
      </table>
      </form>	
	<?
break;

case "ordfields" :
	?>
      <p class="ApopupTitle">
        Order Bio Fields
      </p>
      <form action="do.php?task=ordfields" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Field Name</td>
         <td>Order</td>
      </tr>
	<?
	$getf = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds ORDER BY ord ASC";
	$resf = $sql->queryme($getf);
	$maxf = $sql->sql_num_rows($resf);
	while($field = $sql->sql_array($resf)) {
	?>
      <tr class="ApopupSubHeader">
         <td><? echo reformatData($field['name']); ?></td>
         <td><select name="<? echo $field['fid']; ?>"><? echo select_list($field['ord'], $maxf); ?></select></td>
      </tr>
	<?
	}
	?>
      <tr>
         <td><input type="submit" value="Order"></td>
      </tr>	
      </table>
      </form>
        <?
break;

case "editfield" :
	$fd = new cfields;
	$fd->get($_GET['fid']);
	?>
      <p class="ApopupTitle">
         Edit <? echo $fd->name; ?> Field
      </p>
      <form action="do.php?task=editfield&fldid=<? echo $fd->fid; ?>" method="post">
      <table class="ApopupTable">
      <tr>
         <td>Field Name</td>
         <td><input name="newfn" type="text" value="<? echo reformatData($fd->name); ?>" size="20"></td>
      </tr>
      <tr>
         <td>Field Type</td>
         <td><select name="newft">
            <option value="box">Text box</option>
            <option value="line"<? if($fd->type == "line") { echo " selected"; } ?>>Single Line</option>	
            <option value="box_large"<? if($fd->type == "box_large") { echo " selected"; } ?>>Large Text Box</option>
            <option value="line_small"<? if($fd->type == "line_small") { echo " selected"; } ?>>Small Line(numbers)</option>
            <option value="line_med"<? if($fd->type == "line_med") { echo " selected"; } ?>>Medium Line</option>
         </select>
      </td>
      </tr>
      <tr>
         <td><input type="submit" value="Edit"></td>
      </tr>
      </table>
      </form>
	<?
break;

case "newcrewm" :
	?>
      <p class="ApopupTitle">
        Add New Crew Member
      </p>
   <form action="do.php?task=addcrew" method="post" onsubmit="return checkDetails(document.getElementById('nun'),document.getElementById('npwd')">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" name="nfirst" size="20" value="First"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="text" size="20" name="nmiddle" value="Middle"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="text" size="20" name="nlast" value="Last"></td>
   </tr>
   <tr>
      <td>Species</td>
      <td><input type="text" size="15" name="nrace"></td>
   </tr>
   <tr>
      <td>Gender</td>
      <td><select name="ngender"><option value="Male">Male</option><option value="Female" >Female</option><option value="Other">Other</option></select></td>
   </tr>
   <tr>
      <td>Email</td>
      <td><input type="text" size="25" name="nemail"></td>
   </tr>
   <tr>
      <td></td><td><input type="checkbox" name="mailopt" value="1"> Email this Application</td>
   </tr>
   <?
   $cust = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds ORDER BY ord ASC";
   $rescus = $sql->queryme($cust);
   while($custom = $sql->sql_array($rescus)) {
   ?>
   <tr>
      <td><? echo reformatData($custom['name']); ?></td>
      <td><? switch($custom['type']) {
   
   case "line" :
   	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"$line_size\">";
   break;
   
   case "box" :
   	echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"$box_cols\" rows=\"$box_rows\"></textarea>";
   break;

   case "box_large" :
   	echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"$boxlarge_cols\" rows=\"$boxlarge_rows\"></textarea>";
   break;

   case "line_small" :
   	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"$linesmall_size\">";
   break;

   case "line_med" :
   	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"$linemed_size\">";
   break;
   }
   ?>
      </td>
   <?
   }
   ?>
   <tr>
      <td colspan="2">Starting Details</td>
   </tr>
   <tr>
      <td>Rank</td>
      <td>
      <select name="rank">
      <?
      $rank = "SELECT * FROM " . $table['ranks'] . " AS rank ORDER BY color ASC";
      $resrank = $sql->queryme($rank);
      while($rank = $sql->sql_array($resrank)) {
      ?>
         <option value="<? echo $rank['rankid']; ?>"><? echo reformatData($rank['name']) . "(" . $rank['color'] . ")"; ?></option>
      <?
      }
      ?>
      </select>
      </td>
   </tr>
   <tr>
      <td>Status</td>
      <td><select name="status">
         <option value="1">Active</option>
         <option value="0" selected>Pending</option>
      </select>
      </td>
   <tr>
      <td colspan="2">New Login</td>
   </tr>
   <tr>
      <td class="ApopupSubHeader">Username:</td>
      <td><input type="text" size="15" name="nun" id="nun"></td>
   </tr>
   <tr>
      <td class="ApopupSubHeader">Password:</td>
      <td><input type="text" size="15" name="npwd" id="npwd"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Add"></td>
   </tr>
   </table>
   </form>
	<?
break;

case "addawd" :
   ?>
   <p class="ApopupTitle">Add Award</p>
   <i>Please note that once an award has been entered as an automatic award, the automatic value cannot be changed</i><br>
   
   <form action="do.php?task=addawd" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Award Name:</td>
      <td><input type="text" name="aname" size="30"></td>
   </tr>
   <tr>
      <td>Description</td>
      <td><textarea name="adesc" rows="4" cols="25"></textarea></td>
   </tr>
   <tr>
      <td>Image</td>
      <td><input type="text" name="aimg"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="checkbox" name="aauto" value="1"> Automatic Award After <input type="text" name="anum" size="4"> <select name="aperd"><option value="day">Days</option><option value="month">Months</option><option value="years">Years</option></select></td>
   </tr>
   <tr>
      <td><input type="submit" value="Add"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "editawd" :
   $awd = new awards;
   $awd->get($_GET['awdid']);
   ?>
   <p class="ApopupTitle">Edit Award</p>
   <form action="do.php?task=editawd&awd=<? echo $awd->awdid; ?>" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Award Name:</td>
      <td><input type="text" name="nname" value="<? echo reformatData($awd->name); ?>" size="30"></td>
   </tr>
   <tr>
      <td>Description</td>
      <td><textarea name="ndesc" rows="4" cols="25"><? echo reformatData($awd->desc); ?></textarea></td>
   </tr>
   <tr>
      <td>Image</td>
      <td><input type="text" name="nimg" value="<? echo $awd->image; ?>"></td>
   </tr>
   <tr>
      <td><input type="submit" value="Change"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "presentawd" :
   global $sql;
   ?>
   <p class="ApopupTitle">Present Award To Player</p>
   <form action="do.php?task=giveawd" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Crew Member</td>
      <td><select name="crewm"><?
         $getpla = "SELECT crew.crewid, crew.first_n, crew.last_n, rank.name FROM " . $table['crew'] . " AS crew INNER JOIN " . $table['ranks'] . " AS rank USING(rankid)";
         $respla = $sql->queryme($getpla);
         while($player = $sql->sql_array($respla)) {
            echo "<option value=\"" . $player['crewid'] . "\">" . reformatData($player['name'] . " " . $player['first_n'] . " " . $player['last_n']) . "</option>";
         } ?>
      </select>
      </td>
   </tr>
      <td>Award</td>
      <td><select name="award"><?
         $getawds = "SELECT awardid, name FROM " . $table['awards'] . " AS awards";
         $resawd = $sql->queryme($getawds);
         while($awd = $sql->sql_array($resawd)) {
            echo "<option value=\"" . $awd['awardid'] . "\">" . reformatData($awd['name']) . "</option>";
         }
         ?>
      </select>
      </td>
   </tr>
   <tr>
      <td>Reason</td>
      <td><input type="text" name="reason" size="30"></td>
   </tr>
   <?
   if($mod_records) {
      ?>
   <tr>
      <td colspan="2">
      <? $rec = new record;
      $rec->display_form(false);
      ?>
      </td>
   </tr>
      <?
      }
   ?>
   <tr>
      <td><input type="submit" value="Finish"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "addmission" :
   ?>
   <p class="ApopupTitle">Add Mission</p>
   <form action="do.php?task=addmis" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" name="nname" size="25"></td>
   </tr>
      <td colspan="2">Synopsis</td>
   </tr>
   <tr>
      <td colspan="2"><textarea name="synop" cols="30" rows="4"></textarea></td>
   </tr>
   <tr>
      <td colspan="2">Started on <input type="text" name="srt" value="now" size="15"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Add"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "editmission" :
   $mission = new mission;
   if($_GET['mid'] == "") {
      $mission->get(0);
   } else {
      $mission->get($_GET['mid']);
   }
   ?>
   <p class="ApopupTitle">Edit Current Mission</p>
   <form action="do.php?task=editmis" method="post">
   <input type="hidden" name="mid" value="<? echo $_GET['mid']; ?>">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" name="nname" value="<? echo reformatData($mission->name); ?>" size="25"></td>
   </tr>
      <td colspan="2" >Synopsis</td>
   </tr>
   <tr>
      <td colspan="2"><textarea name="ndesc" rows="4" cols="30"><? echo reformatData($mission->desc); ?></textarea></td>
   </tr>
   <tr>
      <td colspan="2">Started on <input type="text" name="nstart" value="<? echo date("j M Y", $mission->start); ?>" size="15"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Edit"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "newnews" :
   ?>
   <p class="ApopupTitle">Post News</p>
   <form action="do.php?task=addnews" method="post">
   <table class="ApopupTable">
   <tr>
      <td colspan="2">News Title</td>
   </tr>
   <tr>
      <td colspan="2"><input type="text" name="ntitle" size="25"></td>
   </tr>
   <tr>
      <td colspan="2">Story Content</td>
   </tr>
   <tr>
      <td colspan="2"><textarea name="ncont" rows="4" cols="30"></textarea></td>
   </tr>
   <tr>
      <td>Date</td>
      <td><input type="text" name="ndate" value="now" size="15"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Add News"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "editnews" :
   $new = new news;
   $new->fetch($_GET['nid']);
      ?>
      <p class="ApopupTitle">Edit news Story</p>
   <form action="do.php?task=editnews&nid=<? echo $new->nid; ?>" method="post">
   <table class="ApopupTable">
   <tr>
      <td colspan="2">Title</td>
   </tr>
   <tr>
      <td colspan="2"><input type="text" name="title" value="<? echo reformatData($new->title); ?>" size="25"></td>
   </tr>
   <tr>
      <td colspan="2">Story Content</td>
   </tr>
   <tr>
      <td colspan="2"><textarea name="content" rows="4" cols="30"><? echo reformatData($new->content); ?></textarea></td>
   </tr>
   <tr>
      <td>Poster</td>
      <td><input type="text" name="poster" value="<? echo reformatData($new->poster); ?>" size="25"></td>
   </tr>
   <tr>
      <td>Date</td>
      <td><input type="text" name="ndate" value="<? echo date("M j Y", $new->date); ?>" size="15"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Edit Story"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "usr_change" :
   $usr = new users;
   $usr->get($_GET['uid']);
   $crewq = "SELECT crewid, first_n, last_n, name FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid)";
   $rescrew = $sql->queryme($crewq);
   ?>
   <p class="ApopupTitle">Change Users Character</p>
   <form action="do.php?task=usr_change&uid=<? echo $_GET['uid']; ?>" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Account Assigned To</td>
   </tr>
   <tr>
      <td><select name="new_crewm">
         <option value="0">Unassigned</option>
   <?
   while($crews = $sql->sql_array($rescrew)) {
   ?>
         <option value="<? echo $crews['crewid']; ?>" <? if($crews['crewid'] == $usr->player) { echo "selected"; } ?>><? echo reformatData($crews['name'] . " " . $crews['first_n'] . " " . $crews['last_n']); ?></option>
   <?
   }
   ?>
      </select>
      </td>
   </tr>
   <tr>
      <td><input type="submit" value="Change"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "change_pw" :
   ?>
   <p class="ApopupTitle">Change Users Password</p>
   <i>Type the new password into both boxes below, no spaces or wierd characters!</i>
   
   <form action="do.php?task=changepw&uid=<? echo $_GET['uid']; ?>" method="post">
   <table class="ApopupTable">
   <tr>
      <td><input type="text" name="pw1"></td>
   </tr>
   <tr>
      <td><input type="text" name="pw2"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Change"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "new_stat" :
   ?>
   <p class="ApopupTitle">New Specification</p>
   <i><ul><li>To make a Heading, leave the Value box empty.<li>To indent a stat without a title, leave the Title box empty.<li>Check the Multiline box for multipule lines(duh)</ul>
   
   <form action="do.php?task=newstat" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Title</td>
      <td><input type="text" name="title" size="25"></td>
   </tr>
   <tr>
      <td>Value</td>
      <td><textarea name="value" rows="3" cols="25"></textarea></td>
   </tr>
   <tr>
      <td></td>
      <td><input type="checkbox" name="multil" value="1"> Multiline</td>
   </tr>
   <tr>
      <td><input type="submit" value="Add"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "editstat" :
   $stat = new specs;
   $stat->get($_GET['stid']);
   ?>
   <p class="ApopupTitle">Edit Specification</p>
   <i><ul><li>To make a Heading, leave the Value box empty.<li>To indent a stat without a title, leave the Title box empty.<li>Check the Multiline box for multipule lines(duh)</ul>
   <form action="do.php?task=editspec&sp=<? echo $_GET['stid']; ?>" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Title</td>
      <td><input type="text" name="ntitle" value="<? echo reformatData($stat->sname); ?>" size="25"></td>
   </tr>
   <tr>
      <td>Value</td>
      <td><textarea name="nvalue" rows="4" cols="25"><? echo reformatData($stat->svalue); ?></textarea></td>
   </tr>
   <tr>
      <td colspan="2"><input type="checkbox" name="nmultil" value="1" <? if($stat->smulti == 1) { echo "checked"; } ?>> Multiline</td>
   </tr>
   <tr>
      <td><input type="submit" value="Edit"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "newnpc" :
	?>
        <p class="ApopupTitle">
        Add New NPC
        </p>
   <form action="do.php?task=addcrew&type=npc" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" name="nfirst" size="20" value="First"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="text" size="20" name="nmiddle" value="Middle"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input type="text" size="20" name="nlast" value="Last"></td>
   </tr>
   <tr>
      <td>Species</td>
      <td><input type="text" size="15" name="nrace"></td>
   </tr>
   <tr>
      <td>Gender</td>
      <td><select name="ngender"><option value="Male">Male</option><option value="Female" >Female</option><option value="Other">Other</option></select></td>
   </tr>
   <?
   $cust = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds ORDER BY ord ASC";
   $rescus = $sql->queryme($cust);
   while($custom = $sql->sql_array($rescus)) {
   ?>
   <tr>
      <td><? echo reformatData($custom['name']); ?></td>
      <td><? switch($custom['type']) {
   
   case "line" :
   	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"20\">";
   break;
   
   case "box" :
   	echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"25\" rows=\"3\"></textarea>";
   break;

   case "box_large" :
   	echo "<textarea name=\"" . $custom['fid'] . "\" cols=\"35\" rows=\"6\"></textarea>";
   break;

   case "line_small" :
   	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"4\">";
   break;

   case "line_med" :
   	echo "<input type=\"text\" name=\"" . $custom['fid'] . "\" size=\"15\">";
   break;
   }
   ?>
      </td>
   <?
   }
   ?>
   <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Add"></td>
   </tr>
   </table>
   </form>
	<?
break;

case "change_ul" :
   switch($_GET['ul']) {
   case 0:
      $l0 = "selected";
   break;
   case 1:
      $l1 = "selected";
   break;
   case 2:
      $l2 = "selected";
   break;
   case 3:
      $l3 = "selected";
   break;
   case 4:
      $l4 = "selected";
   break;
   case 5:
      $l5 = "selected";
   break;
   }
   ?>
   <p class="ApopupTitle">Change Userlevel</p>
   <form action="do.php?task=change_ul" method="post">
   <table class="ApopupTable">
   <tr>
      <td><select name="new_level">
         <option value="0" <? echo $l0; ?>>Forum-ite (0)</option>
         <option value="1" <? echo $l1; ?>>Crew Member (1)</option>
         <option value="2" <? echo $l2; ?>>Basic Admin (2)</option>
         <option value="3" <? echo $l3; ?>>Privileged Admin (3)</option>
         <option value="4" <? echo $l4; ?>>Executive Officer/Advanced Admin (4)</option>
         <option value="5" <? echo $l5; ?>>Commanding Officer/Superadmin (5)</option>
      </select>
      </td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Change"></td>
   </tr>
   </table>
   <input type="hidden" name="uid" value="<? echo $_GET['uid']; ?>">
   </form>
   <?
break;

case "addpt" :
   ?>
   <p class="ApopupTitle">Add Point Value</p>
   <form action="do.php?task=addpt" method="post">
   <table class="ApopupTable">
   <tr>
      <td>Point Change</td>
      <td><input type="text" size="5" name="newpt"></td>
   </tr>
   <tr>
      <td>Comment</td>
      <td><input type="text" size="25" name="newcomm"></td>
   </tr>
   <tr>
      <td></td>
      <td><input type="submit" value="Add"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "emailer" :
   $lc = new crew;
   $cemails = "SELECT email, crewid, first_n, last_n, name FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE active <> '3'";
   $res = $sql->queryme($cemails);
   $lc->get($_COOKIE['crewid']);
?>
<p class="ApopupTitle">Email Utility</p>
<form action="do.php?task=emailer" method="post">
<table class="ApopupTable">
<tr>
   <td colspan="2">
   To: <select name="to">
      <option value="0">--Select Destination--</option>
<?
while($email = $sql->sql_array($res)) {
?>
      <option value="<? echo $email['crewid']; ?>"><? echo reformatData($email['name'] . " " . $email['first_n'] . " " . $email['last_n']) . " (" . $email['email'] . ")"; ?></option>
<?
}
?>
      <option value="all">Send To All Active Crew</option>
      <option value="ss">Send To All Marked As Senior Staff</option>
   </select>
   </td>
</tr>
<tr>
   <td>From: <input type="text" name="from" value="<? echo $lc->fname(); ?>" size="30"></td>
</tr>
<tr>
   <td>Subject: <input type="text" name="subject" value="[<? echo $shipname; ?>]" size="30"></td>
</tr>
<tr>
   <td colspan="2">
   <textarea name="message" rows="5" cols="50">Type your message here</textarea>
   </td>
</tr>
<tr>
   <td colspan="2">
   <input type="hidden" name="frome" value="<? echo $lc->email; ?>">
   <input type="submit" value="Send Now"> <input type="reset">
   </td>
</tr>
</table>
</form>
<?
break;

case "addrec" :
?>
<p class="ApopupTitle">Add Custom Record</p>
<form action="do.php?task=addrec" method="post">
<table class="ApopupTable">
<tr>
   <td>Player</td>
   <td><select name="player">
<?
$playersq = "SELECT * FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid) WHERE active = '1'";
$respla = $sql->queryme($playersq);
while($pla = $sql->sql_array($respla)) {
?>
      <option value="<? echo $pla['crewid']; ?>"><? echo reformatData($pla['name'] . " " . $pla['first_n'] . " " . $pla['last_n']); ?></option>
<?
}
?>
   </select>
   </td>
</tr>
<tr>
   <td>Comment</td>
   <td><input type="text" name="comment" size="30"></td>
</tr>
<tr>
   <td>Reason</td>
   <td><input type="text" name="reason" size="30"></td>
</tr>
<tr>
   <td>Points</td>
   <td><input type="text" name="pts" size="5"></td>
</tr>
<tr>
   <td></td>
   <td><input type="submit" value="Add"></td>
</table>
</form>
<?
break;

case "genrpt" :
   ?>
   <p class="ApopupTitle">Generate Posting Report</p>
   <form action="do.php?task=genrpt" method="post">
   <table class="ApopupTable">
   <tr>
      <td colspan="2">Include these time periods</td>
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2"><input type="checkbox" name="tp1" value="1" checked> <? echo $time_period1; ?></td>
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2"><input type="checkbox" name="tp2" value="1" checked> <? echo $time_period2; ?></td>
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2"><input type="checkbox" name="tp3" value="1" checked> <? echo $time_period3; ?></td>
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2"><input type="checkbox" name="all" value="1" checked> All posts</td>
   </tr>
   <tr>
      <td colspan="2">Email Copies To</td>
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2"><input type="checkbox" name="email_me" value="1" checked> Me</td>
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2">Others (comma seperated list)<br><input type="text" size="30" name="email_other"> </td> 
   </tr>
   <tr class="ApopupSubHeader">
      <td colspan="2"><input type="checkbox" name="points" value="1"> Include Current Point Totals?</td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Generate And Send"></td>
   </tr>
   </table>
   </form>
   <?
break;

case "deptCss" :
   ?>
   <p class="ApopupTitle">Edit Department Colours</p>
   <table class="ApopupTable">
   <?
   $querycss = "SELECT * FROM " . $table['styles'];
   $resCss = $sql->queryme($querycss);
   while($CssList = $sql->sql_array($resCss)) {
   ?>
   <tr class="ApopupSubHeader">
      <td class="<? echo $CssList['name']; ?>"><? echo $CssList['name']; ?></td>
      <td><a href="forms.php?form=editCss&id=<? echo $CssList['sid']; ?>">Edit</a></td>
      <td><a href="do.php?task=removeCss&id=<? echo $CssList['sid']; ?>">Delete</a></td>
   </tr>
   <?
   }
   ?>
   <tr>
      <td colspan="3"><a href="forms.php?form=addCss">Add New</a></td>
   </tr>
   </table>
   <?
break;

case "addCss" :
   ?>
   <p class="ApopupTitle">Add New Department Colour</p>
   <form method="post" action="do.php?task=addCss">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" size="20" name="cssName"></td>
   </tr>
   <tr>
      <td>Color</td>
      <td><input type="text" name="color" size="9" maxlength="7"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" value="Add"></td>
   </tr>
   </table>
   
   </form>
   
   <?
break;
case "editCss" :
   $styles = new deptcss;
   $styles->get($_GET['id']);
   ?>
   <p class="ApopupTitle">Edit Department Colour</p>
   <form method="post" action="do.php?task=editCss">
   <table class="ApopupTable">
   <tr>
      <td>Name</td>
      <td><input type="text" size="20" name="newName" value = "<? echo $styles->name; ?>"></td>
   </tr>
   <tr>
      <td>Color</td>
      <td><input type="text" name="newColor" size="9" maxlength="7" value="<? echo $styles->color; ?>"></td>
   </tr>
   <tr>
      <td colspan="2"><input type="hidden" name="cid" value="<? echo $_GET['id']; ?>"><input type="submit" value="Edit"></td>
   </tr>
   </table>
   
   </form>
   <?
break;
case "coc" :
   $getcoc = "SELECT crewid, first_n, last_n, rank.name, rank.url, coc FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(crew.rankid = rank.rankid) WHERE crew.active = '1' ORDER BY crew.coc ASC";
   $result = $sql->queryme($getcoc);
   ?>
   <p class="ApopupTitle">Manually Change Coc</p>
   <form method="post" action="do.php?task=editcoc">
   <table>
   <?
   $count = $sql->sql_num_rows($result);
   while($coc = $sql->sql_array($result)) {
   ?>
   <tr>
      <td><select name="<? echo $coc['crewid']; ?>"><? echo select_list($coc['coc'],$count); ?></select></td>
      <td><img src="<? echo $path . $rpath . $coc['url']; ?>" alt="<? echo $coc['name']; ?>"></td>
      <td><? echo $coc['name'] . " " . $coc['first_n'] . " " . $coc['last_n']; ?></td>
   </tr>
   <?
   }
   ?>
   <tr>
      <td colspan="3"><br><input type="submit" value="Change"></td>
   </tr>
   </table>
   </form>
   <?
break;
case "ranksmain" :
      $rank = new rank();
      switch(@$_GET['action']) {
      case "del" :
            $rank->delete($_GET['id']);
            $message = "Rank Deleted";
      break;
      case "add" :
            $rank->name = $_POST['rankName'];
            $rank->url = $_POST['rankPath'];
            $rank->color = $_POST['rankColor'];
            $rank->add();
            $message = $_POST['rankName'] . " (" . $_POST['rankColor'] . ") Added";
      break;
      case "edit" :
            $rank->get($_GET['id']);
            $rank->name = $_POST['rankName'];
            $rank->url = $_POST['rankPath'];
            $rank->color = $_POST['rankColor'];
            $rank->update();
            $message = $_POST['rankName'] . " (" . $_POST['rankColor'] . ") Edited";
      break;
      }
      unset($rank);
      global $sql, $table;
      $getrnkclr = "SELECT DISTINCT color from " . $table['ranks'] . " WHERE color != ''";
      $resultc = $sql->queryme($getrnkclr);
      ?>
      <p class="ApopupTitle">Rank Set Alterations</p>
      <? echo $message; ?>
      <table>
      <tr>
            <td colspan="5" class="ApopupHeader">Choose Color To Edit or <a href="forms.php?form=addRank">Add New Rank</a><br><br></td>
      </tr>
      <?
      $count = 0;
      echo "<tr>";
      while($rankc = $sql->sql_array($resultc)) {
      ?>
            <td bgcolor="<? echo $rankc['color']; ?>" align="center" width="25%"><a href="forms.php?form=displaycolor&color=<? echo $rankc['color']; ?>"><? echo $rankc['color']; ?></a></td> 
      <?
      $count++;
            if($count == 4) {
                  echo "</tr><tr>";
                  $count = 0;
            }
      }
      ?>
      </tr>
      </table>
      <?
break;
case "displaycolor" :
      global $sql, $table;
      $ranksq = "SELECT * FROM " . $table['ranks'] . " WHERE color = '" . $_GET['color'] . "' ORDER BY rating ASC";
      $result = $sql->queryme($ranksq);
      ?>
      <p class="ApopupTitle">Ranks for color '<? echo $_GET['color']; ?>'</p>
      <a href="forms.php?form=ranksmain">&lt; Back</a>
      <table>
      <tr>
            <td colspan="3"><a href="forms.php?form=addRank&color=<? echo $_GET['color']; ?>">Add New Rank Here</a></td>
      </tr>
      <?
      while($rank = $sql->sql_array($result)) {
      ?>
      <tr>
            <td> <a href="forms.php?form=editRank&rankid=<? echo $rank['rankid']; ?>">Edit</a> | <a href="forms.php?form=deleteRank&rankid=<? echo $rank['rankid']; ?>">Delete</a> </td>
            <td><img src="<? echo $path . $rpath . $rank['url']; ?>" alt="<? echo $rank['name']; ?>"></td>
            <td><? echo $rank['name']; ?></td>
      </tr>
      <?
      }
      ?>
      </table>
      <?
break;
case "addRank" :
      ?>
      <p class="ApopupTitle">Add New Rank</p>
      <form method="post" action="forms.php?form=ranksmain&action=add">
      <table class="ApopupTable">
      <tr>
            <td>Rank Name</td>
            <td><input type="text" name="rankName"></td>
      </tr>
      <tr>
            <td>Image Path</td>
            <td><input type="text" name="rankPath"></td>
      </tr>
      <tr>
            <td>Color</td>
            <td><input type="text" value="<? echo $_GET['color']; ?>" name="rankColor"></td>
      </tr>
      <tr>
            <td><input type="submit" value="Add Rank"></td>
      </tr>
      </table>
      </form>
      <?
break;
case "editRank" :
      global $sql,$table;
      $getRank = "SELECT * FROM " . $table['ranks'] . " WHERE rankid = '" . $_GET['rankid'] . "'";
      $resRank = $sql->queryme($getRank);
      $rank = $sql->sql_array($resRank);
      ?>
      <p class="ApopupTitle">Edit Rank</p>
      <form method="post" action="forms.php?form=ranksmain&action=edit&id=<? echo $_GET['rankid']; ?>">
      <table class="ApopupTable">
      <tr>
            <td>Rank Name</td>
            <td><input type="text" name="rankName" value="<? echo $rank['name']; ?>"></td>
      </tr>
      <tr>
            <td>Image Path</td>
            <td><input type="text" name="rankPath" value="<? echo $rank['url']; ?>"></td>
      </tr>
      <tr>
            <td>Color</td>
            <td><input type="text" value="<? echo $rank['color']; ?>" name="rankColor"></td>
      </tr>
      <tr>
            <td><input type="submit" value="Edit Rank"></td>
      </tr>
      </table>
      </form>
      <?
break;
case "deleteRank" :
      ?>
      <p class="ApopupTitle">Are you sure you wish to remove this Rank?</p>
      <a href="forms.php?form=ranksmain">No</a> | <a href="forms.php?form=ranksmain&action=del&id=<? echo $_GET['rankid']; ?>">Yes</a>
      <?
break;
case "importRanks" :
      ?>
      <p class="ApopupTitle">Import Rank Set</p>
      <form method="post" action="do.php?task=setImport">
      <table>
      <tr>
            <td>Select file to import</td>
            <td><select name="importFile"></select></td>
      </tr>
      <tr>
      <td colspan="2"><input type="submit" value="Import"></td>
      </tr>
      </table>
      </form>
      <?
break;
case "exportRanks" :
      ?>
      <p class="ApopupTitle">Export Rank Set</p>
      <form method="post" action="do.php?task=setExport">
      <table>
      <tr>
            <td>File to export to</td>
            <td><input type="text" name="exportFile"></td>
      </tr>
      <tr>
      <td colspan="2"><input type="submit" value="Export"></td>
      </tr>
      </table>
      </form>
      <?
break;
}
?>
</body>
</html>
<?
} else {
	echo "Access Denied, you do not have admin access to this site.";
	die();
}
?>