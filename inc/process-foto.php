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
function processFoto($file, $i) {
	if ($file["size"][$i] > return_bytes(ini_get('upload_max_filesize'))) {
		return FALSE;
	}
	$extension = strtolower(strrchr($file["name"][$i], '.'));
	if (!in_array($extension, array(".jpg", ".jpeg", ".png"))) {
		return FALSE;
	}
	$filename = "fotos/" . uniqid();
	if (move_uploaded_file($file["tmp_name"][$i], $filename . $extension )) {
		$thumb_filename = $filename . '_thumb.png';
		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				$image = @imagecreatefromjpeg($filename.$extension);
				break;
			case '.png':
				$image = @imagecreatefrompng($filename.$extension);
				break;
			default:
				$image = false;
				break;
		}
		
		if (!$image) {
			return FALSE;
		}
		$thumb_width = 100;
		$thumb_height = 100;

		$width = imagesx($image);
		$height = imagesy($image);

		$original_aspect = $width / $height;
		$thumb_aspect = $thumb_width / $thumb_height;

		if ( $original_aspect >= $thumb_aspect )
		{
		   // If image is wider than thumbnail (in aspect ratio sense)
		   $new_height = $thumb_height;
		   $new_width = $width / ($height / $thumb_height);
		}
		else
		{
		   // If the thumbnail is wider than the image
		   $new_width = $thumb_width;
		   $new_height = $height / ($width / $thumb_width);
		}

		$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

		// Resize and crop
		imagecopyresampled($thumb,
		                   $image,
		                   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
		                   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
		                   0, 0,
		                   $new_width, $new_height,
		                   $width, $height);
		if (!imagepng($thumb, $thumb_filename, 0)) {
			return FALSE;
		}
		return array('filename'=>$filename.$extension, 'thumb_filename'=>$thumb_filename, 'width'=>$width, 'height'=>$height);
	}
}
