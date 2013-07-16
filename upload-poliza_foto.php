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
if ($_FILES["box-poliza_foto"]["error"] == 0 and isset($_POST['poliza_id'])){
	$extension = strrchr($_FILES["box-poliza_foto"]["name"], '.');
	if (!in_array($extension, array(".jpg", ".jpeg", ".png"))) {
		die("Error: subir solamente fotos con extensión .jpg, .jpeg o .png");
	}
	$filename = "fotos/" . str_replace('/', '_', $_POST['poliza_id']) . time();
	if (move_uploaded_file($_FILES["box-poliza_foto"]["tmp_name"], $filename . $extension )) {
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
			$insertSQL = sprintf("INSERT INTO poliza_foto (poliza_id, poliza_foto_url, poliza_foto_thumb_url, poliza_foto_width, poliza_foto_height) VALUES (%s, %s, %s, %s, %s)",
						GetSQLValueString($_POST['poliza_id'], "int"),
						GetSQLValueString($filename.$extension, "text"),
						GetSQLValueString($thumb_filename, "text"),
						GetSQLValueString($width, "int"),
						GetSQLValueString($height, "int"));								
			$Result1 = mysql_query($insertSQL, $connection);
			echo "Success";
		}
	}
}
