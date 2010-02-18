<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: styles.php
| Build 1
| <Changes>
| 
| <Purpose>
| Generate the required stylesheet for department colors.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
?>

            <link type="text/css" rel=stylesheet href="<? echo $path . $csspath; ?>trsm.css">
            <link type="text/css" rel=stylesheet href="<? echo $path . $csspath; ?>admin.css">
            <style type="text/css">
            <!--
<?
//Generate custom style sheet from styles table
$getsty = "SELECT * FROM " . $table['styles'];
$ressty = $sql->queryme($getsty);
while($style = $sql->sql_array($ressty)) {
?>
            .<? echo $style['name']; ?> {
        	background-color: <? echo $style['color']; ?>;
        	color: #000000;
        	font-size: 12pt;
                font-weight: bold;
        	vertical-align: middle;
        	font-family: tahoma;
                width: 100%;
            }
<?
}
?>
            -->
            </style>