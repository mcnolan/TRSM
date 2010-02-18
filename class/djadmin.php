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
| Core admin class for database utility. Provided and powered by Document Juggler.
|
| TRSM1.0 is (c) Nolan 2003-2004, and is covered by the GPL License (See gpl.txt for more information)
*/

//if (!$_DJ_ADMIN_) {header("Location: login.php") ; exit ;}

class manager extends tree
{

	var $cont_table = _DJCONT ;

    function manager()
    {
        $this->tree();
    } // end func

	/******** public methods ************/

	function control_create_root()
	{
		$str .= '<input type="text" name="nm" class="tree_input">' . "\n" ;
		$str .= '<input type="submit" name="create_root" value=" ok " class="tree_btn" onclick="return this.form.nm.value != \'\'">' . "\n" ;
		$str .= '</center>' . "\n" ;
		return $str ;
	} // end func

	function control_lib_full_selected($selected)
	{			
			$str = '<select name="edit" class="tree_input">' . "\n" ;
				$str .= '<option class="highlighted" value="0"'.(!$selected?' SELECTED':'').'>select node' . "</option>\n" ;
				for ( $i=0 ; $i < count($this->root_keys) ; $i++ ) {
					$gen = $this->root_keys[$i].'|0|0|0' ;
					$sel = $selected == $gen ? ' SELECTED' : '' ;
					$str .= '<option value="'.$gen.'" class="disabled"'.$sel.'>' . $this->lib[$this->root_keys[$i]][0][0][2] . "</option>\n" ;
					$str_opt = '' ; $path = '0' ;	
					$this->control_get_tree_full($this->lib[$this->root_keys[$i]],$str_opt,$path,$this->root_keys[$i],$selected) ;
					$str .= $str_opt ;
				} // for
			$str .= '</select>&nbsp;<input type="button" value=" select " onclick="if(this.form.edit.options[this.form.edit.selectedIndex].value != 0){document.location.href = \'admin.php?page=database.php&edit=\' + this.form.edit.options[this.form.edit.selectedIndex].value ;}">&nbsp;<input type="button" value=" delete " onclick="if(this.form.edit.options[this.form.edit.selectedIndex].value != 0 && confirm(\'This will delete the current node with all of its subbranches. Continue?\')){document.location.href = \'admin.php?page=database.php&delete=\' + this.form.edit.options[this.form.edit.selectedIndex].value ;}">' . "\n" ;
		return $str ;
	} // end func

	function control_get_tree_full(&$tree,&$str,&$path,$own_id,$selected = 0)
	{			
		$kids = $this->get_kids($tree,$path); 				
		foreach ($kids as $k => $v){
			$level = substr_count($k,'.') ;
			$w = str_repeat('&nbsp;&nbsp;', $level + 1);
			$gen = $own_id . '|' . ($level + 1) . '|' . $v[0] . '|' . $k ;
			$sel = $selected == $gen ? ' SELECTED' : '' ;
			$str .= str_repeat("\t", $level) . '<option value="'.$gen.'"'.$sel.'>'. $w . $k . ': '. $v[2]. '</option>' . "\n" ;				
			$this->control_get_tree_full($tree,$str,$k,$own_id,$selected) ;
		  } // foreach
	} // end func

	function control_add_node($add_full = 0)
	{
			$button = !$add_full ? 'add_node' : 'add_node_full' ;
			$input = !$add_full ? 'node_name' : 'node_name_full' ;
			$str .= '<input type="text" name="'.$input.'" class="tree_input">' . "\n" ;
			$str .= '<input type="submit" name="'.$button.'" value=" add node" class="tree_btn" onclick="return this.form.'.$input.'.value != \'\'"><br>' . "\n" ;
			if($add_full)
			$str .= '<input type="radio" name="place" value="after" checked> after selected <input type="radio" name="place" value="sub"> sub of selected' . "\n" ;
		return $str ;
	} // end func

	function control_update_node($edit)
	{		
		$vals = explode('|',$edit) ;
		$name = $vals[3] ? $this->get_node_name($this->lib[$vals[0]] , $vals[3]) : $this->lib[$vals[0]][0][0][2];
		$cont = $this->get_node_cont($vals[3] ? $this->get_node_id($this->lib[$vals[0]] , $vals[3]) : $vals[0]) ;
	    $str = <<<EOT
			<table border="0" cellpadding="8" cellspacing="0">
			<tr>
				<td>
					<input type="text" name="nm" class="tree_input" value="$name" size="80" onfocus="this.select()">
					<p>
					<textarea name="cont" rows="16" cols="80" class="tree_input" onfocus="this.select()">$cont</textarea>
					<p>
					<center><input type="submit" name="update_cont" value=" save " onclick="return this.form.nm.value != ''"></center>
				</td>
			</tr>
			</table>
EOT;
			return $str ;
	} // end func

	function control_change_position($selected)
	{
		if($selected == 0) return '' ;
		$vals = explode('|',$selected) ;
		if($vals[1] == 0) return '' ;			
		$tree = &$this->lib[$vals[0]] ;
		$brothers = $this->get_kids($tree,$this->get_parent($tree,$vals[3])) ;
		$ks = array_keys($brothers) ;
		if ($vals[3] != $ks[0]) $opts .= '<option value="'.$selected.'|1">first' . "</option>\n" ;
		for ( $i=0 ; $i < count($ks) ; $i++ ) {
			if($vals[3] == $ks[$i] || $brothers[$ks[$i]][0] == $vals[2] - 1) continue ;
			$add = $vals[2] > $brothers[$ks[$i]][0] ? 1 : 0 ;
			$opts .= '<option value="'.$selected.'|'.($brothers[$ks[$i]][0] + $add).'">after ' . $brothers[$ks[$i]][2] . "</option>\n" ;
		} // for
		if(!$opts) return '' ;
		$str .= 'Place "'.$this->get_node_name($tree,$vals[3]).'"' . "\n" ;
		$str .= '<select name="change" class="tree_input">' . "\n" ;
		$str .= $opts ;
		$str .= '</select> ' . "\n" ;
		$str .= '<input type="submit" name="position" value=" ok " class="tree_btn">' . "\n" ;
		return $str ;
	} // end func


	/******** private methods ************/

	function get_node_cont($id)
	{
	    global $CONN ;
		$ret = $CONN->getitem("SELECT node_cont FROM {$this->cont_table} WHERE node_id = '$id'") ;
		return stripslashes($ret[0]) ;
	} // end func

	function update_node_cont($node,$cont, $name)
	{
	    global $CONN ; $cont = addslashes($cont) ; 
		$vals = explode('|',$node) ; 
		$id = $vals[3] ? $this->get_node_id($this->lib[$vals[0]] , $vals[3]) : $vals[0] ;
		$CONN->query("REPLACE INTO {$this->cont_table} VALUES('$id', '$cont')") ;
		$CONN->query("UPDATE {$this->table} SET node_name = '$name' WHERE node_id= '$id'") ;
	} // end func

} // end class

	$manager = new manager ;
?>