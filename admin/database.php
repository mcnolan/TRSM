<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/database.php
| Build 1
| <Changes>
| 
| <Purpose>
| Database utility administration. Provided and powered by Document Juggler.
|
| <Access>
| Level 4 and Above.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/

if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }

	include_once "./class/djmain.php";
	include_once "./class/djadmin.php";
	include_once "./class/djfix.php";
	
	if ($_POST['create_root']) {
		$result = $manager->add_root($nm) ;
		$MSG = $result ? $result : 'Root created successfully' ;
		$manager = new manager ; unset($edit) ;
	} // if
	if ($_POST['add_node_full']) {
		$vals = explode('|',$HTTP_POST_VARS[edit]) ;
		if ($edit == 0 || ($place == 'after' && $vals[3] == 0)) {
			$result = $manager->add_root($node_name_full) ;
			$MSG = $result ? $result : 'Root created successfully' ;
			$manager = new manager ; 
		} // if
		else {
			$result = $manager->insert_node($manager->lib[$vals[0]], $vals, $node_name_full, $place == 'sub') ;
			$MSG = !is_array($result) ? $result : 'Page added successfully' ;
			$manager = new manager ; 
		} // else				
	} // if
	if ($delete) {
		$vals = explode('|',$delete) ;
		if ($delete != 0) {
			if ($vals[3] == 0) {
				$manager->delete_full_tree($vals[0]) ;				
				$MSG = 'Rubric deleted' ;
				$manager = new manager ; unset($edit) ;
			} // if
			else {
				$manager->delete_node($manager->lib[$vals[0]], $vals) ;
				$MSG = 'Page deleted' ;
				$manager = new manager ; unset($edit) ;
			} // else
		} // if
		else {
			$MSG = 'Cannot delete full catalogue tree' ;
		} // else
	} // if
	if (isset($_POST['position'])) {
		$manager->change_position($change) ;
		$MSG = 'Position changed' ;
		$manager = new manager ; unset($edit) ;
	} // if
	if (isset($_POST['update_cont'])) {		
		$manager->update_node_cont($edit,$cont,$nm) ;
		$MSG = 'Page updated' ;
		$manager = new manager ;
	} // if
?>
<html>
<head>
<title>Document Juggler Editor</title>
<style>
.highlighted {color: red;}
.disabled {color: silver;}
.tree_input {font-family: Tahoma;}
</style>
</head>
<body style="font-family: Tahoma; font-size: 8pt;" link="blue" alink="red" vlink="blue">

<?php 
	$add_link = '' ;
	if ($edit) {
		$vals = explode('|',$edit) ; 
		$add_link = $vals[3] ? '?r=' . $vals[0] . '&n=' . $vals[3] : '?r=' . $vals[0] ;
	} // if
?>

[<a href="<? echo $path . "admin.php?page=database.php"; ?>">Top</a>] [<a href="<? echo $path . "database.php"; ?>" target="_blank">preview</a>] <?=$MSG?' - <span style="color: red;"><b>'.$MSG.'</b></span>':''?>


<h1>Document Juggler 2 Editor</h1>

<form action="admin.php?page=database.php" method="post">
Create new rubric:<br>
<?=$manager->control_create_root()?>
</form>

<p>

<form action="admin.php?page=database.php">
Select page to edit:<br>
<?=$manager->control_lib_full_selected($edit)?>
</form>

<?php 
	if ($edit) {
?>

<form action="admin.php?page=database.php" method="post">
<?=$manager->control_add_node(1)?>
<input type="hidden" name="edit" value="<?=$edit?>">
</form>

<p>

<form action="admin.php?page=database.php" method="post">
<?=$manager->control_change_position($edit)?>
</form>

<p>

<form action="admin.php?page=database.php" method="post">
Update node content:<br>
<?=$manager->control_update_node($edit)?>
<input type="hidden" name="edit" value="<?=$edit?>">
</form>

<?php 
		} // if
?>

<hr>
<center>
<b>Powered By Document Juggler 2</b>, 2000-2002, DDT Studio, <a href="http://ddtstudio.de">http://ddtstudio.de</a>

</center>

</body>
</html>