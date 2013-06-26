<?php
	// FILE FUNCTIONS
	function CheckUpload ($field, $alias) {
		switch($_FILES[$field]['error']){
			case UPLOAD_ERR_INI_SIZE:
				die("Error: El archivo '".$alias."' supera el peso máximo permitido (INI).");
				break;
			case UPLOAD_ERR_FORM_SIZE:
				die("Error: El archivo '".$alias."' supera el peso máximo permitido (Form).");
				break;
			case UPLOAD_ERR_PARTIAL:
				die("Error: El upload no pudo ser completado para el archivo '".$alias."'.");
				break;
			case UPLOAD_ERR_NO_FILE:
				die("Error: Debe seleccionar un archivo para el campo '".$alias."'.");
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				die("Error: Configuración del servidor incorrecta (Temp).");
				break;
			case UPLOAD_ERR_CANT_WRITE:
				die("Error: Configuración del servidor incorrecta (Write).");		
				break;
			case UPLOAD_ERR_EXTENSION:
				die("Error: Configuración del servidor incorrecta (Ext).");
				break;
			case UPLOAD_ERR_OK:
				break;
			default:
				die("Error indefinido.");
				break;
		}
	}
	function CheckFileSize ($field, $alias, $num) {	
		if ($_FILES[$field]['size']>$num){
			die("Error: El archivo '".$alias."' supera el peso máximo permitido (PHP).");
		} elseif ($_FILES[$field]['size']==0){
			die("Error: El archivo '".$alias."' se encuentra vacío.");	
		}
	}
	function GetFileExt ($filename) {
		$array = explode('.', $filename);
		$ext = strtolower(end($array));
		return $ext;
	}		
	function CheckFileExt ($field, $alias, $valid) {
		$ext = GetFileExt($_FILES[$field]['name']);
		if (!in_array($ext, $valid)) {
			die("Error: La extensión del archivo '".$alias."' (".$ext.") no se encuentra permitida.");
		}	
	}	
	function VerifyUpload ($field, $alias, $maxSize, $arrayExt) {
		CheckUpload($field, $alias);
		CheckFileSize($field, $alias, $maxSize);
		CheckFileExt($field, $alias, $arrayExt);
	}
	
	// IMAGE FUNCTIONS
	function VerifyImage ($field, $alias, $arrayMime, $width, $height, $proc) {
		// Get image info
		$image = getimagesize($_FILES[$field]['tmp_name']);
		// Check MIME-type
		$image_type = $image['mime'];		
		if ($image_type=="") {
			die("Error: Tipo de archivo no definido para '".$alias."'.");		
		} else {
			if (!in_array($image_type, $arrayMime)) {
				die("Error: El tipo MIME del archivo '".$alias."' no se encuentra permitido ('".$image_type."').");
			}			
		}
		// If image is moved directly
		if ($proc=='move') {
			// Check dimensions									
			$image_width = $image[0];
			$image_height = $image[1];		
			if ($image_width != $width || $image_height != $height) {
				die("Error: Las dimensiones del archivo '".$alias."' (".$image_width."x".$image_height.") difieren de las permitidas (".$width."x".$height.").");
			}			
		}
	}
?>