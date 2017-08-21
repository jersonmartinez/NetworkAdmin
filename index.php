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
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script src="js/modernizr-2.6.2.min.js"></script>
		<script src="js/script.js"></script>


		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>
		
	</head>
	<body>
		
		<div class="container">
			
			<div class="component">
				<h2>Servidores</h2>
				
				<?php
					if (isset($_POST['service']) && !empty($_POST['service'])){
						$service = $_POST['service'];
						?>
							<button class="cn-button" id="cn-button">Menú</button>
							<div class="cn-wrapper" id="cn-wrapper">
								<ul>
									<li class="md-trigger" data-modal="modal-1" onclick="javascript: get_service('<?php echo $service; ?>', 'memoria');"><a><span>Memoria</span></a></li>
									<li class="md-trigger md-setperspective" data-modal="modal-19" onclick="javascript: get_service('<?php echo $service; ?>', 'discos');"><a><span>Discos</span></a></li>
									<li class="md-trigger" data-modal="modal-2" onclick="javascript: get_service('<?php echo $service; ?>', 'interfaces');"><a><span>Interfaces</span></a></li>
									<li class="md-trigger" data-modal="modal-3" onclick="javascript: get_service('<?php echo $service; ?>', 'puertos');"><a><span>Puertos</span></a></li>
									<li class="md-trigger" data-modal="modal-4" onclick="javascript: get_service('<?php echo $service; ?>', 'estado');"><a><span>Estado</span></a></li>
									<li class="md-trigger" data-modal="modal-5" onclick="javascript: get_service('<?php echo $service; ?>', 'usuarios');"><a><span>Usuarios</span></a></li>
										
									<?php
										if ($service == "dhcp"){
											?>
												<li class="md-trigger" data-modal="modal-6" onclick="javascript: get_service('<?php echo $service; ?>', 'asignacion');"><a><span>Asignación</span></a></li>
											<?php
										} else if ($service == "dns"){
											?>
												<li class="md-trigger" data-modal="modal-7" onclick="javascript: get_service('<?php echo $service; ?>', 'zonas');"><a><span>Zonas</span></a></li>
											<?php
										} else if ($service == "web"){
											?>
												<li class="md-trigger" data-modal="modal-8"onclick="javascript: get_service('<?php echo $service; ?>', 'sitios');"><a><span>Sitios</span></a></li>
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
			
			<?php
				include ("php/modal.php");
			?>

		</div>

		<div class="md-overlay"></div><!-- the overlay element -->

		<script src="js/classie.js"></script>
		<script src="js/modalEffects.js"></script>

		<!-- for the blur effect -->
		<!-- by @derSchepp https://github.com/Schepp/CSS-Filters-Polyfill -->
		<script>
			// this is important for IEs
			var polyfilter_scriptpath = '/js/';
		</script>
		<script src="js/cssParser.js"></script>
		<script src="js/css-filters-polyfill.js"></script>

		<script src="js/polyfills.js"></script>
		<script src="js/demo2.js"></script>
	</body>
</html>