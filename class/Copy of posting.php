<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: class/posting.php
| Build 2
| <Changes>
| Fixed error that caused the 'Last Week' timeframe to stop working
|
| <Purpose>
| Core posting report class (Version .2c)
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/

class points {
    
    var $ptid;
    var $ptdesc;
    var $ptnum;
    
    function get($id) {
        global $sql, $table;
        $get = "SELECT * FROM " . $table['points'] . " AS points WHERE ptid = '$id'";
        $res = $sql->queryme($get);
        $pt = $sql->sql_array($res);
        $this->ptid = $pt['ptid'];
        $this->ptdesc = $pt['pcomm'];
        $this->ptnum = $pt['pchange'];
    }
    function add() {
        global $sql, $table;
        $query = "INSERT INTO " . $table['points'] . "(pchange, pcomm) VALUES ('" . $this->ptnum . "', '" . $this->ptdesc . "')";
        $sql->insquery($query);
        $this->ptid = $sql->lastid;
    }
    function edit() {
        global $sql, $table;
        $query = "UPDATE " . $table['points'] . " SET pchange = '" . $this->ptnum . "', pcomm = '" . $this->ptdesc . "' WHERE ptid = '" . $this->ptid . "'";
        $sql->queryme($query);
    }
    function remove($id) {
        global $sql, $table;
        $query = "DELETE FROM " . $table['points'] . " WHERE ptid = '$id'";
        $sql->queryme($query);
    }
}

class posts {
    
    var $subject;
    var $synop;
    var $missionid;
    var $crewid;
    var $date;
    var $comments;
    var $points;
    
    function add() {
        global $sql, $table;
        $addq = "INSERT INTO " . $table['posts'] . "(subject, synop, missionid, crewid, date, comments, points) VALUES ('" . $this->subject . "', '" . $this->synop . "', '" . $this->missionid . "', '" . $this->crewid . "', '" . $this->date . "', '" . $this->comments . "', '" . $this->points . "')";
        $sql->insquery($addq);
        return $sql->lastid;
    }
    
    function removebycrew($crewid) {
        global $sql, $table;
        $remove = "DELETE FROM " . $table['posts'] . " WHERE crewid = '$crewid'";
        $sql->queryme($remove);
    }
    
    function update_ondel($crewid, $cname) {
        global $sql, $table;
        $upd = "UPDATE " . $table['posts'] . " SET crewid = '0', crewm = '$cname' WHERE crewid = '$crewid'";
        $sql->queryme($upd);
    }
}

class report {
    
    var $cat1;
    var $cat1_start = 1;
    var $cat1_posts = 0;
    var $cat2;
    var $cat2_start = 1;
    var $cat2_posts = 0;
    var $cat3;
    var $cat3_start = 1;
    var $cat3_posts = 0;
    var $total;
    var $point_total = 0;
	var $totalcat1 = 0;
	var $totalcat2 = 0;
	var $totalcat3 = 0;
    
    function generate($crewid) {
        global $sql, $mod_records, $table;
        $mpquery = "SELECT * FROM " . $table['posts'] . " AS posts WHERE crewid = '" . $crewid . "'";
        $result = $sql->queryme($mpquery);
        $this->clear();
        while($posts = $sql->sql_array($result)) {
            //Cycle through all posts, and make running counts on how much posts are in each cat, and do a running point total too!
            if(($posts['date'] > $this->cat1_start) && ($posts['date'] < $this->cat1)) {
                $this->cat1_posts++;
				$this->totalcat1++;
            }
            if(($posts['date'] > $this->cat2_start) && ($posts['date'] < $this->cat2)) {
                $this->cat2_posts++;
				$this->totalcat2++;
            }
            if(($posts['date'] > $this->cat3_start) && ($posts['date'] < $this->cat3)) {
                $this->cat3_posts++;
				$this->totalcat3++;
            }
            $this->point_total += $posts['points'];
            $this->total++;
        }
        if($mod_records) {
            $rec = new record;
            $pts = $rec->fetch_pts($crewid);
            $this->point_total += $pts;
        }
    }
    function report() {
        $this->calc_times();
    }
    /*function calc_spc_times($date) {
        /*
          An alternative to the below function, operating on a specific date
        /
        global $time_period1, $time_period2, $time_period3, $first_day;
        $tp = 1;
        while($tp <= 4) {
            switch($tp) {
                case 1:
                    $time = $time_period1;
                break;
                case 2:
                    $time = $time_period2;
                    $this->cat1 = $last;
                    $this->cat1_start = $first;
                break;
                case 3:
                    $time = $time_period3;
                    $this->cat2 = $last;
                    $this->cat2_start = $first;
                break;
                case 4:
                    $this->cat3 = $last;
                    $this->cat3_start = $first;
                    continue;
                break;
            }
            if(is_string($time)) {
                $time = strtolower($time);
            }
            switch($time) {
                case 'last week' :
                    
                break;
                case 'this week' :
                    
                break;
                case 'two weeks' :
                    
                break;
                case 'last month' :
                    
                break;
                case 'this month' :
                    
                break;
                default:
                    $first = strtotime();
                    $last = 
                break;
            }
        $tp++;
        }
    } */
    function calc_times() {
        /*
        Takes every set time period in the settings file, calculates the time stamps
        required and sets them to the class
        */
        global $time_period1, $time_period2, $time_period3, $first_day, $table;
        $tp = 1;
        while($tp <= 4) {
            switch($tp) {
                case 1:
                    $time = $time_period1;
                break;
                case 2:
                    $time = $time_period2;
                    $this->cat1 = $last;
                    $this->cat1_start = $first;
                break;
                case 3:
                    $time = $time_period3;
                    $this->cat2 = $last;
                    $this->cat2_start = $first;
                break;
                case 4:
                    $this->cat3 = $last;
                    $this->cat3_start = $first;
                    continue;
                break;
            }
            if(is_string($time)) {
                $time = strtolower($time);
            }
            switch($time) {
                case 'last week':
                    //everything between the last 2 given days
                    $last = strtotime("last " . $first_day);
                    $first = $last - (((60 * 60) * 24) * 7);
                break;
                case 'this week':
                    //everything since the given day
                    $since = "last " . $first_day;
                    $first = strtotime($since);
                    $last = time();
                break;
                case 'two weeks':
                    //A combination of this and last week
                    $first = strtotime("last " . $first_day) - (((60 * 60) * 24) * 7);
                    $last = time();
                break;
                case 'last month':
                    //between the first and last days of last month
                    $str = "-1 month";
                    $lmths = strtotime($str);
                    $str2 = "1 " . date("F", $lmths);
                    $first = strtotime($str2);
                    $last = strtotime("1 " . date("F"));
                break;
                case 'this month':
                    //after the first of the month
                    $str = "1 " . date("F");
                    $first = strtotime($str);
                    $last = time();
                break;
                default:
                    //this is the case where someone sets a date, we need to count from that date to today
                    $first = strtotime($time);
                    $last = time();
                break;
            }
        $tp++;
        }
    }
    function clear() {
        $this->total = 0;
        $this->point_total = 0;
        $this->cat1_posts = 0;
        $this->cat2_posts = 0;
        $this->cat3_posts = 0;
        $this->cat4_posts = 0;
    }
}