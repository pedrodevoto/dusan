	<div id="divHeader">
    	<!-- Logo -->
        <div style="float:left; margin-right: 20px;">
        	<div id="divHeaderLogo">
	        	<a href="main.php"><img style="vertical-align:middle" src="media/images/logo.png" width="113" height="25" alt="Logo" border="0" /></a>
			</div>   
        	<div id="divHeaderTitle">
            </div>                         
        </div>
        <!-- Items -->
        <div style="float:right">           
            <ul id="jsddm">         
            
            	<?php if($_SESSION['ADM_UserGroup']=="administrativo") { ?>
            	<!-- Administrativo -->
                <li><a href="section-poli_list.php">Pólizas</a></li>
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
                <li><a href="section-poli_list.php">Pólizas</a></li>
                <li><a href="section-clie_list.php">Clientes</a></li>
                <li><a href="#">Configuración</a>
                    <ul>
                        <li><a href="section-usr_list.php">Usuarios</a></li>
                        <li><a href="section-seg_list.php">Compañías de Seguros</a></li>
                        <li><a href="section-pro_list.php">Productores</a></li>
                    </ul>
                </li>  
                <? } ?>                  
                
                <!-- GENERAL -->
                <li><a href="logout.php">Logout</a></li>                           

            </ul>                     
        </div>
		<br style="clear:both;" />
    </div>