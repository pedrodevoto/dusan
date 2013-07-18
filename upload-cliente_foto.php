<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
ini_set('display_errors', '1');
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
if ($_FILES["box-cliente_foto"]["error"] == 0 and isset($_POST['cliente_id'])){
	if ($_FILES["box-cliente_foto"]["size"] > return_bytes(ini_get('upload_max_filesize'))) {
		die("Error: no se puede subir archivos de más de ".ini_get('upload_max_filesize')." de tamaño.");
	}
	$extension = strtolower(strrchr($_FILES["box-cliente_foto"]["name"], '.'));
	if (!in_array($extension, array(".jpg", ".jpeg", ".png"))) {
		die("Error: subir solamente fotos con extensión .jpg, .jpeg o .png");
	}
	$filename = "fotos/" . str_replace('/', '_', $_POST['cliente_id']) . time();
	if (move_uploaded_file($_FILES["box-cliente_foto"]["tmp_name"], $filename . $extension )) {
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
			die('Error: no se pudo generar vista preliminar.');
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
			echo "Error: no se pudo generar la vista preliminar. Intente nuevamente.";
		}
		else {
			$insertSQL = sprintf("INSERT INTO cliente_foto (cliente_id, cliente_foto_url, cliente_foto_thumb_url, cliente_foto_width, cliente_foto_height) VALUES (%s, %s, %s, %s, %s)",
						GetSQLValueString($_POST['cliente_id'], "int"),
						GetSQLValueString($filename.$extension, "text"),
						GetSQLValueString($thumb_filename, "text"),
						GetSQLValueString($width, "int"),
						GetSQLValueString($height, "int"));								
			$Result1 = mysql_query($insertSQL, $connection);
			echo "Success";
		}
	}
}
