<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin.php
| Build 1
| <Changes>
| 
| <Purpose>
| Admin Interface - Admin forms are loaded ontop of this file.
|
| TRSM1.0 is (c) Nolan 2003-2004, and is covered by the GPL License (See gpl.txt for more information)
*/
$pagename = "Admin System";
include "header.php";

if(is_admin()) {
    define("CONSTANT_ADMIN",1);
    include "admin/" . $_GET['page'];
} else {
    echo "Access Denied, you do not have admin access to this site.";
}
include "footer.php";
?>