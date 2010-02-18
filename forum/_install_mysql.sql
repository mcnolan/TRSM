#
# Table structure for table 'forums'
#
CREATE TABLE minibb_forums (
forum_id int(10) DEFAULT '1' NOT NULL auto_increment,
forum_name varchar(150) NOT NULL,
forum_desc text NOT NULL,
forum_order int(10) NOT NULL,
forum_icon varchar(255) DEFAULT 'default.gif' NOT NULL,
PRIMARY KEY (forum_id)
);

#
# Table structure for table 'posts'
#
CREATE TABLE minibb_posts (
post_id int(11) DEFAULT '1' NOT NULL auto_increment,
forum_id int(10) DEFAULT '1' NOT NULL,
topic_id int(10) DEFAULT '1' NOT NULL,
poster_id int(10) DEFAULT '0' NOT NULL,
poster_name varchar(40) DEFAULT 'Anonymous' NOT NULL,
post_text text NOT NULL,
post_time datetime NOT NULL,
poster_ip varchar(15) NOT NULL,
post_status tinyint(1) DEFAULT '0' NOT NULL,
# post_status: 0-clear (available for edit), 1-edited by author, 2-edited by admin (available only for admin)
KEY post_id (post_id),
KEY forum_id (forum_id),
KEY topic_id (topic_id),
KEY poster_id (poster_id),
PRIMARY KEY (post_id)
);

#
# Table structure for table 'topics'
#
CREATE TABLE minibb_topics (
topic_id int(10) DEFAULT '1' NOT NULL auto_increment,
topic_title varchar(100) NOT NULL,
topic_poster int(10) DEFAULT '0' NOT NULL,
topic_poster_name varchar(40) DEFAULT 'Anonymous' NOT NULL,
topic_time datetime NOT NULL,
topic_views int(10) DEFAULT '0' NOT NULL,
forum_id int(10) DEFAULT '1' NOT NULL,
topic_status tinyint(1) DEFAULT '0' NOT NULL,
# about status: 0-opened; 1-locked; 9-sticky; 8-sticky&locked
topic_last_post_id int(10) DEFAULT '1' NOT NULL,
KEY topic_id (topic_id),
KEY forum_id (forum_id),
PRIMARY KEY (topic_id)
);

#
# Table structure for table 'users'
#
CREATE TABLE minibb_users (
user_id int(10) DEFAULT '1' NOT NULL auto_increment,
username varchar(40) DEFAULT '' NOT NULL,
user_regdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
user_password varchar(32) DEFAULT '' NOT NULL,
user_email varchar(50) DEFAULT '' NOT NULL,
user_icq varchar(15) DEFAULT '' NOT NULL,
user_website varchar(100) DEFAULT '' NOT NULL,
user_occ varchar(100) DEFAULT '' NOT NULL,
user_from varchar(100) DEFAULT '' NOT NULL,
user_interest varchar(150) DEFAULT '' NOT NULL,
user_viewemail tinyint(1) DEFAULT '0' NOT NULL,
#sort: 0 - not view; 1 - view
user_sorttopics tinyint(1) DEFAULT '1' NOT NULL,
#sort: 1 - new topics; 0 - new answers
user_newpwdkey varchar(32) DEFAULT '' NOT NULL,
user_newpasswd varchar(32) DEFAULT '' NOT NULL,
PRIMARY KEY (user_id)
);

#
# Table structure for table 'send_mails'
#
CREATE TABLE minibb_send_mails (
id int(11) DEFAULT '1' NOT NULL auto_increment,
user_id int(10) DEFAULT '1' NOT NULL,
topic_id int(10) DEFAULT '1' NOT NULL,
PRIMARY KEY (id)
);

#
# Table structure for table 'banned'
#
CREATE TABLE minibb_banned (
id int(10) DEFAULT '1' NOT NULL auto_increment,
banip varchar(15) NOT NULL,
PRIMARY KEY (id)
);
