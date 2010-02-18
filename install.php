<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: install.php
| Build 3
| <Changes>
| Changed the default Data Somewhat
| Attempt to fix compatibility problem experienced with some hosts
|
| <Purpose>
| Installer File, builds the database. (Assuming all fields in the settings file are correct)
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
include "settings.php";

mysql_connect($host,$user,$pass) or die("Could not get database, check settings.php");
mysql_select_db($datab) or die("Could not get database, check settings.php");

//Setup all tables structures

//Crew Table - required
$query1 = "CREATE TABLE " . $table['crew'] . " (
  `crewid` int(11) NOT NULL auto_increment,
  `first_n` text,
  `middle_n` text,
  `last_n` text NOT NULL,
  `rankid` int(11) NOT NULL default '0',
  `species` text NOT NULL,
  `gender` text NOT NULL,
  `email` text NOT NULL,
  `joined` int(11) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  `loa` tinyint(4) NOT NULL default '0',
  `coc` int(11) NOT NULL default '0',
  PRIMARY KEY  (`crewid`)
)";
mysql_query($query1) or die(mysql_error());

//Custom fields table - required
$query2 = "CREATE TABLE " . $table['customfields'] . " (
  `fid` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `ord` int(11) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
)";
mysql_query($query2) or die(mysql_error());

//Departments table - required
$query3 = "CREATE TABLE " . $table['departments'] . " (
  `deptid` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `class` text NOT NULL,
  `ord` int(11) NOT NULL default '0',
  PRIMARY KEY  (`deptid`)
)";
mysql_query($query3) or die(mysql_error());

//Custom Field data - required
$query4 = "CREATE TABLE " . $table['biodata'] . " (
  `fiid` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL default '0',
  `info` text NOT NULL,
  `crewid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`fiid`)
)";
mysql_query($query4) or die(mysql_error());

//Forum banned table - optional
$query5 = "CREATE TABLE " . $table['forum_banned'] . " (
  `id` int(10) NOT NULL auto_increment,
  `banip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`)
)";
if($mod_forums) {
mysql_query($query5) or die(mysql_error());
}
//Forum forum table - optional
$query6 = "CREATE TABLE " . $table['forums'] . " (
  `forum_id` int(10) NOT NULL auto_increment,
  `forum_name` varchar(150) NOT NULL default '',
  `forum_desc` text NOT NULL,
  `forum_order` int(10) NOT NULL default '0',
  `forum_icon` varchar(255) NOT NULL default 'default.gif',
  PRIMARY KEY  (`forum_id`)
)";
if($mod_forums) { mysql_query($query6) or die(mysql_error()); }

//Forum posts table - optional
$query7 = "CREATE TABLE " . $table['forum_posts'] . " (
  `post_id` int(11) NOT NULL auto_increment,
  `forum_id` int(10) NOT NULL default '1',
  `topic_id` int(10) NOT NULL default '1',
  `poster_id` int(10) NOT NULL default '0',
  `poster_name` varchar(40) NOT NULL default 'Anonymous',
  `post_text` text NOT NULL,
  `post_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `poster_ip` varchar(15) NOT NULL default '',
  `post_status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `post_id` (`post_id`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_id` (`topic_id`),
  KEY `poster_id` (`poster_id`)
)";
if($mod_forums) { mysql_query($query7) or die(mysql_error()); }

//Forum mailer table - optional
$query8 = "CREATE TABLE " . $table['send_emails'] . " (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL default '1',
  `topic_id` int(10) NOT NULL default '1',
  PRIMARY KEY  (`id`)
)";
if($mod_forums) { mysql_query($query8) or die(mysql_error()); }

//Forum topics table - optional
$query9 = "CREATE TABLE " . $table['topics'] . " (
  `topic_id` int(10) NOT NULL auto_increment,
  `topic_title` varchar(100) NOT NULL default '',
  `topic_poster` int(10) NOT NULL default '0',
  `topic_poster_name` varchar(40) NOT NULL default 'Anonymous',
  `topic_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `topic_views` int(10) NOT NULL default '0',
  `forum_id` int(10) NOT NULL default '1',
  `topic_status` tinyint(1) NOT NULL default '0',
  `topic_last_post_id` int(10) NOT NULL default '1',
  PRIMARY KEY  (`topic_id`),
  KEY `topic_id` (`topic_id`),
  KEY `forum_id` (`forum_id`)
)";
if($mod_forums) { mysql_query($query9) or die(mysql_error()); }

//minibb users table, now spms users table - required
$query10 = "CREATE TABLE " . $table['users'] . " (
  `user_id` int(10) NOT NULL auto_increment,
  `username` varchar(40) NOT NULL default '',
  `user_regdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_password` varchar(32) NOT NULL default '',
  `user_email` varchar(50) NOT NULL default '',
  `user_icq` varchar(15) NOT NULL default '',
  `user_website` varchar(100) NOT NULL default '',
  `user_occ` varchar(100) NOT NULL default '',
  `user_from` varchar(100) NOT NULL default '',
  `user_interest` varchar(150) NOT NULL default '',
  `user_viewemail` tinyint(1) NOT NULL default '0',
  `user_sorttopics` tinyint(1) NOT NULL default '1',
  `user_newpwdkey` varchar(32) NOT NULL default '',
  `user_newpasswd` varchar(32) NOT NULL default '',
  `crewid` int(11) NOT NULL default '0',
  `userlevel` int(11) NOT NULL default '0',
  `signature` text NOT NULL default '',
  PRIMARY KEY  (`user_id`)
)";
mysql_query($query10) or die(mysql_error());

//positions table - required
$query11 = "CREATE TABLE " . $table['positions'] . " (
  `posid` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `deptid` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `senior` tinyint(4) NOT NULL default '0',
  `enlisted` tinyint(4) NOT NULL default '0',
  `repeating` tinyint(4) NOT NULL default '0',
  `ord` int(11) NOT NULL default '0',
  `crewid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`posid`)
)";
mysql_query($query11) or die(mysql_error());

//Rank table - required
$query12 = "CREATE TABLE " . $table['ranks'] . " (
  `rankid` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `color` text NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY  (`rankid`)
)";
mysql_query($query12) or die(mysql_error());

//Styles table - required
$query13 = "CREATE TABLE " . $table['styles'] . " (
  `sid` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `color` text NOT NULL,
  PRIMARY KEY  (`sid`)
)";
mysql_query($query13) or die(mysql_error());

//Service record table - optional
$query29 = "CREATE TABLE " . $table['records'] . " (
  `rid` int(11) NOT NULL auto_increment,
  `crewid` int(11) NOT NULL default '0',
  `pchange` int(11) NOT NULL default '0',
  `comment` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `admin` int(11) NOT NULL default '0',
  `reason` text NOT NULL,
  PRIMARY KEY  (`rid`)
) TYPE=MyISAM COMMENT='service record table';";
if($mod_records) { mysql_query($query29) or die(mysql_error()); }

//DJ content table - optional
$query30 = "CREATE TABLE djnew_cont (
  node_id bigint(16) NOT NULL default '0',
  node_cont text NOT NULL,
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;";
if($mod_database) { mysql_query($query30) or die(mysql_error()); }

//DJ cat table - optional
$query31 = "CREATE TABLE djnew_tree (
  node_id bigint(16) NOT NULL auto_increment,
  node_own bigint(16) NOT NULL default '0',
  node_lev bigint(16) NOT NULL default '0',
  node_pos bigint(16) NOT NULL default '0',
  node_par bigint(16) NOT NULL default '0',
  node_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (node_id),
  KEY node_own (node_own),
  KEY node_pos (node_pos),
  KEY node_lev (node_lev)
) TYPE=MyISAM;";
if($mod_database) { mysql_query($query31) or die(mysql_error()); }

//posting report points - optional
$query32 = "CREATE TABLE " . $table['points'] . " (
  `ptid` int(11) NOT NULL auto_increment,
  `pchange` int(11) NOT NULL default '0',
  `pcomm` text NOT NULL,
  PRIMARY KEY  (`ptid`)
) TYPE=MyISAM COMMENT='Points Information Table';";
if($mod_report) { mysql_query($query32) or die(mysql_error()); }

//posting report posts - optional
$query33 = "CREATE TABLE " . $table['posts'] . " (
  `postid` int(11) NOT NULL auto_increment,
  `subject` text NOT NULL,
  `synop` text NOT NULL,
  `missionid` int(11) NOT NULL default '0',
  `crewid` int(11) NOT NULL default '0',
  `crewm` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `comments` text NOT NULL,
  `points` int(11) NOT NULL default '0',
  PRIMARY KEY  (`postid`)
) TYPE=MyISAM COMMENT='Posts';";
if($mod_report) { mysql_query($query33) or die(mysql_error()); }

//Set all Initial Setup Data

//Set up superadmin user - required
$rs = "INSERT INTO $Tu (username, user_password, user_email, user_viewemail, crewid, userlevel) values ('$admin_usr', '".md5($admin_pwd)."', '$admin_email', '0', '1', '5')";
mysql_query($rs) or die(mysql_error());

//Set up dummy record for Co/inital admin - required
$query14 = "INSERT INTO " . $table['crew'] . " (`first_n`, `middle_n`, `last_n`, `rankid`, `species`, `gender`, `email`, `joined`, `active`, `loa`) VALUES ('admin', '', 'admin', '2', '', 'Other', 'change@this', '" . time() . "', '1', '0')";
mysql_query($query14) or die(mysql_error());//or die("Installation Failed, data input failure");

//set default custom fields - required
$query15 = "INSERT INTO " . $table['customfields'] . " (`fid`, `name`, `type`, `ord`) VALUES (1, 'Age', 'line_small', 1),
(2, 'Background', 'box_large', 6),
(3, 'Height', 'line_small', 2),
(4, 'Weight', 'line_small', 3),
(5, 'Appearance', 'box', 4),
(6, 'Personality', 'box', 5)";
mysql_query($query15) or die(mysql_error());// or die("Installation Failed, data input failure");

//Set up departments - required
$query16 = "INSERT INTO " . $table['departments'] . " (`deptid`, `name`, `class`, `ord`) VALUES (1, 'Command', 'commandBanner', 1),
(2, 'Flight Control', 'commandBanner', 2),
(3, 'Operations', 'servicesBanner', 4),
(4, 'Marine Detachment', 'marineBanner', 7),
(7, 'Medical', 'scimedBanner', 5),
(6, 'Security/Tactical', 'servicesBanner', 3),
(8, 'Science', 'scimedBanner', 6),
(9, 'Civilian', 'civilianBanner', 8)";
mysql_query($query16) or die(mysql_error());//or die("Installation Failed, data input failure");

//set up first crew members field data - required
$query17 = "INSERT INTO " . $table['biodata'] . " (`fiid`, `fid`, `info`, `crewid`) VALUES (1, 1, '', 1),
(2, 3, '', 1),
(3, 2, 'Change this bio to suit your commanding officer character. This bio will be linked into the default admin account.', 1),
(4, 4, '', 1),
(5, 5, '', 1),
(6, 6, '', 1)";
mysql_query($query17) or die(mysql_error());// or die("Installation Failed, data input failure");

//Setup a default forum - optional
$query18 = "INSERT INTO " . $table['forums'] . " (`forum_id`, `forum_name`, `forum_desc`, `forum_order`, `forum_icon`) VALUES (1, 'General', 'General Chat Forum', 1, 'transparent.gif')";
if($mod_forums) { mysql_query($query18) or die(mysql_error()); }

//Setup inital default positions - required
$query19 = "INSERT INTO " . $table['positions'] . " (`posid`, `name`, `deptid`, `description`, `senior`, `enlisted`, `repeating`, `ord`, `crewid`) VALUES (1, 'Commanding Officer', 1, 'Position Description', 1, 0, 0, 1, 1),
(2, 'Executive Officer', 1, 'Position Description', 1, 0, 0, 2, 0),
(3, 'Chief Of Flight Control', 2, 'Position Description', 1, 0, 0, 1, 0),
(4, 'Chief Science officer', 8, 'Position Description', 1, 0, 0, 1, 0),
(5, 'Bartender', 9, 'Position Description', 0, 0, 0, 1, 0),
(6, 'Chief Medical Officer', 7, 'Position Description', 1, 0, 0, 1, 0),
(7, 'Chief Operations Officer', 3, 'Position Description', 1, 0, 0, 1, 0),
(8, 'Detachment Commander', 4, 'Position Description', 1, 0, 0, 1, 0),
(9, 'Chief Security/Tactical Officer', 6, 'Position Description', 1, 0, 0, 1, 0)";
mysql_query($query19) or die(mysql_error());// or die("Installation Failed, data input failure");

//Setup default ranks - required
$query20 = "INSERT INTO " . $table['ranks'] . " (`rankid`, `name`, `url`, `color`, `rating`) VALUES (1, 'Rank Pending', 'pending.png', '', 20),
(2, 'Blank', 'red/blank.png', 'Red', 19),
(3, 'Ensign', 'red/ens.png', 'Red', 9),
(4, 'Lieutenant (JG)', 'red/ltjg.png', 'Red', 8),
(5, 'Lieutenant', 'red/lt.png', 'Red', 7),
(6, 'Lieutenant Commander', 'red/lcdr.png', 'Red', 6),
(7, 'Commander', 'red/cdr.png', 'Red', 5),
(8, 'Captain', 'red/cpt.png', 'Red', 4),
(9, 'Ensign', 'teal/ens.png', 'Teal', 9),
(10, 'Lieutenant (JG)', 'teal/ltjg.png', 'Teal', 8),
(11, 'Lieutenant', 'teal/lt.png', 'Teal', 7),
(12, 'Lieutenant Commander', 'teal/lcdr.png', 'Teal', 6),
(13, 'Commander', 'teal/cdr.png', 'Teal', 5),
(14, 'Captain', 'teal/cpt.png', 'Teal', 4),
(15, 'Ensign', 'blue/ens.png', 'Blue', 9),
(16, 'Lieutenant (JG)', 'blue/ltjg.png', 'Blue', 8),
(17, 'Lieutenant', 'blue/lt.png', 'Blue', 7),
(18, 'Lieutenant Commander', 'blue/lcdr.png', 'Blue', 6),
(19, 'Commander', 'blue/cdr.png', 'Blue', 5),
(20, 'Captain', 'blue/cpt.png', 'Blue', 4),
(21, '1st Lieutenant', 'green/1lt.png', 'Green', 8),
(22, '2nd Lieutenant', 'green/2lt.png', 'Green', 9),
(23, 'Captain', 'green/cpt.png', 'Green', 7),
(24, 'Major', 'green/maj.png', 'Green', 6),
(25, 'Lieutenant Colonol', 'green/lcol.png', 'Green', 5),
(26, 'Colonol', 'green/col.png', 'Green', 4),
(27, 'Blank', 'green/blank.png', 'Green', 19),
(28, 'Blank', 'blue/blank.png', 'Blue', 19),
(29, 'Ensign', 'yellow/ens.png', 'Yellow', 9),
(30, 'Lieutenant (JG)', 'yellow/ltjg.png', 'Yellow', 8),
(31, 'Lieutenant', 'yellow/lt.png', 'Yellow', 7),
(36, 'Crewman Recruit', 'blue/e1.png', 'Blue', 18),
(33, 'Lieutenant Commander', 'yellow/lcdr.png', 'Yellow', 6),
(34, 'Commander', 'yellow/cdr.png', 'Yellow', 5),
(35, 'Captain', 'yellow/cpt.png', 'Yellow', 4),
(37, 'Crewman Apprentice', 'blue/e2.png', 'Blue', 17),
(38, 'Crewman', 'blue/e3.png', 'Blue', 16),
(39, 'Petty Officer Third Class', 'blue/e4.png', 'Blue', 15),
(40, 'Petty Officer Second Class', 'blue/e5.png', 'Blue', 14),
(41, 'Petty Officer First Class', 'blue/e6.png', 'Blue', 13),
(42, 'Chief Petty Officer', 'blue/e7.png', 'Blue', 12),
(43, 'Senior Chief Petty Officer', 'blue/e8.png', 'Blue', 11),
(44, 'Master Chief Petty Officer', 'blue/e9.png', 'Blue', 10),
(45, 'Warrant Officer', 'blue/wo1.png', 'Blue', 8),
(46, 'Chief Warrant Officer 1', 'blue/wo2.png', 'Blue', 7),
(47, 'Chief Warrant Officer 2', 'blue/wo3.png', 'Blue', 7),
(48, 'Chief Warrant Officer 3', 'blue/wo4.png', 'Blue', 6),
(49, 'Private 2nd Class', 'green/e1.png', 'Green', 18),
(50, 'Private 1st Class', 'green/e2.png', 'Green', 17),
(51, 'Lance Corporal', 'green/e3.png', 'Green', 16),
(52, 'Corporal', 'green/e4.png', 'Green', 15),
(53, 'Sargeant', 'green/e5.png', 'Green', 14),
(54, 'Staff Sargeant', 'green/e6.png', 'Green', 13),
(55, 'Gunnery Sargeant', 'green/e7.png', 'Green', 12),
(56, 'Master Sargeant', 'green/e8.png', 'Green', 11),
(57, 'Sargeant Major', 'green/e9.png', 'Green', 10),
(58, 'Warrant Officer', 'green/wo1.png', 'Green', 8),
(59, 'Chief Warrant Officer 1', 'green/wo2.png', 'Green', 7),
(60, 'Chief Warrant Officer 2', 'green/wo3.png', 'Green', 7),
(61, 'Chief Warrant Officer 3', 'green/wo4.png', 'Green', 6),
(62, 'Crewman Recruit', 'red/e1.png', 'Red', 18),
(63, 'Crewman Apprentice', 'red/e2.png', 'Red', 17),
(64, 'Crewman', 'red/e3.png', 'Red', 16),
(65, 'Petty Officer Third Class', 'red/e4.png', 'Red', 15),
(66, 'Petty Officer Second Class', 'red/e5.png', 'Red', 14),
(67, 'Petty Officer First Class', 'red/e6.png', 'Red', 13),
(68, 'Chief Petty Officer', 'red/e7.png', 'Red', 12),
(69, 'Senior Chief Petty Officer', 'red/e8.png', 'Red', 11),
(70, 'Master Chief Petty Officer', 'red/e9.png', 'Red', 10),
(71, 'Warrant Officer', 'red/wo1.png', 'Red', 8),
(72, 'Chief Warrant Officer 1', 'red/wo2.png', 'Red', 7),
(73, 'Chief Warrant Officer 2', 'red/wo3.png', 'Red', 7),
(74, 'Chief Warrant Officer 3', 'red/wo4.png', 'Red', 6),
(75, 'Crewman Recruit', 'white/e1.png', 'White', 18),
(76, 'Crewman Apprentice', 'white/e2.png', 'White', 17),
(77, 'Crewman', 'white/e3.png', 'White', 16),
(78, 'Petty Officer Third Class', 'white/e4.png', 'White', 15),
(79, 'Petty Officer Second Class', 'white/e5.png', 'White', 14),
(80, 'Petty Officer First Class', 'white/e6.png', 'White', 13),
(81, 'Chief Petty Officer', 'white/e7.png', 'White', 12),
(82, 'Senior Chief Petty Officer', 'white/e8.png', 'White', 11),
(83, 'Master Chief Petty Officer', 'white/e9.png', 'White', 10),
(84, 'Warrant Officer', 'white/wo1.png', 'White', 8),
(85, 'Chief Warrant Officer 1', 'white/wo2.png', 'White', 7),
(86, 'Chief Warrant Officer 2', 'white/wo3.png', 'White', 7),
(87, 'Chief Warrant Officer 3', 'white/wo4.png', 'White', 6),
(88, 'Crewman Recruit', 'yellow/e1.png', 'Yellow', 18),
(89, 'Crewman Apprentice', 'yellow/e2.png', 'Yellow', 17),
(90, 'Crewman', 'yellow/e3.png', 'Yellow', 16),
(91, 'Petty Officer Third Class', 'yellow/e4.png', 'Yellow', 15),
(92, 'Petty Officer Second Class', 'yellow/e5.png', 'Yellow', 14),
(93, 'Petty Officer First Class', 'yellow/e6.png', 'Yellow', 13),
(94, 'Chief Petty Officer', 'yellow/e7.png', 'Yellow', 12),
(95, 'Senior Chief Petty Officer', 'yellow/e8.png', 'Yellow', 11),
(96, 'Master Chief Petty Officer', 'yellow/e9.png', 'Yellow', 10),
(97, 'Warrant Officer', 'yellow/wo1.png', 'Yellow', 8),
(98, 'Chief Warrant Officer 1', 'yellow/wo2.png', 'Yellow', 7),
(99, 'Chief Warrant Officer 2', 'yellow/wo3.png', 'Yellow', 7),
(100, 'Chief Warrant Officer 3', 'yellow/wo4.png', 'Yellow', 6),
(101, 'Private 2nd Class', 'black/e1.png', 'Black', 18),
(102, 'Private 1st Class', 'black/e2.png', 'Black', 17),
(103, 'Lance Corporal', 'black/e3.png', 'Black', 16),
(104, 'Corporal', 'black/e4.png', 'Black', 15),
(105, 'Sargeant', 'black/e5.png', 'Black', 14),
(106, 'Staff Sargeant', 'black/e6.png', 'Black', 13),
(107, 'Gunnery Sargeant', 'black/e7.png', 'Black', 12),
(108, 'Master Sargeant', 'black/e8.png', 'Black', 11),
(109, 'Sargeant Major', 'black/e9.png', 'Black', 11),
(110, 'Warrant Officer', 'black/wo1.png', 'Black', 8),
(111, 'Chief Warrant Officer 1', 'black/wo2.png', 'Black', 7),
(112, 'Chief Warrant Officer 2', 'black/wo3.png', 'Black', 7),
(113, 'Chief Warrant Officer 3', 'black/wo4.png', 'Black', 6),
(114, 'Crewman Recruit', 'grey/e1.png', 'Grey', 18),
(115, 'Crewman Apprentice', 'grey/e2.png', 'Grey', 17),
(116, 'Crewman', 'grey/e3.png', 'Grey', 16),
(117, 'Petty Officer Third Class', 'grey/e4.png', 'Grey', 15),
(118, 'Petty Officer Second Class', 'grey/e5.png', 'Grey', 14),
(119, 'Petty Officer First Class', 'grey/e6.png', 'Grey', 13),
(120, 'Chief Petty Officer', 'grey/e7.png', 'Grey', 12),
(121, 'Senior Chief Petty Officer', 'grey/e8.png', 'Grey', 11),
(122, 'Master Chief Petty Officer', 'grey/e9.png', 'Grey', 10),
(123, 'Warrant Officer', 'grey/wo1.png', 'Grey', 8),
(124, 'Chief Warrant Officer 1', 'grey/wo2.png', 'Grey', 7),
(125, 'Chief Warrant Officer 2', 'grey/wo3.png', 'Grey', 7),
(126, 'Chief Warrant Officer 3', 'grey/wo4.png', 'Grey', 6),
(140, 'Crewman Recruit', 'teal/e1.png', 'Teal', 18),
(141, 'Crewman Apprentice', 'teal/e2.png', 'Teal', 17),
(142, 'Crewman', 'teal/e3.png', 'Teal', 16),
(143, 'Petty Officer Third Class', 'teal/e4.png', 'Teal', 15),
(144, 'Petty Officer Second Class', 'teal/e5.png', 'Teal', 14),
(145, 'Petty Officer First Class', 'teal/e6.png', 'Teal', 13),
(146, 'Chief Petty Officer', 'teal/e7.png', 'Teal', 12),
(147, 'Senior Chief Petty Officer', 'teal/e8.png', 'Teal', 11),
(148, 'Master Chief Petty Officer', 'teal/e9.png', 'Teal', 10),
(149, 'Warrant Officer', 'teal/wo1.png', 'Teal', 8),
(150, 'Chief Warrant Officer 1', 'teal/wo2.png', 'Teal', 7),
(151, 'Chief Warrant Officer 2', 'teal/wo3.png', 'Teal', 7),
(152, 'Chief Warrant Officer 3', 'teal/wo4.png', 'Teal', 6),
(179, '', 'white/blank.png', 'White(Civilian)', 20),
(159, 'Ensign', 'grey/ens.png', 'Grey', 9),
(160, 'Lieutenant (JG)', 'grey/ltjg.png', 'Grey', 8),
(161, 'Lieutenant', 'grey/lt.png', 'Grey', 7),
(162, 'Lieutenant', 'grey/lt.png', 'Grey', 7),
(163, 'Lieutenant Commander', 'grey/lcdr.png', 'Grey', 6),
(164, 'Commander', 'grey/cdr.png', 'Grey', 5),
(165, 'Captain', 'grey/cpt.png', 'Grey', 4),
(166, 'Ensign', 'white/ens.png', 'White', 9),
(167, 'Lieutenant (JG)', 'white/ltjg.png', 'White', 8),
(168, 'Lieutenant', 'white/lt.png', 'White', 7),
(169, 'Lieutenant', 'white/lt.png', 'White', 7),
(170, 'Lieutenant Commander', 'white/lcdr.png', 'White', 6),
(171, 'Commander', 'white/cdr.png', 'White', 5),
(172, 'Captain', 'white/cpt.png', 'White', 4),
(173, '1st Lieutenant', 'black/1lt.png', 'Black', 8),
(174, '2nd Lieutenant', 'black/2lt.png', 'Black', 9),
(175, 'Captain', 'black/cpt.png', 'Black', 7),
(176, 'Major', 'black/maj.png', 'Black', 6),
(177, 'Lieutenant Colonol', 'black/lcol.png', 'Black', 5),
(178, 'Colonol', 'black/col.png', 'Black', 4),
(180, 'Commodore', 'red/comm.png', 'Red', 3),
(181, 'Rear Admiral', 'red/radm.png', 'Red', 2),
(182, 'Vice Admiral', 'red/vadm.png', 'Red', 1),
(183, 'Admiral', 'red/adm.png', 'Red', 0)";
mysql_query($query20) or die(mysql_error());// or die("Installation Failed, data input failure");

//Setup styles table info - required
$query21 = "INSERT INTO " . $table['styles'] . " (`sid`, `name`, `color`) VALUES (1, 'commandBanner', '#CD1105'),
(2, 'servicesBanner', '#F79702'),
(3, 'scimedBanner', '#327B81'),
(4, 'marineBanner', '#137012'),
(5, 'diplomaticBanner', '#8229A7'),
(6, 'starfighterBanner', '#0E36B8'),
(7, 'intellBanner', '#4d4d4d'),
(8, 'civilianBanner', '#FFFFFF')";
mysql_query($query21) or die(mysql_error());// or die("Installation Failed, data input failure");

//Setup awarded table - optional
$query22 = "CREATE TABLE " . $table['awarded'] . " (
  `aid` int(11) NOT NULL auto_increment,
  `crewid` int(11) NOT NULL default '0',
  `awardid` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `reason` text NOT NULL,
  `crewm` text NOT NULL,
  PRIMARY KEY  (`aid`)
)";
if($mod_awards) { mysql_query($query22) or die(mysql_error()); }

//setup awards table- optional
$query23 = "CREATE TABLE " . $table['awards'] . " (
  `awardid` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `auto` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY  (`awardid`)
)";
if($mod_awards) { mysql_query($query23) or die(mysql_error()); }

//setup missions table - optional
$query24 = "CREATE TABLE " . $table['missions'] . " (
  `missionid` int(11) NOT NULL auto_increment,
  `mname` text NOT NULL,
  `desc` text NOT NULL,
  `start` int(11) NOT NULL default '0',
  `finish` int(11) NOT NULL default '0',
  PRIMARY KEY  (`missionid`)
)";
if($mod_missions) { mysql_query($query24) or die(mysql_error()); }

//setup news table - now installed by defaul
$query25 = "CREATE TABLE " . $table['news'] . " (
  `newsid` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `poster` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`newsid`)
)";
mysql_query($query25) or die(mysql_error());

//setup ship specs table - optional
$query26 = "CREATE TABLE " . $table['specs'] . " (
  `statid` int(11) NOT NULL auto_increment,
  `sname` text NOT NULL,
  `value` text NOT NULL,
  `sord` int(11) NOT NULL default '0',
  `multil` int(11) NOT NULL default '0',
  PRIMARY KEY  (`statid`)
)";
if($mod_specs) { mysql_query($query26) or die(mysql_error()); }

//setup default awards (from OF awards)
$query27 = "INSERT INTO " . $table['awards'] . " (`awardid`, `name`, `desc`, `auto`, `image`) VALUES (1, 'Example Award', 'This is an Example Award', '', 'purple.jpg')";

if($mod_awards) { mysql_query($query27) or die(mysql_error()); }

//Setup default stat set (OF sovereign)
$query28 = "INSERT INTO " . $table['specs'] . " (`statid`, `sname`, `value`, `sord`, `multil`) VALUES (1, 'Specifications For The Sovereign Class USS Testing', '', 1, 0),
(2, 'Personnel', '', 2, 0),
(3, 'Officers:', '150', 3, 0),
(4, 'Enlisted Crew:', '500', 4, 0),
(5, 'Passengers:', '150', 5, 0),
(6, 'Expected Duration:', '120 years', 6, 0),
(7, 'Time Between Resupply:', '1 year', 7, 0),
(8, 'Time Between Refit:', '10 Years', 8, 0),
(9, 'Marines:', '216', 9, 0),
(10, 'Speed', '', 10, 0),
(11, 'Cruising Velocity:', 'Warp 8', 11, 0),
(12, 'Maximum Velocity:', 'Warp 9.9', 12, 0),
(13, 'Emergency Velocity:', 'Warp 9.99 (for 36 hours)', 13, 0),
(14, 'Dimensions', '', 14, 0),
(15, 'Length:', '685.2 metres', 15, 0),
(16, 'Width:', '275 metres', 16, 0),
(17, 'Height:', '120 metres', 17, 0),
(18, 'Decks:', '24', 18, 0),
(19, 'Auxiliary Craft', '', 19, 0),
(20, 'Shuttlebays:', '2', 20, 0),
(21, 'Fighters', '', 21, 0),
(22, 'Broadsword Multi-role Assault Fighter:', '4', 22, 0),
(23, 'Razor Interceptor:', '6', 23, 0),
(24, 'Runabouts', '', 24, 0),
(25, 'Captains Yacht - Sovereign Runabout:', '1', 25, 0),
(26, 'Danube Runabout:', '2', 26, 0),
(27, 'Delta Flyer Runabout:', '1', 27, 0),
(28, 'Shuttles', '', 28, 0),
(29, 'Type 11 Shuttle:', '2', 29, 0),
(30, 'Type 8 Shuttle:', '2', 30, 0),
(31, 'Type 9 Shuttle:', '2', 31, 0),
(32, 'Wyvern Hopper Transport:', '2', 32, 0),
(33, 'Armament\r\n', '', 33, 0),
(34, 'Defensive Systems', '', 34, 0),
(35, '', 'Ablative Armour', 35, 0),
(36, '', 'Cloaking Device', 36, 0),
(37, 'Phasers', '', 37, 0),
(38, 'Type XII Array:', '12', 38, 0),
(39, 'Shielding Systems', '', 39, 0),
(40, '', 'Auto-Modulating Shields', 40, 0),
(41, '', 'Metaphasic Shielding', 41, 0),
(42, '', 'Regenerative Shielding', 42, 0),
(43, 'Torpedoes', '', 43, 0),
(44, 'Burst-Fire Torpedo Launcher:', '4', 44, 0),
(45, 'Photon Torpedoes:', '300', 45, 0),
(46, 'Quantum Torpedoes: ', '150', 46, 0),
(47, '', 'Rapid-Fire Quantum Torpedo Turret', 47, 0),
(48, 'Quantum Torpedoes:', '280', 48, 0),
(49, 'Tri-Cobalt Devices:', '20', 49, 0),
(50, 'Description', 'Its the sovereign Class!', 50, 1),
(51, '', 'These stats are borrowed from the Obsidian Fleet HQ, please do not copy. Here for example purposes only.', 51, 0)";

if($mod_specs) { mysql_query($query28) or die(mysql_error()); }

//setup default news story
$query29 = "INSERT INTO " . $table['news'] . " (`newsid`, `title`, `content`, `poster`, `date`) VALUES (1, 'First News Article', 'This is the setup news story for TRSM, change/delete at will =)', 'Local Administrator', " . time() . ")";
mysql_query($query29) or die(mysql_error());



if(empty($alone)) {
?>
<center>
<h1>Trek RPG Site Manager 1.0 Installed</h1>
<img src="images/trsm_logo_large.gif">
<h3>Congrads, it seems there was no errors...and TRSM is now installed.</h3>
</center>
<p>
<u>Please refer to the manual for further questions</u><br>

</p>
TRSM is Copyright (C) 2003-2005  Nolanator<br>
<br>
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or(at your option) any later version.

<?
}
mysql_close();
?>