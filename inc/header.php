	<a href="main.php"><div id="divHeaderImage" style="background: url('media/images/banner_cabezal.jpg');background-repeat:no-repeat;background-size:1050px 51px;width:1050;height:51px">
		
	</div></a>
	<div id="divHeader">
    	<!-- Logo -->
        <div style="float:left; margin-right: 20px;">
        	<!--<div id="divHeaderLogo">
	        	<a href="main.php"><img style="vertical-align:middle" src="media/images/logo.png" width="113" height="25" alt="Logo" border="0" /></a>
			</div>-->
        	<div id="divHeaderTitle">
            </div>                         
        </div>
        <!-- Items -->
        <div style="float:right">           
            <ul id="jsddm">         
            
            	<?php if($_SESSION['ADM_UserGroup']=="administrativo") { ?>
            	<!-- Administrativo -->
                <li><a href="#">Pólizas</a>
					<ul>
                        <li><a href="section-poli_auto_list.php">Automotor</a></li>
                        <li><a href="section-poli_patrimoniales_list.php">Otros Riesgos</a></li>
                        <li><a href="section-poli_personas_list.php">Personas</a></li>                        
                    </ul>
				</li>
				<li><a href="#">Op. Pendientes</a>
					<ul>
						<li><a href="section-poli_pendientes_list.php">Pólizas</a></li>
					</ul>
				</li>
				<li><a href="section-endosos_list.php">Endosos</a></li>
                <li><a href="#">Siniestros</a></li>
                <li><a href="section-clie_list.php">Clientes</a></li>
                <li><a href="section-caja.php">Caja</a></li>
                <li><a href="section-calendario.php">Calendario</a></li>
                <? } ?>
                
            	<?php if($_SESSION['ADM_UserGroup']=="master") { ?>
            	<!-- Master -->
                <li><a href="#">Pólizas</a>
					<ul>
                        <li><a href="section-poli_auto_list.php">Automotor</a></li>
                        <li><a href="section-poli_patrimoniales_list.php">Otros Riesgos</a></li>
                        <li><a href="section-poli_personas_list.php">Personas</a></li>                        
                    </ul>
				</li>
				<li><a href="#">Op. Pendientes</a>
					<ul>
						<li><a href="section-poli_pendientes_list.php">Pólizas Pendientes</a></li>
						<li><a href="section-poli_archivadas_list.php">Pólizas Archivadas</a></li>
					</ul>
				</li>
				<li><a href="section-endosos_list.php">Endosos</a></li>
                <li><a href="section-siniestros_list.php">Siniestros</a></li>
				<li><a href="section-clie_list.php">Clientes</a></li>
				<li><a href="#">Administración</a>
					<ul>
		               	<li><a href="section-libros_rubricados.php">Libros Rubricados</a></li>
						<li><a href="section-facturacion.php">Facturación</a></li>
						<li><a href="section-vencimientos.php">Listado de vencimientos</a></li>
		                <li><a href="section-caja.php">Caja</a></li>
		                <li><a href="section-calendario.php">Calendario</a></li>
		                <li><a href="section-estadisticas.php">Estadísticas</a></li>
						<li><a href="section-newsletter.php">Newsletter</a></li>
					</ul>
				</li>
                <li><a href="#">Configuración</a>
                    <ul>
                        <li><a href="section-usr_list.php">Usuarios</a></li>
                        <li><a href="section-seg_list.php">Compañías</a></li>
                        <li><a href="section-pro_list.php">Productores</a></li>
						<li><a href="section-org_list.php">Organizadores</a></li>
						<li><a href="section-suc_list.php">Sucursales</a></li>
						<li><a href="section-autos_modelos_list.php">Modelos de autos</a></li>
                    </ul>
                </li>  
                <? } ?>                  
                
                <!-- GENERAL -->
                <li><a href="logout.php">Logout</a></li>                           

            </ul>                     
        </div>
		<br style="clear:both;" />
    </div>
	<div id="stats" title="Estadísticas">
	  Cargando...
	</div>