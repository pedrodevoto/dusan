	<div id="divHeaderImage" style="background: url('media/images/banner_cabezal.jpg');background-repeat:no-repeat;height:51px">
		
	</div>
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
                        <li><a href="section-poli_patrimoniales_list.php">Patrimoniales (Otras)</a></li>
                        <li><a href="section-poli_personas_list.php">Personas</a></li>                        
                    </ul>
				</li>
				<li><a href="#">Op. Pendientes</a>
					<ul>
						<li><a href="section-poli_pendientes_list.php">Pólizas</a></li>
					</ul>
				</li>
				<li><a href="section-endosos_list.php">Endosos</a></li>
                <li><a href="section-clie_list.php">Clientes</a></li>
                <li><a href="#">Configuración</a>
                    <ul>
                        <li><a href="section-seg_list.php">Compañías de Seguros</a></li>
                        <li><a href="section-pro_list.php">Productores</a></li>                        
                    </ul>
                </li>  
                <? } ?>
                
            	<?php if($_SESSION['ADM_UserGroup']=="master") { ?>
            	<!-- Master -->
                <li><a href="#">Pólizas</a>
					<ul>
                        <li><a href="section-poli_auto_list.php">Automotor</a></li>
                        <li><a href="section-poli_patrimoniales_list.php">Patrimoniales (Otras)</a></li>
                        <li><a href="section-poli_personas_list.php">Personas</a></li>                        
                    </ul>
				</li>
				<li><a href="#">Op. Pendientes</a>
					<ul>
						<li><a href="section-poli_pendientes_list.php">Pólizas</a></li>
					</ul>
				</li>
				<li><a href="section-endosos_list.php">Endosos</a></li>
				<li><a href="section-facturacion.php">Facturación</a></li>
                <li><a href="section-clie_list.php">Clientes</a></li>
                <li><a href="#">Configuración</a>
                    <ul>
                        <li><a href="section-usr_list.php">Usuarios</a></li>
                        <li><a href="section-seg_list.php">Compañías de Seguros</a></li>
                        <li><a href="section-cob_list.php">Coberturas</a></li>
                        <li><a href="section-pro_list.php">Productores</a></li>
						<li><a href="section-suc_list.php">Sucursales</a></li>
                        <li><a href="section-cod_list.php">Códigos de Productor</a></li>
                    </ul>
                </li>  
                <? } ?>                  
                
                <!-- GENERAL -->
                <li><a href="logout.php">Logout</a></li>                           

            </ul>                     
        </div>
		<br style="clear:both;" />
    </div>