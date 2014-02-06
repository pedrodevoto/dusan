<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('memory_limit', -1);
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        case 'g':
            $val *= 1024 * 1024 * 1024;
        case 'm':
            $val *= 1024 * 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}
function processArchivo($file, $i = -1) {
	$size = $i>=0?$file['size'][$i]:$file['size'];
	if ($size > return_bytes(ini_get('upload_max_filesize'))) {
		return FALSE;
	}
	$name = $i>=0?$file['name'][$i]:$file['name'];
	$extension_index = strrpos($name, '.');
	$extension = strtolower(strrchr($name, '.'));
	if (!in_array($extension, array(".pdf", ".doc"))) {
		return FALSE;
	}
	$original_name = substr($name, 0, $extension_index);
	$file_id = 0;

	while (file_exists('archivos/'.$original_name.($file_id>0?'_'.$file_id:'').$extension)) {
		$file_id++;
	}
	$new_name = $original_name.($file_id>0?'_'.$file_id:'');
	$filename = "archivos/".$new_name;
	$tmp_name = $i>=0?$file['tmp_name'][$i]:$file['tmp_name'];

	if (move_uploaded_file($tmp_name, $filename . $extension )) {
		return array('filename'=>$filename.$extension, 'name'=>$new_name.$extension);
	}
}
