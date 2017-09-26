<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui1.12.4.js"></script>

<script>
  $( function() {
    $( "#accordion" )
      .accordion({
        header: "> div > h3"
      })
      .sortable({
        axis: "y",
        handle: "h3",
        stop: function( event, ui ) {
          // IE doesn't register the blur when sorting
          // so trigger focusout handlers to remove .ui-state-focus
          ui.item.children( "h3" ).triggerHandler( "focusout" );
 
          // Refresh accordion to handle new order
          $( this ).accordion( "refresh" );
        }
      });
  });
</script>

<?php
	$ConnectDB = new mysqli("127.0.0.1", "root", "root", "monitorizador");

	$Result = $ConnectDB->query("SELECT * FROM host;");

	if ($Result->num_rows > 0){
		?>
			<div id="accordion">
		<?php
		while ($Row = $Result->fetch_array(MYSQLI_ASSOC)){
			?>
			  <div class="group">
			     <h3>Machine ID: #<?php echo $Row['id']; ?> | <?php echo $Row['username']; ?> (<?php echo $Row['ip_address']; ?>)</h3>
			    <div>
			      <p>Acciones que se pueden ejecutar en el host (<?php echo $Row['username']."@".$Row['ip_address']; ?>) son las siguientes, haga click sobre ellas.</p>
			    	<nav class="codrops-demos">
						<a class="class_more" style="cursor: pointer;" onclick="javascript: MakeQuery('<?php echo $Row['ip_address']; ?>','<?php echo $Row['username']; ?>','MemoriaDisco');">MEMORIA Y DISCOS</a>
						<a class="class_more" style="cursor: pointer;" onclick="javascript: MakeQuery('<?php echo $Row['ip_address']; ?>','<?php echo $Row['username']; ?>','Interfaces');">INTERFACES</a>
						<a class="class_more" style="cursor: pointer;" onclick="javascript: MakeQuery('<?php echo $Row['ip_address']; ?>','<?php echo $Row['username']; ?>','Puertos');">PUERTOS</a>
						<a class="class_more" style="cursor: pointer;" onclick="javascript: MakeQuery('<?php echo $Row['ip_address']; ?>','<?php echo $Row['username']; ?>','Estado');">ESTADO</a>
						<a class="class_more" style="cursor: pointer;" onclick="javascript: MakeQuery('<?php echo $Row['ip_address']; ?>','<?php echo $Row['username']; ?>','Usuarios');">USUARIOS</a>
						<a class="class_more" style="cursor: pointer;" onclick="javascript: MakeBackup('<?php echo $Row['ip_address']; ?>','<?php echo $Row['username']; ?>');"><i class="fa fa-codepen" aria-hidden="true"></i> BACKUP</a>
						<!-- <a class="class_more" style="cursor: pointer;" onclick="javascript: MakeClick();">MEMORIA Y DISCOS</a> -->
					</nav>
			    </div>
			  </div>
			<?php
		}
		?>
			</div>
		<?php
	}
?>