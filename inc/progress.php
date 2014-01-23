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
	$progMenu = array(
		1=>'1. Datos de Póliza',
		2=>'2. Detalle de Póliza',
		3=>'3. Certificados'
	);	
	if ($renew === true) {
		$progMenu[1] .= ' [Renovación]';
	}
?>	
<div class="progMenu">
	<ul>
		<?php
			for ($i=1; $i<=count($progMenu); $i++) {
				echo '<li';
				if ($i === $section) {
					echo ' class="alert-success"';
				}
				echo '>'.$progMenu[$i].'</li>';
			}
		?>
    </ul> 
    <br clear="all" />                   
</div>	