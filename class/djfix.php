<?
if (!get_cfg_var('register_globals')){
if (is_array($HTTP_POST_VARS) and count($HTTP_POST_VARS)>0) foreach($HTTP_POST_VARS as $key=>$ht) { $$key=$ht; }
if (is_array($HTTP_GET_VARS) and count($HTTP_GET_VARS)>0) foreach($HTTP_GET_VARS as $key=>$ht) { $$key=$ht; }
if (is_array($HTTP_COOKIE_VARS) and count($HTTP_COOKIE_VARS)>0) foreach($HTTP_COOKIE_VARS as $key=>$ht) { $$key=$ht; }
}
?>