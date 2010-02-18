<?php
function checkUserData($userData, $act) {
global $userRegName, $disallowNames;

$userRegExp = "#^[".$userRegName."]{3,40}\$#";

if (!preg_match($userRegExp,$userData[1]) or in_array($userData[1],$disallowNames)) { return 1; }
elseif ($act=='reg' and !eregi("^[A-Za-z0-9_]{5,32}$", $userData[2])) { return 2; }
elseif ($act=='upd' and $userData[2]!='' and !eregi("^[A-Za-z0-9_]{5,32}$", $userData[2])) { return 2; }
elseif ($userData[2] != $userData[3]) { return 3; }
elseif (!eregi("^[0-9a-z]+([._-][0-9a-z]+)*_?@[0-9a-z]+([._-][0-9a-z]+)*[.][0-9a-z]{2}[0-9A-Z]?[0-9A-Z]?$", $userData[4])) { return 4; }
elseif ($userData[5] != '' and !eregi("^[0-9]*$", $userData[5])) { return 5; }
elseif ($userData[6] != '' and !eregi("^(f|ht)tp[s]?:\/\/[^<>]+$", $userData[6])) { return 6; }
else { return "ok"; }
}
?>