<?php
// Datatables functions
function getCurrentSection() {
	$section_array = explode("-", $_SERVER['PHP_SELF']);
	$page_array = explode(".",$section_array[1]);
    return $page_array[0];
}
function DTImageLinks($dest_path, $files) {
	$return_str = "";
	foreach ($files as $item) {
		$return_str .= "<a href=\"".$dest_path."'+oObj.aData[0]+'".$item["suffix"].".".$item["finalext"]."?rnd='+rnd+'\" target=\"_blank\" class=\"dtLink\">".$item["alias"]."</a> | ";
		if (is_array($item["variants"])) {
			foreach ($item["variants"] as $variant) {			
				$return_str .= "<a href=\"".$dest_path."'+oObj.aData[0]+'".$variant["suffix"].".".$item["finalext"]."?rnd='+rnd+'\" target=\"_blank\" class=\"dtLink\">".$variant["alias"]."</a> | ";				
			}
		}
		if ($return_str != "") {
			$return_str = substr_replace($return_str, "", -3);
		}
	}
	return $return_str;
}

// Password functions
function createPassword($length) {
	$chars = "1234567890abcdefghijklmnopqrstuvwxyz";
	$i = 0;
	$password = "";
	while ($i < $length) {
		$password .= $chars{mt_rand(0,(strlen($chars)-1))};
		$i++;
	}
	return $password;
}
?>