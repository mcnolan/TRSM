<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.02
|
| File: admin/do.php
| Build 5
| <Changes>
| Css Changes
| New functions to manually change the CoC
| New functions to edit and add ranks
| Changed mission editing to include old missions
|
| <Purpose>
| Central task performer for administration system.
|
| <Access>
| Dependant on parent page.
|
| TRSM1.02 is (c) Nolan 2003-2006, and is covered by the GPL License (See gpl.txt for more information)
*/

include "../settings.php";
include "../core.php";
$sql = new sql;
if($_COOKIE['admin'] == 'rar') {

switch($_GET['task']) {

case "tgactive" :
	$crm = new crew;
	$crm->get($_GET['crewid']);
	$crm->tgactive();
	$ref = "crew";
break;

case "tgloa" :
	$crm = new crew;
	$crm->get($_GET['crewid']);
	$crm->tgloa();
	$ref = "crew";
break;

case "changerank" :
	global $mod_records;
   $crm = new crew;
   $crm->get($_GET['crewid']);
   $oldrnk = $crm->rankn;
   $crm->rnk = $_POST['newrank'];
   $crm->update();
   if($mod_records) {
	$record = new record;
	$crm->get($_GET['crewid']);
	$comment = "Rank change from " . $oldrnk . " to " . $crm->rankn;
	$record->make($_GET['crewid'],$_POST['record_change'],$comment,$_POST['record_reason']);
   }
   $ref = "crew";
break;

case "cbio" :
	$crm = new crew;
	$crm->get($_GET['crewid']);
	foreach($_POST as $key => $value) { 
		switch($key) {
			case "first" :
			$crm->fn = dataCheck($value);
			break;
			case "middle" :
			$crm->mn = dataCheck($value);
			break;
			case "last" :
			$crm->ln = dataCheck($value);
			break;
			case "race" :
			$crm->species = dataCheck($value);
			break;
			case "gender" :
			$crm->gender = dataCheck($value);
			break;
			case "email" :
			$crm->email = $value;
			break;
			case "joined" :
			$crm->joined = strtotime($value);
			break;
			default:
			$fc = new f_cont;
			$fc->info = dataCheck($value);
			$fc->crewid = $_GET['crewid'];
			$fc->fldid = $key;
			$fc->change();
			break;
		}		
	}
   $crm->update();
   $ref = "crew";
break;

case "addcrew" :
	$crew = new crew;
	$lg = new mbblogin;
	$awd = new awarded;
	$crew->fn = dataCheck($_POST['nfirst']);
	$crew->mn = dataCheck($_POST['nmiddle']);
	$crew->ln = dataCheck($_POST['nlast']);
	$crew->species = dataCheck($_POST['nrace']);
	$crew->gender = $_POST['ngender'];
	$crew->joined = time();
	$crew->rnk = 1;
	if($_GET['type'] <> "npc") {
		$crew->email = $_POST['nemail'];
		$crew->active = $_POST['status'];
		$crew->rnk = $_POST['rank'];
	} else {
		$crew->active = 3;
		$crew->npc = true;
	}
	$crew->add();
	if($_GET['type'] <> "npc") {
		$lg->addnew($_POST['nun'], $_POST['npwd'], $crew->cid, $crew->email);
	}
	foreach($_POST as $ckey => $cinfo) {
		switch($ckey) {
		case "nfirst" :
		break;
		case "nmiddle" :
		break;
		case "nlast" :
		break;
		case "nrace" :
		break;
		case "ngender" :
		break;
		case "nemail" :
		break;
		case "nun" :
		break;
		case "npwd" :
		break;
		case "status":
		break;
		case "rank":
		break;
		case "mailopt":
		break;
		default:
			$addition = "INSERT INTO " . $table['biodata'] . "(fid, info, crewid) VALUES ('$ckey', '" . dataCheck($cinfo) . "', '" . $crew->cid . "')";
			$sql->queryme($addition);
		break;
		}
	}
	if($mod_awards && $_GET['type'] <> "npc") {
	$awd->crew_update($crew->cid);
	}
	if($_POST['mailopt'] == 1) {
		$message .= "Your character has been added to the roster of the $shipname\n";
		$message .= "The following information has been entered:\n\n";
		$message .= $crew->toString();
		$message .= "\n\n your login Details:\n Username: " . $_POST['unun'] . "\n Password: " . $_POST['npwd'] . "\n\n";
		$message .= "Go to the site at " . $path . " and login to change your bio";
		
		mail($crew->email,"Your Bio for $shipname",$message,"From: $shipname TRSM Engine");
	}
	$ref = "crew";
break;

case "deletecrew" :
	$crew = new crew;
	$crew->del($_GET['crewid']);
	$ref = "crew";
break;

case "adddep" :
	$dep = new department;
	$dep->name = dataCheck($_POST['ndepname']);
	$dep->css = $_POST['ndepclass'];
	$dep->addnew();
	$ref = "positions";
break;

case "orddept" :
	foreach($_POST as $key => $value) {
		$query = "UPDATE " . $table['departments'] . " SET ord = '$value' WHERE deptid = '$key'";
		$sql->queryme($query);
	}
	$ref = "positions";
break;

case "deldept" :
	$dep = new department;
	$dep->del($_GET['deptid']);
	$ref = "positions";
break;
/*
case "editdept" :
	$dept = new department;
	$dept->get($_POST['did']);
	$dept->name = dataCheck($_POST['newname']);
	$dept->css = $_POST['newcss'];
	$dept->update();
	$ref = "positions";
break;
*/
case "delpos" :
	$pos = new position;
	$pos->del($_GET['posid']);
	$ref = "positions";
break;

case "updept" :
	$dep = new department;
	$dep->get($_POST['did']);
	$dep->name = dataCheck($_POST['newname']);
	$dep->css = $_POST['newclass'];
	$dep->update();
	$ref = "positions";
break;

case "addpos" :
	$postn = new position;
	$postn->name = dataCheck($_POST['posname']);
	$postn->department = $_POST['posdept'];
	$postn->desc = dataCheck($_POST['posdesc']);
	$postn->senior = $_POST['sen'];
	$postn->enlist = $_POST['enlist'];
	$postn->repeat = $_POST['rept'];
	$postn->new_pos();
	$ref = "positions";
break;

case "editpos" :
	$posn = new position;
	$posn->get($_POST['posid']);
	$posn->name = dataCheck($_POST['newposn']);
	$posn->department = $_POST['newposd'];
	$posn->desc = dataCheck($_POST['newposds']);
	$posn->senior = $_POST['newsen'];
	$posn->enlist = $_POST['newenl'];
	$posn->repeat = $_POST['newrep'];
	$posn->update();
	$ref = "positions";
break;

case "orderpos" :
	foreach($_POST as $key => $value) {
		$orderq = "UPDATE " . $table['positions'] . " SET ord = '$value' WHERE posid = '$key'";
		$sql->queryme($orderq);
	}
	$ref = "positions";
break;

case "assign" :
	$position = new position;
	$position->get($_POST['posiid']);
	$position->crewm = $_POST['newcmem'];
	$position->update();
	if(($position->repeat == 1) and ($_POST['newcmem'] <> 0)) {
		$position->new_pos();
	} elseif(($position->repeat == 1) and ($_POST['newcmem'] == '0')) {
		if($position->countrep() > 1) {
			$position->del($position->pid);
		}
	}
	$ref = "positions";
break;

case "addfield" :
	$feild = new cfields;
	$feild->name = $_POST['fname'];
	$feild->type = $_POST['ftype'];
	$feild->newf();
	$ref = "custom";
break;

case "delfield" :
	$feil = new cfields;
	$feil->deletef($_GET['fid']);
	$ref = "custom";
break;

case "ordfields" :
	foreach($_POST as $fkey => $fvalue) {
		$ordthis = "UPDATE " . $table['customfields'] . " SET ord = '$fvalue' WHERE fid = '$fkey'";
		$sql->queryme($ordthis);
	}
	$ref = "custom";
break;

case "editfield" :
	$fld = new cfields;
	$fld->get($_GET['fldid']);
	$fld->name = $_POST['newfn'];
	$fld->type = $_POST['newft'];
	$fld->update();
	$ref = "custom";
break;

case "delawd" :
	$aw = new awards;
	$aw->delete($_GET['awd']);
	$ref = "awards";
break;

case "addawd" :
	$aw = new awards;
	$awd = new awarded;
	$aw->name = dataCheck($_POST['aname']);
	$aw->desc = dataCheck($_POST['adesc']);
	$aw->image = $_POST['aimg'];
	if($_POST['aauto'] == 1) {
		$aw->auto = "+" . $_POST['anum'] . " " . $_POST['aperd'];
	}
	$aw->create();
	if($_POST['aauto'] == 1) {
		$awd->new_auto($aw->awdid, $aw->auto);
	}
	$ref = "awards";
break;

case "giveawd" :
	global $mod_records;
	$awds = new awarded;
	$awds->award = $_POST['award'];
	$awds->crewm = $_POST['crewm'];
	$awds->date = time();
	$awds->reason = $_POST['reason'];
	$awds->assign();
	if($mod_records) {
		$record = new record;
		$awd = new awards;
		$awd->get($_POST['award']);
		$comment = "Presented Award - " . $awd->name;
		$record->make($_POST['crewm'],$_POST['record_change'],$comment,$_POST['reason']);
	}
	$ref = "awards";
break;

case "editawd" :
	$awxs = new awards;
	$awxs->get($_GET['awd']);
	$awxs->name = dataCheck($_POST['nname']);
	$awxs->desc = dataCheck($_POST['ndesc']);
	$awxs->image = $_POST['nimg'];
	$awxs->update();
	$ref = "awards";
break;

case "addmis" :
	$mission = new mission;
	$mission->name = dataCheck($_POST['nname']);
	$mission->desc = dataCheck($_POST['synop']);
	$mission->start = strtotime($_POST['srt']);
	$mission->add();
	$ref = "missions";
break;

case "editmis" :
	$mis = new mission;
	if($_POST['mid'] == "") {
		$mis->get(0);
	} else {
		$mis->get($_POST['mid']);
	}
	$mis->name = dataCheck($_POST['nname']);
	$mis->desc = dataCheck($_POST['ndesc']);
	$mis->start = strtotime($_POST['nstart']);
	$mis->update();
	$ref = "missions";
break;

case "delmis" :
	$mis = new mission;
	$mis->delete($_GET['mid']);
	$ref = "missions";
break;

case "addnews" :
	$n = new news;
	$n->title = dataCheck($_POST['ntitle']);
	$n->content = dataCheck($_POST['ncont']);
	$n->date = strtotime($_POST['ndate']);
		$c = new crew;
		$c->get($_COOKIE['crewid']);
	$n->poster = $c->rankn . " " . $c->fn . " " . $c->ln;
	$n->create();
	$ref = "news";
break;

case "editnews" :
	$new = new news;
	$new->fetch($_GET['nid']);
	$new->title = dataCheck($_POST['title']);
	$new->content = dataCheck($_POST['content']);
	$new->date = strtotime($_POST['ndate']);
	$new->poster = dataCheck($_POST['poster']);
	$new->update();
	$ref = "news";
break;

case "delnews" :
	$news = new news;
	$news->delete($_GET['nid']);
	$ref = "news";
break;

case "usr_change" :
	$usr = new users;
	$usr->get($_GET['uid']);
	$usr->player = $_POST['new_crewm'];
	$usr->update();
	$ref = "users";
break;

case "changepw" :
	if($_POST['pw1'] == $_POST['pw2']) {
		$user = new users;
		$user->get($_GET['uid']);
		$user->pwd = md5(trim($_POST['pw1']));
		$user->changepwd();
	} else {
		echo "The Feilds did not match, please go <a href=\"javascript:history.back()\">back</a> and repeat";
	}
	$ref = "users";
break;

case "newstat" :
	$stat = new specs;
	$stat->sname = dataCheck($_POST['title']);
	$stat->svalue = dataCheck($_POST['value']);
	$stat->smulti = $_POST['multil'];
	$stat->newstat();
	$ref = "specs";
break;

case "editspec" :
	$st = new specs;
	$st->get($_GET['sp']);
	$st->sname = dataCheck($_POST['ntitle']);
	$st->svalue = dataCheck($_POST['nvalue']);
	$st->smulti = $_POST['nmultil'];
	$st->update();
	$ref = "specs";
break;

case "delstat" :
	$spec = new specs;
	$spec->delete($_GET['stat']);
	$ref = "specs";
break;

case "statord" :
	//old stat ordering function. moved to the specs.php admin file
	$u = $_GET['ord'];
	if($_GET['way'] == "up") {
		$u--;
	} else {
		$u++;
	}
	echo $query1 = "UPDATE " . $table['specs'] . " SET sord = '" . $_GET['ord'] . "' WHERE sord = '$u'";
	echo $query2 = "UPDATE " . $table['specs'] . " SET sord = '$u' WHERE statid = '" . $_GET['sid'] . "'";
	$sql->queryme($query1);
	$sql->queryme($query2);
	$ref = "specs";
break;

case "change_ul" :
	$usr = new users;
	$usr->get($_POST['uid']);
	$usr->ulevel = $_POST['new_level'];
	$usr->update();
	$ref = "users";
break;

case "delpt" :
	$point = new points;
	$point->remove($_GET['ptid']);
	$ref = "posting";
break;

case "addpt" :
	$pt = new points;
	$pt->ptnum = $_POST['newpt'];
	$pt->ptdesc = dataCheck($_POST['newcomm']);
	$pt->add();
	$ref = "posting";
break;

case "emailer" :
	if($_POST['to'] != 'all' && $_POST['to'] != 'ss') {
		$crew = new crew;
		$crew->get($_POST['to']);
		$recip = $crew->email;
	} else {
		if($_POST['to'] == 'ss') {
			$getmail = "SELECT email FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['positions'] . " AS positions ON(crew.crewid = positions.crewid) WHERE positions.senior = '1'";
			$resmail = $sql->queryme($getmail);
			$count = 1;
			while($row = $sql->sql_row($resmail)) {
				if($count != 1) { $recip .= ", "; } else { $count = 2; }
				$recip .= $row[0];
			}
		} else {
			$fetchall = "SELECT email FROM " . $table['crew'] . " AS crew WHERE active = '1'";
			$resall = $sql->queryme($fetchall);
			$count = 1;
			while($row = $sql->sql_row($resall)) {
				if($count != 1) { $recip .= ", "; } else { $count = 2; }
				$recip .= $row[0];
			}
		}
	}
	$msubject = $_POST['subject'];
	$mmessage = $_POST['message'];
	$mfrom = "From: " . $_POST['from'] . " <" . $_POST['frome'] . ">";
	if(mail($recip, $msubject, $mmessage, $mfrom)) {
		echo "Mail Sent Successfully!<br><br>";
	} else {
		echo "Problem sending mail, make sure your server supports SMTP mail<br>";
		die();
	}
	$ref = "crew";
break;

case "addrec" :
	$addrec = "INSERT INTO " . $table['records'] . "(crewid, pchange, comment, reason, date, admin) VALUES ('" . $_POST['player'] . "', '" . $_POST['pts'] . "', '" . $_POST['comment'] . "', '" . dataCheck($_POST['reason']) . "', '" . time() . "', '" . $_SESSION['crewid'] . "')";
	$sql->queryme($addrec);
	$ref = "records";
break;

case "genrpt" :
	$report = new report;
	if($_POST['rep_date'] != "") {
		$report->calc_spc_times($_POST['rep_time']);
	}
	$string .= "Name\t";
	if($_POST['tp1'] == 1) { $string .= $time_period1 . "\t"; }
	if($_POST['tp2'] == 1) { $string .= $time_period2 . "\t"; }
	if($_POST['tp3'] == 1) { $string .= $time_period3 . "\t"; }
	if($_POST['all'] == 1) { $string .= "Total\t"; }
	if($_POST['points'] == 1) { $string .= "Points Total"; }
	$string .= "\n";
	
	$crewm = "SELECT crewid, first_n, last_n, name, loa FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON(rank.rankid = crew.rankid) WHERE active = '1'";
	$resm = $sql->queryme($crewm);
	while($c = $sql->sql_array($resm)) {
		$string .= $c['name'] . " " . $c['first_n'] . " " . $c['last_n'];
		if($c['loa'] == '1') { $string .= "(LOA)"; }
		$string .= "\t";
		$report->generate($c['crewid']);
		if($_POST['tp1'] == 1) { $string .= $report->cat1_posts . "\t"; }
		if($_POST['tp2'] == 1) { $string .= $report->cat2_posts . "\t"; }
		if($_POST['tp3'] == 1) { $string .= $report->cat3_posts . "\t"; }
		if($_POST['all'] == 1) { $string .= $report->total . "\t"; }
		if($_POST['points'] == 1) { $string .= $report->point_total; }
		$string .= "\n";
	}
	
	$crew = new crew;
	$crew->get($_COOKIE['crewid']);
	if($_POST['email_me'] == 1) {
		$to = $crew->email;
		if($_POST['email_other'] != "") {
			$to .= ",";
		}
	}
	$to .= $_POST['email_other'];
	$from = "From: " . $crew->rankn . " " . $crew->fn . " " . $crew->ln . "<" . $crew->email . ">";
	$subject = "Posting Report For ";
	if($_POST['rep_date'] == "") {
		$subject .= date("jS F Y");
	} else {
		$suject .= $_POST['rep_date'];
	}
	mail($to,$subject,$string,$from);
	echo "<span class=\"Amessage\">Mail Sent</span><br>";
	
	$ref = "posting";
break;

case "removeCss" :
	$style = new deptcss;
	$style->remove($_GET['id']);
	
	$ref = "positions";
break;

case "addCss" :
	$style = new deptcss;
	$style->name = $_POST['cssName'];
	$style->color = $_POST['color'];
	$style->addnew();
	
	$ref = "positions";
break;

case "editCss" :
	$style = new deptcss;
	$style->get($_POST['cid']);
	$style->name = $_POST['newName'];
	$style->color = $_POST['newColor'];
	$style->edit();
	$ref = "positions";
break;

case "editcoc" :
	foreach($_POST as $cid => $value) {
		$cocu = "UPDATE crew SET coc = '" . $value . "' WHERE crewid = '" . $cid . "'";
		$sql->queryme($cocu);
	}
	$ref = "crew";
break;

case "setImport" :
	
break;

case "setExport" :
	
break;

default:
	echo "Error - No Process has been selected";
	die();
break;
}
$refresh = "../admin.php?page=" . $ref . ".php";
?>
<html>
<head>
<title>Success</title>
<? include_once "../styles.php"; ?>
</head>
<body><br>
	<p class="Amessage" align="center">Process completed. Click <a href="javascript:top.window.opener.location='<? echo $refresh; ?>';self.close()">here</a> to continue</p>
</body>
</html>
<?
} else {
	echo "Access Denied, you do not have admin access to this site.";
	die();
}
?>