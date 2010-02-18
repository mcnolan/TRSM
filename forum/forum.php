<?
$displayForum=1;
if($mode=='login' or $mode=='logout' or $action=='language2') { $displayForum=0; }
if($displayForum==1){
	$pagename = "Forums";
include "../header.php";
echo "<div class=\"forum\">";
	include ('./index.php');
echo "</div>";
include "../footer.php";
} else {
	include ('./index.php');
}
?>