<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: template.php
| Build 1
| <Changes>
| 
| <Purpose>
| Display any custom file within the set header and footer pages
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
include "header.php";
$file = "templates/" . $_GET['file'] . ".html";
if(file_exists($file)) {
    if(is_readable($file)) {
        include_once($file);
    } else {
        echo "could not read selected file (" . $file . ".html)";
    }
} else {
    echo "$file does not exist ";
}
//echo sincewhen('1074265992');
include "footer.php";
?>