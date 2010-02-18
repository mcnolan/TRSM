<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: info.php
| Build 2
| <Changes>
| Css Changes
|
| <Purpose>
| Popups giving information about clicked items
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
include_once "inc.inc";
switch($_GET['info']) {
    case "pos" :
        if(!empty($_GET['posid'])) {
            $pos = new position;
            $dep = new department;
            $pos->get($_GET['posid']);
            $dep->get($pos->department);
            ?>
            <html><head><title><? echo reformatData($pos->name); ?> Information</title><? include "styles.php"; ?></head><body>
            <table width="100%">
            <tr>
                <td class="<? echo $dep->css; ?>"><? echo reformatData($pos->name); ?></td>
            </tr><tr>
                <td class="deptName"><? echo reformatData($dep->name); ?> Department</td>
            </tr>
                <tr><td class="positionInfo">
            <? echo reformatData(nl2br($pos->desc)); ?>
                </td>
            </tr>
            </table>
            <br><center><a href="javascript:self.close();">Close</a></center>
            </body></html>
            <?
        }
    break;
    case "awdees" :
        $awd = new awards;
        $awd->get($_GET['awdid']);
        ?>
        <html><head><title><? echo reformatData($awd->name); ?> Awardees</title><? include "styles.php"; ?></head><body>
        <p class="awardeeHeader" align="center">
            <img src="<? echo $imagepath . "awards/" . $awd->image; ?>" border="0"><br>
            <? echo reformatData($awd->name); ?>
        </p>
        <p>
            <span class="popupSubHead">Awardees</span><br>
            <span class="awardees">
        <?
        $awdees = "SELECT first_n, last_n, rank.name as rname, crew.crewid AS cid, crewm FROM " . $table['awarded'] . " AS awarded LEFT JOIN " . $table['crew'] . " AS crew ON(crew.crewid = awarded.crewid) LEFT JOIN " . $table['ranks'] . " AS rank ON(rank.rankid = crew.rankid) WHERE awarded.awardid = '" . $_GET['awdid'] . "' AND date < '" . time() . "'";
        $res = $sql->queryme($awdees);
        while($crew = $sql->sql_array($res)) {
            if($crew['cid'] != '') { 
                echo $crew['rname'] . " " . reformatData($crew['first_n']) . " " . reformatData($crew['last_n']) . "<br>";
            } else {
                echo reformatData($crew['crewm']);
            }
        }
        ?>
            </span>
        </p>
        <?
    break;
    case "posts" :
        //Needs 2 variables, start (where to start from) & display #
        if(empty($_GET['display'])) { $limit = $display_limit; } else { $limit = $_GET['display']; }
        if(empty($_GET['start'])) { $start = 0; } else { $start = $_GET['start'] * $limit; }
        
        if($_GET['tp'] != 4) {
            $report = new report;
            $cat_title = "time_period" . $_GET['tp'];
            $cat_start = "cat" . $_GET['tp'] . "_start";
            $cat_finish = "cat" . $_GET['tp'];
            $getp = "SELECT * FROM " . $table['posts'] . " AS posts LEFT JOIN " . $table['crew'] . " AS crew ON(posts.crewid = crew.crewid) WHERE crew.crewid = '" . $_GET['crewid'] . "' AND date > '" . $report->$cat_start . "' AND date < '" . $report->$cat_finish . "' ORDER BY date DESC LIMIT $start, $limit";
            $getpt = "SELECT count(postid) FROM " . $table['posts'] . " AS posts LEFT JOIN " . $table['crew'] . " AS crew ON(posts.crewid = crew.crewid) WHERE crew.crewid = '" . $_GET['crewid'] . "' AND date > '" . $report->$cat_start . "' AND date < '" . $report->$cat_finish . "' ORDER BY date DESC";
        } else {
            $cat_title = "at";
            $at = "All Time";
            $getp = "SELECT * FROM " . $table['posts'] . " AS posts LEFT JOIN " . $table['crew'] . " AS crew ON(crew.crewid = posts.crewid) WHERE crew.crewid = '" . $_GET['crewid'] . "' ORDER BY date DESC LIMIT $start,$limit";
            $getpt = "SELECT count(postid) FROM " . $table['posts'] . " AS posts LEFT JOIN " . $table['crew'] . " AS crew ON(crew.crewid = posts.crewid) WHERE crew.crewid = '" . $_GET['crewid'] . "' ORDER BY date DESC";
        }
        $result = $sql->queryme($getp);
        $res2 = $sql->queryme($getpt);
        
        $row = $sql->sql_row($res2);
        $total = ceil($row[0] / $limit);
    ?>
    <html><head><title>Posting Report: <? echo $$cat_title; ?></title><? include "styles.php"; ?></head><body>
    <p class="postsPopupTitle">
        Posts From Reporting Period: <? echo $$cat_title; ?>
    </p>
    <p class="pageNumbers">
        Page: 
    <?
    for($c = 1, $p = 0;$c<=$total;$c++, $p++) {
        echo "<a href=\"info.php?info=posts&tp=" . $_GET['tp'] . "&crewid=" . $_GET['crewid'] . "&start=$p\">" . $c . "</a> ";
    }
    ?>
    </p>
    <table width="95%" align="center">
    <tr class="postsHeader">
        <td>Post Name</td>
        <td>Poster</td>
        <td>Date</td>
    </tr>
    <?
    while($post = $sql->sql_array($result)) {
    ?>
    <tr class="postsPopupRows">
        <td><? echo reformatData($post['subject']); ?></td>
        <td class="postsPoster"><? echo reformatData($post['first_n']) . " " . reformatData($post['last_n']); ?></td>
        <td><? echo date("M j Y",$post['date']); ?></td>
    </tr>
    <tr class="postsComments">
        <td colspan="2"><? echo reformatData(str_replace('_',' ',$post['comments'])); ?>&nbsp;</td>
        <td><? echo $post['points']; ?> Points.</td>
    </tr>
    <?
    if($show_synop) {
    ?>
        <tr class="postsSynop">
            <td colspan="3"><? echo reformatData($post['synop']); ?></td>
        </tr>
    <?
    }
    }
    ?>
    </table>
    <?
    break;
}

?>