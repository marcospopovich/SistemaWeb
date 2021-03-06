<?php
session_start();
require "conexion.php";

if ($_GET) {
	//require "./includes/functions.php";

	$color = $_GET['color'];
	switch ($color) {
		case 'rojo':
			$color = 3;
			$colorN  = 'Rojo';
			$fondo = 'bg-danger';
			break;
		case 'amarillo':
			$color = 2;
			$colorN  = 'Amarillo';
			$fondo = 'bg-warning';
			break;
		case 'verde':
			$color = 1;
			$colorN  = 'Verde';
			$fondo = 'bg-success';
			break;
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Resultados de la Busqueda</title>
	<br>

</head>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container p-2 m-2">
		<h1>Resultados de la Busqueda</h1>
		<br>
		<h3><kbd class="<?php echo $fondo ?>" >Buscando datos estado:
				<?php
				echo $colorN;

				?>

			</kbd></h3>
		<div class="row">
			<div class="col">

				<?php

				if (!empty($_GET)) {

		
					$query = mysqli_query($link, "SELECT 
					boletas.boc,
					boletas.pasillo,
					boletas.metro,
					boletas.letra,
					boletas.boleta_estado_boleta_estado_id,
					usuarios.nombre_usuario,
					usuarios.apelli_usuario,
					boletas.fecha_bol,
					boletas.comentarios
				FROM     boletas 
				INNER JOIN `usuarios`  ON `boletas`.`Usuarios_legajo_usuario` = `usuarios`.`legajo_usuario` 
				WHERE boletas.boleta_estado_boleta_estado_id = $color
																	
                  ");

				
						$result = mysqli_num_rows($query);
						if ($result > 0) {
				?>
							<table class="table table-hover">
								<tr style="color: white; background-color:grey">
									<th>N° Boc</th>
									<th>Ubicacion</th>
									<th>Comentarios</th>
									<th>Fecha Alta</th>
									<th>Usuario</th>
									<th></th>

								</tr>
								<?php

								while ($data = mysqli_fetch_array($query)) {

								?>

									<tr style="background-color: #dddddd;font-weight: bold;">
										<td><?php echo $data["boc"]; ?></td>
										<td><?php echo $data["pasillo"] . "-" . $data["metro"] . "-" . $data["letra"]; ?></td>
										<td><?php echo $data["comentarios"]; ?></td>
										<td><?php echo $data["fecha_bol"]; ?></td>
										<td><?php echo $data["nombre_usuario"] . " " . $data["apelli_usuario"]; ?></td>
										<td> 
									 <button class="btn btn-success" onclick="location.href='editar_boc.php?boc=<?php echo  $data["boc"]; ?>'"> Editar</button>
									
									</td>
								
									</tr>


									<!-- inicio del ciclo de detalles -->
									<?php
									$boletaid = $data["boc"];
															
										$query2 = mysqli_query($link, " SELECT
                                                `boletas_descripcion`.`boc`,
                                                `boletas_descripcion`.`Articulos_sap_articulo`,
                                                `boletas_descripcion`.`cantidad_articulos`,
                                                `articulos`.`sap_articulo`,
                                                `articulos`.`descr_articulo`
                                       FROM     `boletas_descripcion` 
                                       INNER JOIN `articulos`  ON `boletas_descripcion`.`Articulos_sap_articulo` = `articulos`.`sap_articulo` 

                                                             WHERE  boc = $boletaid
                                                              ");
									?>
										<tr style="color: black ; font-weight: bold; background-color: #eeeded; ">
											<td></td>
											<td></td>
											<td>SAP</td>
											<td>Descripcion</td>
											<td>Cantidad</td>
											<td>Imagen</td>
										</tr>

										<?php

										while ($data2 = mysqli_fetch_array($query2)) {


										?>

											<!-- tabla -->



											<tr>
												<td></td>
												<td></td>
												<td><?php echo $data2["Articulos_sap_articulo"]; ?></td>
												<td><?php echo $data2["descr_articulo"]; ?></td>
												<td><?php echo $data2["cantidad_articulos"]; ?></td>
												<td>
													<!-- Inicio de la busqueda de Imagen -->
													<?php $img = imagen($data2["Articulos_sap_articulo"]); ?>
													<img height="100" width="100" src=" <?php echo $img; ?> "> <!-- Fin de la busqueda de imagen -->
												</td>
											</tr>


											<!-- FIN TABLA -->









										<?php  }  ?>



									








								<?php } ?>
					<?php } else {

							echo "<div class=\"alert alert-danger w-50\" role=\"alert\"> La busqueda no arrojo resultados </div>";
						}

						echo " <button class=\"btn btn-primary\" onclick=\"location.href='index.php'\"> Volver</button>";
						

						mysqli_close($link);
					
				}
					?>

							</table>
			</div>
		</div>
	</section>
	<?php
	include "includes/footer.php";
	include "includes/sesiones.php";

	?>
</body>

</html>