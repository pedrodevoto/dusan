<?php
// Sanitation
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
	  $theValue = strip_tags($theValue);
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// Encryption
class Encryption
{
    const CYPHER = MCRYPT_RIJNDAEL_256;
    const MODE   = MCRYPT_MODE_CBC;
    const KEY    = '332!w_RHEHRJsdfsdaiqw7mcy7w67';

    public static function encrypt($plaintext)
    {
        $td = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, self::KEY, $iv);
        $crypttext = mcrypt_generic($td, $plaintext);
        mcrypt_generic_deinit($td);
        return base64_encode($iv.$crypttext);
    }

    public static function decrypt($crypttext)
    {
        $crypttext = base64_decode($crypttext);
        $plaintext = '';
        $td        = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
        $ivsize    = mcrypt_enc_get_iv_size($td);
        $iv        = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        if ($iv)
        {
            mcrypt_generic_init($td, self::KEY, $iv);
            $plaintext = mdecrypt_generic($td, $crypttext);
        }
        return trim($plaintext);
    }
}

// Processing
function determineState($startdiff, $enddiff) {
	$estado = NULL;
	if ($startdiff > $enddiff) {
		if ($startdiff < 0) {
			$estado = 2; // PENDIENTE
		} elseif ($enddiff > 0) {
			$estado = 6; // FINALIZADA
		} else {
			if ($enddiff >= -30) {
				$estado = 4; // A RENOVAR
			} else {
				$estado = 3; // VIGENTE
			}
		}
	}
	return $estado;
}

// Population 
function enumToForm($table, $field, $type, $selected = NULL) {
	
	// Global variables
	global $connection;
	
	// Recordset
	$query_Recordset1 = sprintf("SELECT column_type FROM information_schema.columns WHERE table_name=%s AND column_name=%s",
						GetSQLValueString($table, "text"),
						GetSQLValueString($field, "text"));		
	$Recordset1 = mysql_query($query_Recordset1, $connection);
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	

	// If Recordset not empty
	if ($totalRows_Recordset1 > 0) {	

		// Parse result	
		$result = str_replace(array("enum('", "')", "''"), array('', '', "'"), $row_Recordset1['column_type']);
		$result = explode("','", $result);
		
		// For each element
		foreach ($result as $key=>$value) {
			
			// Determine input type
			switch ($type) {
				case 'select':
					echo '<option value="'.$value.'"';
					if (!is_null($selected) && ($selected === $value)) {
						echo ' selected';
					}
					echo '>'.ucfirst($value).'</option>';
					break;
			}
			
		}
		
	}
	
	// Close Recordset
	mysql_free_result($Recordset1);
}
?>