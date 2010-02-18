<?php 
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: class/djmain.php
| Build 1
| <Changes>
| 
| <Purpose>
| Core Database utility class. Provided and powered by Document Juggler.
|
| TRSM1.0 is (c) Nolan 2003-2004, and is covered by the GPL License (See gpl.txt for more information)
*/

//Contents of func_dj

	class tree
	{		
		var $roots = array() ;		
		var $table = _DJMAIN ;	
		var $cont_table = _DJCONT ;
		var $lib = array() ;
		
	    function tree()
	    {
			global $CONN ; $CONN->FreeMem() ;
	        $this->get_roots() ;
			if (!empty($this->roots)) {
				for ( $i=0 ; $i < count($this->roots) ; $i++ ) {
					$this->lib[$this->roots[$i][id]] = $this->read_tree($this->roots[$i][id]) ;
				} // for
				$this->root_keys = array_keys($this->lib) ;
			} // if
	    } // end func

		function get_roots()
		{
		    global $CONN ;
			$CONN->Query("SELECT node_id , node_name FROM {$this->table} WHERE node_own = '0' ORDER BY node_name") ;
			if(!$CONN->rows) return ;
			for ( $i=0 ; $i < $CONN->rows ; $i++ ) {
				$CONN->Fetch() ;
				$this->roots[$i][id] = $CONN->data[0] ;
				$this->roots[$i][name] = $CONN->data[1] ;
			} // for
		} // end func

		function read_tree($root_id)
		{
		    global $CONN ; $lib = $ret = array() ;
			$CONN->Query("SELECT node_lev , node_pos , node_id , node_name , node_par FROM {$this->table} WHERE node_id = '$root_id' OR node_own = '$root_id' ORDER BY node_lev, node_par, node_pos ") ;
			if(!$CONN->rows) return $lib ;
			$lev= -1; $idpath = array(); $idpathcur = array(); 
			for ( $i=0 ; $i < $CONN->rows ; $i++ ) {
				$CONN->Fetch() ; 
				$lev = $i ? $lev : $CONN->data[0];
				if($lev != $CONN->data[0] && empty($idpath)){
					if(empty($lib)) continue;
					foreach($lib as $k=>$v)
						foreach($v as $k1=>$v1)
							$ret[$lev][$k1] = $v1;
					$idpath = $idpathcur; $idpathcur = $lib = array();
					$lev = $CONN->data[0];
				}//if
				if($lev != $CONN->data[0] && !empty($idpath)){
					foreach($idpath as $k=>$v){
						if(empty($lib[$k])) continue;
						foreach($lib[$k] as $k1=>$v1)
							$ret[$lev][$k1] = $v1;
					}//for
					$idpath = $idpathcur; $idpathcur = $lib = array();
					$lev = $CONN->data[0];
				}//if
				$path = empty($idpath) ? '0' : $idpath[$CONN->data[4]] . ($idpath[$CONN->data[4]] == '' ? '' : '.') . $CONN->data[1];
				$lib[$CONN->data[4]][$path] = array($CONN->data[1], $CONN->data[2],  $CONN->data[3]); 
				$idpathcur[$CONN->data[2]] = $path == '0' ? '' : $path;
				if($i == $CONN->rows -1 ){
					if(empty($idpath) && !empty($lib)){
						foreach($lib as $k=>$v)
							foreach($v as $k1=>$v1)
								$ret[substr_count($k1,'.')][$k1] = $v1;
					}//if
					foreach($idpath as $k=>$v){
						if(empty($lib[$k])) continue;
						foreach($lib[$k] as $k1=>$v1)
							$ret[$lev][$k1] = $v1;
					}//for
				}
			} // for
			return $ret ;
		} // end func

		function get_parent(&$tree , $path)
		{
		    if ($path == '0') return NULL ;
			if (!substr_count($path,'.')) return 0 ;
			return substr($path,0,strlen($path) - strpos(strrev($path),'.') - 1) ;
		} // end func

		function get_node_name(&$tree , $path)
		{
		    if ($path == '0') return  $tree[0]['0'][2] ;
			else $level = substr_count($path,'.') + 1 ;			
			return  $tree[$level][$path][2] ;
		} // end func
		
		function get_node_id(&$tree , $path)
		{
			if ($path == '0') return  $tree[0]['0'][1] ;
			else $level = substr_count($path,'.') + 1 ;			
			return  $tree[$level][$path][1] ;
		} // end func

		function get_node_pos(&$tree , $path)
		{			
			if ($path == '0') return  $tree[0]['0'][0] ;
			else $level = substr_count($path,'.') + 1 ;			
			return  $tree[$level][$path][0] ;
		} // end func

		function get_elements_num(&$tree, $path)
		{
		    return count($this->get_kids($tree, $path)) ;
		} // end func
		
		function get_kid_paths(&$tree,$parent)
		{
			if ($parent == '0') return array_keys($tree[1]);
			$kids = array();
			$level = substr_count($parent,'.') + 2 ;
			if (empty($tree[$level])) return $kids;
			foreach($tree[$level] as $k => $v)
				if(strpos($k, $parent.'.') === 0) $kids[] = $k;
			return $kids;
		} // end func
		
		function get_book_nodes(&$tree, $parent, &$book)
		{
			$book[] = $parent;
		    $kids = $this->get_kid_paths($tree,$parent);
			if(empty($kids)) return;
			foreach($kids as $k=>$v){
				$this->get_book_nodes($tree, $v, $book);
			}
		} // end func
		
		function get_prev_next_nodes(&$tree,$path,&$prev, &$next)
		{
		    $this->get_book_nodes($tree, '0', $book) ;
			$key = array_search($path, $book);
			if($key === false) return 1 ;
			$prev = isset($book[$key-1]) ? $book[$key-1] : '' ;
			$next = isset($book[$key+1]) ? $book[$key+1] : '' ;
			return 0;
		} // end func
		
		function get_kids(&$tree,$parent)
		{
			if ($parent == '0') $level = 1 ;
			else $level = substr_count($parent,'.') + 2 ;
			$kids = array();
			if (empty($tree[$level])) return $kids;
			if ($parent == '0') return $tree[1] ;
			foreach($tree[$level] as $k => $v) if(strpos($k, $parent.'.') === 0) $kids[$k] = $v ;
			return $kids;	
		} // end func

		function get_sub_tree(&$tree , $path)
		{
			if($path == '0') return $tree ;
			$subtree = array() ;
		    if (!empty($tree)) {
		    	for ( $i=0 ; $i < count($tree) ; $i++ ) {
		    		if (!empty($tree[$i])) {
		    			foreach($tree[$i] as $k => $v){
		    				if (strpos($k, $path . '.') === 0 || $k == $path) {
		    					$subtree[$i][$k] = $v ;
		    				} // if
		    			} // foreach
		    		} // if
		    	} // for
		    } // if
			return $subtree ;
		} // end func

		function add_root($name)
		{
		    global $CONN ;
			$ret = $CONN->setitemmod("INSERT INTO {$this->table} VALUES(NULL,'0','0','1','0','$name') ") ;
			$this->tree() ;
			$this->get_roots() ;
			return $ret[error] != 'none' ? $ret[error] : false ;
		} // end func

		function insert_node(&$tree, $vals, $name,$ifsub = 0)
		{
			global $CONN ;
			if ($ifsub) {
				$new_level = $vals[1] +1 ;
				$new_pos = $this->get_elements_num($tree, $vals[3]) + 1 ;
				$new_parent = $this->get_node_id($tree , $vals[3]) ;				
			} // if
			else {
				$new_level = $vals[1] ;
				$new_pos = $vals[2] + 1 ;
				$new_parent = $this->get_node_id($tree , $this->get_parent($tree,$vals[3])) ;
				$this->shift_down($tree,$vals[0],$vals[3],1) ;
			} // else
			$ret = $CONN->setitemmod("INSERT INTO {$this->table} VALUES(NULL,'{$vals[0]}', '$new_level', '$new_pos', '$new_parent','$name') ") ;
			$this->lib[$vals[0]] = $this->read_tree($vals[0]) ;
			return $ret[error] != 'none' ? $ret[error] : array($CONN->instId,$node_pos,$node_path,$parent) ;
		} // end func

		function shift_down(&$tree,$node_own,$path,$step)
		{
			global $CONN ;
			$par = $this->get_parent($tree,$path) ;
		    $sub = $this->get_sub_tree($tree,$par) ;
			$ownlevelids = array() ;
			if (!empty($sub[substr_count($path,'.')+1])) {
				foreach($sub[substr_count($path,'.')+1] as $k => $v){
					if($k > $path) $ownlevelids[] = $v[1] ;
				} // foreach
			} // if
			$this->shift_down_single($ownlevelids) ;
		} // end func

		function delete_full_tree($node_own)
		{
		    global $CONN ;
			$ret = $CONN->getitem("SELECT node_id FROM {$this->table} WHERE node_own = '$node_own' OR node_id = '$node_own' ") ;
			if(!empty($ret)) $CONN->Query("DELETE FROM {$this->cont_table} WHERE node_id IN (".implode(',', $ret).") ") ;
			$CONN->Query("DELETE FROM {$this->table} WHERE node_own = '$node_own' OR node_id = '$node_own' ") ;
		} // end func

		function delete_node(&$tree, $vals)
		{			
			global $CONN ;
			$sub = $this->get_sub_tree($tree,$vals[3]) ;
			if (!empty($sub)) {
				$deleteids = array() ;
				foreach($sub as $k => $v){
					foreach($v as $k1 => $v1){
						$deleteids[] = $v1[1] ;
					} // foreach
				} // foreach
				$this->shift_up($tree,$vals[0],$vals[3],1) ;
				if(!empty($deleteids)) {
					$CONN->Query("DELETE FROM {$this->table} WHERE node_id IN (".implode(',', $deleteids).") ") ;
					$CONN->Query("DELETE FROM {$this->cont_table} WHERE node_id IN (".implode(',', $deleteids).") ") ;
				} // if
			} // if
		} // end func
		
		function shift_up(&$tree,$node_own,$path,$step)
		{
		    global $CONN ;
			$par = $this->get_parent($tree,$path) ;
			$sub = $this->get_sub_tree($tree,$par) ;
			if ($path == '0') $level = 0 ;
			else $level = substr_count($path,'.') + 1 ;
			if (!empty($sub)) {
				$shiftids = array() ;
				foreach($sub as $k => $v){
					if($k != $level) continue ;
					foreach($v as $k1 => $v1){
						if ($k1 <= $path)  continue ;
						$shiftids[] = $v1[1] ;
					} // foreach
				} // foreach
				$this->shift_up_single($shiftids) ;
			} // if
		} // end func

		function shift_up_single($shiftids,$step = 1)
		{
			global $CONN ;
		    if(!empty($shiftids)) $CONN->Query("UPDATE {$this->table} SET node_pos = node_pos - $step WHERE node_id IN (".implode(',', $shiftids).") ") ;
		} // end func

		function shift_down_single($shiftids,$step = 1)
		{
			global $CONN ;
		    if(!empty($shiftids)) $CONN->Query("UPDATE {$this->table} SET node_pos = node_pos + $step WHERE node_id IN (".implode(',',$shiftids).") ") ; 
		} // end func

		function change_position($select)
		{
		    $vals = explode('|',$select) ;
			$tree = &$this->lib[$vals[0]] ;
			$brothers = $this->get_kids($tree,$this->get_parent($tree,$vals[3])) ;
			$ks = array_keys($brothers) ;
			if ($vals[4] > $vals[2]) {
				$i = count($ks) - 1 ; $shift = array() ;
				while ($i > $vals[2] - 1) {
					if ($vals[4] < $brothers[$ks[$i]][0]) {$i-- ; continue ;}
					$shift[] = $this->get_node_id($tree,$ks[$i--]) ;
				} // while
				$this->shift_up_single($shift) ;
			} // if
			elseif ($vals[4] < $vals[2]) {
				$i = 0 ; $shift = array() ;
				while ($i != $vals[2] - 1) {
					if ($brothers[$ks[$i]][0] < $vals[4]) {$i++ ; continue ;}
					$shift[] = $this->get_node_id($tree,$ks[$i++]) ;
				} // while
				$this->shift_down_single($shift) ;
			} // elseif
			else {
				return ;
			} // else
			$id = $this->get_node_id($tree,$vals[3]) ;				
			global $CONN ;
			$CONN->Query("UPDATE {$this->table} SET node_pos = '{$vals[4]}' WHERE node_id = '$id' ") ;
			$this->lib[$vals[0]] = $this->read_tree($vals[0]) ; 
		} // end func

	} // end class

//End contents of func_dj

//Contents of func_common

	//session_start() ;
	define('_ME',basename($PHP_SELF));
	define('_TIME',date("Y-m-d H:i:s")) ;
	define('_DJMAIN','djnew_tree') ;
	define('_DJCONT','djnew_cont') ;

	if ($logout) logout() ;

	function login()
	{
		global $_DJ_ADMIN_ , $HTTP_POST_VARS , $DJ_login_name , $DJ_login_password ;		
		if (
			$HTTP_POST_VARS['LOGIN_EMAIL'] == $DJ_login_name && 
			$HTTP_POST_VARS['LOGIN_PASSWORD'] == $DJ_login_password
			) {
			$_DJ_ADMIN_ = 'ok' ;
			session_register('_DJ_ADMIN_') ;
			return true ;
		} // if
		else {
			return false ;
		} // else 		
	} // end func

	function logout()
	{
		global $_DJ_ADMIN_ , $DJ_path ;
		$_DJ_ADMIN_ = 0 ; session_destroy() ; header('Location: '.$DJ_path.'/index.php') ; exit() ;
	} // end func

if (isset($HTTP_POST_VARS['_DJ_ADMIN_']) || isset($HTTP_GET_VARS['_DJ_ADMIN_'])) {
	session_destroy() ; 
	header('Location: '.$DJ_path.'/index.php') ; exit() ;
} // if
if ($HTTP_POST_VARS['LOGIN_EMAIL']) {
	if(login()) {header("Location: index.php") ; exit ;}
} // if

class Connection {
	var $host, $user, $pass, $db, $id, $result, $rows, $data, $aftRows , $instId , $query , $tb_names , $crpt ;

	function Connection($main_host, $main_database, $main_user, $main_password, $main_crypt) {
		$this->host = $main_host ; 
		$this->user = $main_user ; 
		$this->pass = $main_password ; 
		$this->db = $main_database ; 
		$this->crpt = $main_crypt ; 
		$this->id = mysql_pconnect($this->host,$this->user,$this->pass); 
		mysql_select_db($this->db,$this->id) ; 
		$result = mysql_list_tables ($this->db); $i = 0;
        while ($i < mysql_num_rows ($result)) {$this->tb_names[$i] = mysql_tablename ($result, $i); $i ++;}
	}
    function query ($query) {	
		$this->query = $query ; 
		$this->result = @mysql_query($query,$this->id); 
		$this->rows = @mysql_num_rows($this->result); 
		$this->aftRows = @mysql_affected_rows($this->id); 
		$this->instId = @mysql_insert_id($this->id) ;
    }
	function fetch(){ $this->data = @mysql_fetch_array($this->result);}
	function freemem(){ @mysql_free_result($this->result) ; } 
	function getitem($str)
	{
	    $this->Query($str) ; $data = array() ;
		if(!$this->rows) return $data ;		
		$this->Fetch();
		for ( $j=0 ; $j < count($this->data)/2 ; $j++ ) $data[$j] = $this->data[$j];		
		$this->FreeMem() ;
		return $data ;
	} // end func
	function getallitems($str)
	{
	    $this->Query($str) ; $data = array() ;
		if(!$this->rows) return $data ;		
		for ( $i=0 ; $i < $this->rows ; $i++ ) {
			$this->Fetch();
			for ( $j=0 ; $j < count($this->data)/2 ; $j++ ) $data[$i][] = $this->data[$j];			
		} // for
		$this->FreeMem() ;
		return $data ;	    
	} // end func
	function setitemmod($str) 
	{ 
	    $this->Query($str) ; $data = array() ;
		$data['affected_rows'] = $this->aftRows ;
		$data['last_insert_id'] = $this->instId ? $this->instId : NULL ;
		$e = @mysql_error() ;
		$data['error'] = $e ? $e : 'none' ;
		$this->FreeMem() ; return $data ;
	} // end func
} // class

	$CONN = new Connection($DJ_host, $DJ_database, $DJ_user, $DJ_password, $DJ_crypt) ;

//End contents of func_common

class dj extends tree
{
    
	var $tree ;
	var $r , $n ;
	var $cont_table = _DJCONT ;

	function dj()
    {
		global $r , $n ;
		$this->r = $_GET['r'] ? $_GET['r'] : 0 ;
		$this->n = $_GET['n'] ? $_GET['n'] : 0 ;
		$this->tree() ;
    } // end func

	/********** public methods ************/
	
	function get_catalogindex($type) 
	{
	    if (empty($this->roots)) return '' ;
		$delim = '' ; 
		switch ($type) {
		    case 1 :
				$delim = '&nbsp;&nbsp;' . "\n" ;
		        break ;
			case 2 :
				$delim = '<br>' . "\n" ;
				break ;
		} // switch
		for ( $i=0 ; $i < count($this->roots) ; $i++ ) {
			$str .= '<a href="'._ME.'?r='.$this->roots[$i][id].'" class="catalogindex">'.$this->roots[$i][name].'</a>' . $delim ;
		} // for
		$str = '<a href="'._ME.'" class="catalogindex">Home</a>' . "\n" . $delim . $str ;
		return $str ;
	} // end func

	function get_rubricname()
	{
	    if (!$this->n && !$this->r) {
			global $DJ_appname ;
	    	$str = $DJ_appname ;
	    } // if
		elseif ($this->r && !$this->n) {
			$str = $this->lib[$this->r][0][0][2] ;
		} // elseif
		elseif ($this->r && $this->n) {
			$str = $this->lib[$this->r][substr_count($this->n , '.') +1][$this->n][2] ;			
		} // else
		return '<span class="rubricname">' . $str . "</span>\n" ;
	} // end func

	function get_currentpath()
	{
	    $str = '' ;
		if (!$this->n && !$this->r) {
			return $str ;
	    } // if
		elseif ($this->r && !$this->n) {
			$path = '0' ;
			$this->get_full_tree($this->lib[$this->r], $path, $this->r, $str) ;
		} // elseif
		elseif ($this->r && $this->n) {
			$par = $this->get_parent($this->lib[$this->r] , $this->n) ; $par_lev = substr_count($this->n,'.') ;
			$class = $par ? 'class="level'. $par_lev . '"' : 'class="level0"' ;
			$str = '<a href="'._ME.'?r='.$this->r.'&n='. $par .'"'.$class.'>' . ($par ? $par . '. ' : '') . $this->get_node_name($this->lib[$this->r] , $par).'</a>' . "<br>\n" ;
			$str .= '&nbsp;&nbsp;<a href="'._ME.'?r='.$this->r.'&n='. $this->n .'" class="level'. ($par_lev+1) .'">' . $this->n . '. ' .$this->get_node_name($this->lib[$this->r] , $this->n).'</a>' . "<br>\n" ;
		} // else
		return $str ;
	} // end func

	function get_links()
	{
	    $str = '' ;
		if (!$this->n && !$this->r) {
			return $str ;
	    } // if
		elseif ($this->r && !$this->n) {						
			return $str ;
		} // elseif
		elseif ($this->r && $this->n) {
			
			$home = _ME ; $prev = $next = '&nbsp;' ;			
			$rt = $this->get_prev_next_nodes($this->lib[$this->r],$this->n, $prev, $next) ;
			
			if ($prev) {
				$name = $this->get_node_name($this->lib[$this->r] , $prev) ;
				$nm = strlen($name) > 32 ? substr($name,0,30) . ' ...' : $name ;
				$prevlink = '<a href="'._ME.'?r='.$this->r.'&n='.$prev.'" class="navlinks"  title="'.$name.'">[&lt;&lt; '.$nm.']</a>' ;	
			} // if
			
			if ($next) {
				$name = $this->get_node_name($this->lib[$this->r] , $next) ;
				$nm = strlen($name) > 32 ? substr($name,0,30) . ' ...' : $name ;
				$nextlink = '<a href="'._ME.'?r='.$this->r.'&n='.$next.'" class="navlinks" title="'.$name.'">['.$nm.' &gt;&gt;]</a>' ;
			} // if

			$str = <<<EOT
				<table width="80%" border="0" cellspacing="0" cellpading="0">
				<tr>
					<td width="25%" align="left">$prevlink</td>
					<td width="25%" align="center"><a href="$home" class="navlinks">[Home]</a></td>
					<td width="25%" align="center"><a href="$home?r={$this->r}" class="navlinks">[Content]</a></td>
					<td width="25%" align="right">$nextlink</td>
				</tr>
				</table>
				<p>
EOT;
		} // else
		return $str ;
	} // end func

	function get_content()
	{
	    $str = '' ;
		if (!$this->n && !$this->r) {
			if(!empty($this->roots))
			for ( $i=0 ; $i < count($this->roots) ; $i++ ) {
				$str .= '<span class="rubric">' . $this->roots[$i][name] . "</span>\n" ;
				$str .= "<p>\n" ;
				$str .= $this->get_page($this->roots[$i][id]) . "\n" ;
			} // for			
	    } // if
		elseif ($this->r && !$this->n) {
			return $str ;
		} // elseif
		elseif ($this->r && $this->n) {
			$str = $this->get_page($this->get_node_id($this->lib[$this->r], $this->n)) . "<p>\n" ;
		} // else
		return $str ;
	} // end func

	function get_credits()
	{
	    return '	<hr><small><strong>Powered By Document Juggler 2</strong> - &copy; Oleksandr Missa and Valentyn Stashko, DDT Studio, 2000-2002<br>mailto: info AT ddtstudio DOT de</small>' ;
	} // end func

	/********** private methods ************/

	function get_page($id)
	{
		global $CONN ;
		$ret = $CONN->getitem("SELECT node_cont FROM {$this->cont_table} WHERE node_id = '$id'") ;
		return stripslashes($ret[0]) . "<p>\n" ;
	} // end func

	function get_full_tree(&$tree, &$path, $own_id, &$str)
	{
		$kids = $this->get_kids($tree,$path);
		foreach ($kids as $k => $v){
			$level = substr_count($k, '.') ;
			$w = str_repeat('&nbsp;&nbsp;', $level + 1);
			$gen = $own_id . '|' . ($level + 1) . '|' . $v[0] . '|' . $k ;	
			$str .= str_repeat("\t", $level) . $w . '<a href="'._ME.'?r='. $own_id .'&n=' . $k . ' " class="level'. ($level+1) . '">' . $k . '.&nbsp;' . $v[2]. "</a><br>\n" ;
			$this->get_full_tree($tree, $k, $own_id, $str) ;
		  } // foreach
	} // end func

} // end class

	$DJ = new dj ;
?>