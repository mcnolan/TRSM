<?php
/*
setup_options.php : basic options for miniBB.
Copyright (C) 2001-2002 miniBB.net.

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$DB='mysql';

$DBhost='localhost';
$DBname='miniBB';
$DBusr='tumbleweeddesign';
$DBpwd='';

$admin_usr='admin';
$admin_pwd='admin';
$admin_email='email@domain';

$bb_admin='bb_admin.php';

$lang='eng';
$skin='default';
$main_url='http://localhost/minibb';
$sitename='Me Forums';
$emailadmin=0;
$emailusers=1;
$userRegName='_A-Za-z0-9 ';
$l_sepr = '&middot;';

$post_text_maxlength=10240;
$post_word_maxlength=70;
$topic_max_length=50;
$viewmaxtopic=30;
$viewlastdiscussions=30;
$viewmaxreplys=30;
$viewmaxsearch=30;
$viewpagelim=50;
$viewTopicsIfOnlyOneForum=0;

$protectWholeForum=0;
$protectWholeForumPwd='pwd';

$postRange=60;

$dateFormat='MM DD, YYYY<br>T';

$cookiedomain='';
$cookiename='miniBBsite';
$cookiepath='/';
$cookiesecure=FALSE;
$cookie_expires=90000;
$cookie_renew=1800;
$cookielang_exp=2592000;

/* New options for miniBB 1.1 */
$Tf='minibb_forums';
$Tp='minibb_posts';
$Tt='minibb_topics';
$Tu='minibb_users';
$Ts='minibb_send_mails';
$Tb='minibb_banned';

$disallowNames=array('Anonymous');

/* New options for miniBB 1.2 */
$sortingTopics=0;
$topStats=4;
$genEmailDisable=0;

/* New options for miniBB 1.3 */
$defDays=365;
$userUnlock=0;

/* New options for miniBB 1.5 */
$emailadmposts=0;
$useredit=86400; 

/* New options for miniBB 1.6 */
//$metaLocation='go';
//$closeRegister=1;
//$timeDiff=21600;

/* New options for miniBB 1.7 */
$stats_barWidthLim='31';

?>