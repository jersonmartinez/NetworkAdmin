<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Panel de Administración</title>
		<meta name="author" content="Jerson M. y Frankier F." />
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/component2.css" />
		<script src="js/modernizr-2.6.2.min.js"></script>
		<script src="js/script.js"></script>
	</head>
	<body>
		<div class="container">
			
			<div class="component">
				<h2>Servidores</h2>
				
				<?php
					if (isset($_POST['service']) && !empty($_POST['service'])){

						?>
							<button class="cn-button" id="cn-button">Menú</button>
							<div class="cn-wrapper" id="cn-wrapper">
								<ul>
									<li><a href="#"><span>Memoria</span></a></li>
									<li><a href="#"><span>Discos</span></a></li>
									<li><a href="#"><span>Interfaces</span></a></li>
									<li><a href="#"><span>Puertos</span></a></li>
									<li><a href="#"><span>Estado</span></a></li>
									<li><a href="#"><span>Usuarios</span></a></li>
										
									<?php
										$service = $_POST['service'];
										if ($service == "dhcp"){
											?>
												<li><a href="#"><span>Asignación</span></a></li>
											<?php
										} else if ($service == "dns"){
											?>
												<li><a href="#"><span>Zonas</span></a></li>
											<?php
										} else if ($service == "web"){
											?>
												<li><a href="#"><span>Sitios</span></a></li>
											<?php
										}
									?>	
								 </ul>
							</div>
						<?php
					}
				?>
			</div>
			<header>
				<h1>Monitorización de Servidores <span>Recolectar información de red para cada equipo conectado</span></h1>	
				<nav class="codrops-demos">
					<?php
						if (@$service == "dhcp"){
							?>
								<a class="current-demo class_dhcp" onclick="javascript: start_service('dhcp')">DHCP</a>
							<?php
						} else {
							?>
								<a class="class_dhcp" onclick="javascript: start_service('dhcp')">DHCP</a>
							<?php							
						}

						if (@$service == "dns"){
							?>
								<a class="current-demo class_dns" onclick="javascript: start_service('dns')">DNS</a>
							<?php
						} else {
							?>
								<a class="class_dns" onclick="javascript: start_service('dns')">DNS</a>
							<?php							
						}

						if (@$service == "web"){
							?>
								<a class="current-demo class_web" onclick="javascript: start_service('web')">WEB</a>
							<?php
						} else {
							?>
								<a class="class_web" onclick="javascript: start_service('web')">WEB</a>
							<?php							
						}
					?>

					<form action="./" id="FormService" method="post">
						<input type="hidden" name="service" id="service" />
					</form>
				</nav>
			</header>
		</div>
		<script src="js/polyfills.js"></script>
		<script src="js/demo2.js"></script>
	</body>
</html>