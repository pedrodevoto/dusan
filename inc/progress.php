<?php
	// Get section
	$section = NULL;
	if (isset($_GET['section']) && $_GET['section'] !== '') {
		$section = intval($_GET['section']);
	}	
	// Get renovation status
	$renew = false;
	if (isset($_GET['ren']) && $_GET['ren'] === '1') {
		$renew = true;
	}	
	
	// Progress menu options
	switch ($progress_bar) {
		case 'siniestro':
		$progMenu = array(
			1=>array('nombre'=>'1. Siniestros de la póliza', 'id'=>'navegacion-siniestros'),
			2=>array('nombre'=>'2. Detalle del Siniestro', 'id'=>'navegacion-detalle_siniestro'),
			3=>array('nombre'=>'3. Certificados', 'id'=>'navegacion-cert_siniestro')
		);
		break;
		default:
		$progMenu = array(
			1=>array('nombre'=>'1. Datos de Póliza', 'id'=>'navegacion-datos'),
			2=>array('nombre'=>'2. Detalle de Póliza', 'id'=>'navegacion-detalle'),
			3=>array('nombre'=>'3. Certificados', 'id'=>'navegacion-cert')
		);	
		if ($renew === true) {
			$progMenu[1] .= ' [Renovación]';
		}
		break;
	}
	
?>	
<div class="progMenu">
	<ul>
		<?php
			for ($i=1; $i<=count($progMenu); $i++) {
				echo '<a href="#" id="'.$progMenu[$i]['id'].'"><li';
				if ($i === $section) {
					echo ' class="alert-success"';
				}
				echo '>'.$progMenu[$i]['nombre'].'</li></a>';
			}
		?>
    </ul> 
    <br clear="all" />                   
</div>	