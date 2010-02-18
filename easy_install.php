<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: easy_install.php
| Build 3
| <Changes>
| Updated output settings to comply with new settings
| Changed the layout and colour to make it pretty
|
| <Purpose>
| One of Two Installer methods. This one uses input forms to generate the settings file.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
?>
<html>
<head>
<title>Install TRSM 1.0</title>
<link href="css/trsm.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.aTable {
    border: 1pt solid #666666;
    text-align: center;
    font-weight: bold;
    width: 380;
}

.bTable {
    border: 1pt solid #666666;
    font-weight: bold;
    width: 380;
}

.bLine {
    padding-bottom: 3;
}

.normal {
    font-weight: normal;
}

.small {
    font-size: 8pt;
    text-align: left;
}
-->
</style>
</head>

<body>
<center><img src="images/trsm_logo_large.gif"></center>

<?
switch($_GET['step']) {
    case "1" :
        $conn = mysql_connect($_POST['dbdb'], $_POST['dbun'], $_POST['dbpw']) or die("Sorry, could not connect to database, check settings (<a href=javascript:history.back()>Back</a>)");
        $databases = mysql_query("SHOW DATABASES");
        ?>
        <form action="easy_install.php?step=2" method="post">
        <table class="aTable" align="center">
        <tr>
        <td colspan="2" class="missionHeader">
        Installation Options
        </td>
        </tr>
        <tr>
        <td colspan="2" class="normal">
        Please Select the database to install TRSM into:</td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="hidden" name="dbdb" value="<? echo $_POST['dbdb']; ?>">
        <input type="hidden" name="dbun" value="<? echo $_POST['dbun']; ?>">
        <input type="hidden" name="dbpw" value="<? echo $_POST['dbpw']; ?>">
        <select name="dbdbn">
        <?
        while($db = mysql_fetch_row($databases)) {    
        ?>
        <option value="<? echo $db[0]; ?>"><? echo $db[0]; ?></option>
        <?
        }
        ?>
        </select>
        </td>
        </tr>
        <tr>
        <td colspan="2" class="missionHeader">
        Select the sections of TRSM that you wish to install:
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <table>
        <tr><td>Core</td> <td align="center">*required</td></tr>
        <tr><td>Manifest</td> <td align="center">*required</td></tr>
        <tr><td>News</td> <td align="center"><input type="checkbox" value="1" name="optNews"></td></tr>
        <tr><td>Ship Specifications</td> <td align="center"><input type="checkbox" value="1" name="optSpecs"></td></tr>
        <tr><td>Forums</td> <td align="center"><input type="checkbox" value="1" name="optForums"></td></tr>
        <tr><td>Missions</td> <td align="center"><input type="checkbox" value="1" name="optMissions"></td></tr>
        <tr><td>PostingReport System</td> <td align="center"><input type="checkbox" value="1" name="optPosting"></td></tr>
        <tr><td>Awards</td> <td align="center"><input type="checkbox" value="1" name="optAwards"></td></tr>
        <tr><td>Service Records</td> <td align="center"><input type="checkbox" value="1" name="optService"></td></tr>
        <tr><td>Chain Of Command</td> <td align="center"><input type="checkbox" value="1" name="optCoc"></td></tr>
        <tr><td>Database</td> <td align="center"><input type="checkbox" value="1" name="optDatabase"></td></tr>
        </table>
        </td>
        </tr>
        <tr>
        <td colspan="2" class="missionHeader">
        Ship Settings
        </td>
        </tr>
        <td>Ship Name</td> <td><input type="text" name="sname"></td>
        </tr>
        <tr>
        <td>First Admin Username:</td> <td><input type="text" name="adminun"></td>
        </tr>
        <tr class="bLine">
        <td>First Admin Password:</td> <td><input type="text" name="adminpw"></td>
        </tr>
        <tr>
        <td colspan="2" class="normal">What level of administrator gets application emails:</td>
        </tr>
        <tr>
        <td colspan="2">
        <select name="emailLevel">
        <option value="2">Basic Admin</option>
        <option value="3">Advanced Admin</option>
        <option value="4">Privileged Admin</option>
        <option value="5" selected>SuperAdmin</option>
        </select>
        <br><br>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="submit" value="-----Finish Install-----">
        </td>
        </tr>
        </table>
        </form>
        <?
        break;
    case "2" :
            $s_file = "settings.php";
        if(file_exists($s_file) && is_writable($s_file)) {
            //Determine Variables
            $dir = dirname($_SERVER['PHP_SELF']);
            $mpath = "http://" . $_SERVER['SERVER_NAME'] . $dir . "/";
            $sett = fopen($s_file,"w+b");

$write = "<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: settings.php
| Build x
| <Changes>
| Generated by script/Modified by the user
|
| <Purpose>
| TRSM master config file - Refer to manual for help
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/

//	mySQL database connection settings
\$host = '" . $_POST['dbdb'] . "'; 
\$user = '" . $_POST['dbun'] . "'; 
\$pass = '" . $_POST['dbpw'] . "'; 
\$datab = '" . $_POST['dbdbn'] . "';

//	Ship Master Settings
\$shipname = \"" . $_POST['sname'] . "\"; 
\$path = \"$mpath\"; //Path to the installation 

//These are the initial superuser account settings, these are essential for getting into the system, don't make them easy to guess.
\$admin_usr='" . $_POST['adminun'] . "'; 
\$admin_pwd='" . $_POST['adminpw'] . "'; 
//Once the system has installed, the above 2 lines can be removed

//paths to image locations
\$ipath = \"images/\";
\$rpath = \"images/ranks/\";
\$awdpath = \"images/awards/\";

//path to Css Files
\$csspath = \"css/\";

//What admins get copies of the join applications, based on admin level.
\$email_level = " . $_POST['emailLevel'] . ";

//Options - what sections to install, and show on menu
\$mod_specs = ";
$write .= ($_POST['optSpecs'])?"true;":"false;";
$write .= "
\$mod_forums = ";
$write .= ($_POST['optForums'])?"true;":"false;";
$write .= "
\$mod_missions = ";
$write .=  ($_POST['optMissions'])?"true;":"false;";
$write .= "
\$mod_report = ";
$write .= ($_POST['optPosting'])?"true;":"false;";
$write .= "
\$mod_awards = ";
$write .= ($_POST['optAwards'])?"true;":"false;";
$write .= "
\$mod_records = ";
$write .= ($_POST['optService'])?"true;":"false;";
$write .= "
\$mod_coc = ";
$write .= ($_POST['optCoc'])?"true;":"false;";
$write .= "
\$mod_database = ";
$write .= ($_POST['optDatabase'])?"true;":"false;";

$write .= "

//###############
// Custom bios box sizes (=p)
//###############
\$line_size = \"20\";
\$box_cols = \"25\";
\$box_rows = \"3\";
\$linesmall_size = \"4\";
\$linemed_size = \"15\";
\$boxlarge_cols = \"35\";
\$boxlarge_rows = \"6\";

//###############
//Posting Report Settings
//###############
/*
  --Time period Options--
  This Week
  Two Weeks
  Last Month
  Last Week
  This Month
  OR
  any date, and all posts since that date will be counted
*/
\$time_period1 = 'This Week';
\$time_period2 = 'Two Weeks';
\$time_period3 = 'Last Month';

\$first_day = 'Sunday';
\$show_synop = true;
\$remove_posts = false; //Should the system remove all posts when a player is removed? true/false
\$display_limit = 20; //How many posts to view per page

//###############
//Awards System Settings
//###############
//Should the system delete all awards by a member when they leave? true/false
\$remove_awards = false;

//###############
//Document Juggler Stuff
//###############
// 
 # application name
 \$DJ_appname = \$shipname . ' Database';
    
//###############
//miniBB settings, alter after this line at own risk (its setup as per default install folders)
//###############
/*
Options for miniBB. Alter with care.
Copyright (C) 2001-2003 miniBB.net.
*/

\$admin_email='superuser@spms';
\$lang='eng';
\$skin='default';
\$sitename= \$shipname . ' :: Forums';
\$emailadmin=0;
\$emailusers=1;
\$userRegName='_A-Za-z0-9 ';
\$l_sepr = '&middot;';

\$post_text_maxlength=10240;
\$post_word_maxlength=70;
\$topic_max_length=50;
\$viewmaxtopic=30;
\$viewlastdiscussions=30;
\$viewmaxreplys=30;
\$viewmaxsearch=30;
\$viewpagelim=50;
\$viewTopicsIfOnlyOneForum=0;

\$protectWholeForum=0;
\$protectWholeForumPwd='pwd';

\$postRange=60;

\$dateFormat='MM DD, YYYY<br>T';

/* New options for miniBB 1.1 */
\$disallowNames=array('Anonymous');
/* New options for miniBB 1.2 */
\$sortingTopics=0;
\$topStats=4;
\$genEmailDisable=0;
/* New options for miniBB 1.3 */
\$defDays=365;
\$userUnlock=0;
/* New options for miniBB 1.5 */
\$emailadmposts=0;
\$useredit=86400; 
/* New options for miniBB 1.7 */
\$stats_barWidthLim='31';

//###############
//Table Names
//###############

\$table['forums'] = 'minibb_forums';
\$table['forum_posts'] = 'minibb_posts';
\$table['topics'] = 'minibb_topics';
\$table['send_emails'] = 'minibb_send_mails';
\$table['forum_banned'] = 'minibb_banned';

\$table['users'] = 'minibb_users';
\$table['crew'] = 'crew';
\$table['ranks'] = 'ranks';
\$table['positions'] = 'positions';
\$table['departments'] = 'departments';
\$table['customfields'] = 'customfields';
\$table['biodata'] = 'biodata';
\$table['awards'] = 'awards';
\$table['awarded'] = 'awarded';
\$table['missions'] = 'missions';
\$table['news'] = 'news';
\$table['points'] = 'points';
\$table['posts'] = 'posts';
\$table['records'] = 'records';
\$table['specs'] = 'specifications';
\$table['styles'] = 'styles';

//###############
//Misc Stuff / 3rd Party Settings, don't touch me please! (If you do...you really really need to know what yer doing)
//###############

//Minibb
\$DB='mysql';
\$DBhost = \$host;
\$DBname = \$datab;
\$DBusr = \$user;
\$DBpwd = \$pass;
\$bb_admin='bb_admin.php';
\$main_url = \$path . '/forum';
\$indexphp='forum.php?';
//Cookie Options, also used by spms logins
\$cookiedomain = '';
\$cookiename='miniBBsite';
\$cookiepath='/';
//\$cookiepath = \$path;
\$cookiesecure=FALSE;
\$cookie_expires=90000;
\$cookie_renew=1800;
\$cookielang_exp=2592000;
//Minibb Tables
\$Tf=\$table['forums'];
\$Tp=\$table['forum_posts'];
\$Tt=\$table['topics'];
\$Tu=\$table['users'];
\$Ts=\$table['send_emails'];
\$Tb=\$table['forum_banned'];

//Doc Juggler
\$DJ_host = \$host;
\$DJ_database = \$datab;
\$DJ_user = \$user;
\$DJ_password = \$pass;
\$DJ_path = \$path;
\$DJ_file = 'database.php';

//Build image path
\$imagepath = \$path . \$ipath;
?>";
            fwrite($sett,$write) or die("There has been an error, and the installer can't write to the file :(");
            fclose($sett);
            
            $alone = true;
	    include "install.php";
            //chmod($s_file,644);
            ?>
            <table class="bTable" align="center">
            <tr>
            <td class="missionHeader">Trek RPG Site Manager 1.0</td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
            <td class="normal">Has installed itself...wasn't that easy?<br><br>
            Please refer to the manual for further questions
            <br><br>
            <i>TRSM is Copyright &#169; 2003-2005  Nolanator<br></i>
            <span class="small">This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
            the Free Software Foundation; either version 2 of the License, or(at your option) any later version.</span>
            </td>
            </tr>
            <tr>
            <td align="center"><br> &gt; <a href="index.php">Go To Your Site</a> &lt; </td>
            </tr>
            </table>
            <?
        } else {
            die("A problem has risen, either the settings dummy file (settings.php) does not exist, or has not got writable permissions on it(Unix Only)");
        }
        break;
    
    default:
    ?>
    <form action="easy_install.php?step=1" method="post">
    <table class="aTable" align="center" cellspacing="0" cellpadding="0">
    <tr>
    <td colspan="2" class="missionHeader">
    <br>
    Welcome to the Installation file of Trek RPG Site Manager
    </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
    <td colspan="2" class="normal">
    To begin, fill out your database settings and click continue.
    </td>
    </tr>
    <tr>
    <td>Database Hostname:</td> <td align="left"><input type="text" name="dbdb" value="localhost"></td>
    </tr>
    <tr>
    <td>Database Username:</td> <td align="left"><input type="text" name="dbun"></td>
    </tr>
    <tr>
    <td>Database Password:</td> <td align="left"><input type="password" name="dbpw"></td>
    </tr>
    <tr>
    <td colspan="2">
    <input type="submit" value="Continue--&gt;">
    </td>
    </tr>
    </table>
    </form>
    <?
    break;
}
?>
</body>
</html>