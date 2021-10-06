<?php
session_start();
require "conexion.php";





if ($_GET) {
    $busqueda = ($_REQUEST['busqueda']);
    $Xbusqueda = ($_REQUEST['Xbusqueda']);

    if ($Xbusqueda == 'XNboleta') {
        //busqueda por Numero de Boc
        $query = mysqli_query($link, "SELECT
             boletas_descripcion.Articulos_sap_articulo,
             boletas_descripcion.cantidad_articulos,
             boletas.boleta_estado_boleta_estado_id,
             boletas.fecha_bol,
             boletas.boc,
             boletas.comentarios,
             usuarios.apelli_usuario,
             usuarios.nombre_usuario,
             articulos.descr_articulo,
             boletas.pasillo,
             boletas.metro,
             boletas.letra
     FROM     boletas_descripcion
     INNER JOIN `boletas`  ON `boletas_descripcion`.`boc` = `boletas`.`boc` 
     INNER JOIN `usuarios`  ON `boletas`.`Usuarios_legajo_usuario` = `usuarios`.`legajo_usuario` 
     INNER JOIN `articulos`  ON `boletas_descripcion`.`Articulos_sap_articulo` = `articulos`.`sap_articulo` 
     WHERE  boletas.boc = '$busqueda' AND NOT boleta_estado_boleta_estado_id = '5'
     LIMIT 50
           ");


        $result = mysqli_num_rows($query);
        if ($result > 0) {


            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_array($query);


                $sap =  $data["Articulos_sap_articulo"];
                $cantidad =  $data["cantidad_articulos"];
                $comentarios = $data["comentarios"];
                $fecha = $data["fecha_bol"];
                $boc = $data["boc"];
                $nombre = $data["nombre_usuario"];
                $apellido = $data["apelli_usuario"];
                $pasillo =  $data["pasillo"];
                $metro = $data["metro"];
                $ubicacion = $data["letra"];
                $estado = $data["boleta_estado_boleta_estado_id"];

                $date1 = date("Y-m-d", strtotime($fecha));
                $now = date("Y-m-d");
                //calculo de Diferencia de fechas en dias
                $datetime1 = date_create($date1);
                $datetime2 = date_create($now);
                $contador = date_diff($datetime1, $datetime2);
                $differenceFormat = '%a';
                $newDate = date("d/m/Y H:i:s", strtotime($fecha));

                //estados de color en BOC
                switch ($estado) {
                    case '1':
                        $color = 'bg-success';
                        break;
                    case '2':
                        $color = 'bg-warning';
                        break;
                    case '3':
                        $color = 'bg-danger';
                        break;
                    case '4':
                        $color = 'bg-dark';
                        break;
                }
            }


?>

            <!DOCTYPE html>
            <html lang="es">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <?php include "includes/scripts.php"; ?>
                <!-- Titulo de la web -->
                <title>Sistema WEB Boc N° <?php echo $boc; ?></title>

              
            </head>

            <body style="background-color: #494644 ;">
                <?php include "includes/header.php"; ?>

            <div class="container w-100">

                <div class="card <?php echo $color; ?> border-secondary m-3" style="width: 35%;
              box-shadow: -15px 8px 30px 1px rgba(0,0,0,0.76);
-webkit-box-shadow: -15px 8px 30px 1px rgba(0,0,0,0.76);
-moz-box-shadow: -15px 8px 30px 1px rgba(0,0,0,0.76);
        
                ">
                    <div class="card-header text-white"> 
                        <h1>BOC N° <?php echo $boc; 
                         if ($estado == 4){
                            echo " - ENTREGADO";
                        }
                    
                        ?> </h1>
                    </div>
                    <div class="card-body bg-light text-black">
                        <h5 class="card-title bg-light"> <?php echo "Creado hace: ". $contador->format($differenceFormat)." dias."; 
                           
                        ?> 
                        </h5>
                        <hr>
                        <h5>Ubicacion: </h5>
                        <h2> <?php echo $pasillo . "-" . $metro . "-" . $ubicacion; ?> </h2>
                        <hr>
                        <h5>Comentarios: </h5>
                        <span> <?php echo $comentarios; ?> </span>
                        <br>

                        <p class="card-text">
                            <hr>
                        <h5> Creado por:</h5>
                        <h6><?php echo $nombre . " " . $apellido; ?></h6>
                        <h6>Fecha: <?php echo $newDate; ?></h6>
                        </p>
                        <div class="m-1 p-1 text-center align-items-center">
                            
                                <button class="btn btn-primary" onclick="location.href='detalleBoc.php?boc= <?php echo $boc; ?>'">Detalles</button>
                                <button class="btn btn-secondary" onclick="location.href='editar_boc.php?boc=<?php echo $boc; ?>'">Editar&nbsp;&nbsp;</button>
                                <button class="btn btn-success" onclick="location.href='eliminar_confirmar_boc.php?boc=<?php echo $boc; ?>'">Entregar</button>
                                <button class="btn btn-danger" onclick="location.href='eliminar_confirmarb_boc.php?boc=<?php echo $boc; ?>'">Borrar&nbsp;&nbsp;</button>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php
        } else {

            
                header("Status: 301 Moved Permanently");
                header("Location: sinResultados.php");
                exit;


            
        }









        include "includes/footer.php";
        include "includes/sesiones.php";
            ?>

            </body>

            </html>
            <?php
        };

        if ($Xbusqueda == 'Xsap') {

            ///busqueda por sap exacto

            $query = mysqli_query($link, " SELECT
                     boletas_descripcion.Articulos_sap_articulo,
                     boletas_descripcion.cantidad_articulos,
                     boletas.fecha_bol,
                     boletas.boc,
                     boletas.comentarios,
                     boletas.boleta_estado_boleta_estado_id,
                     usuarios.apelli_usuario,
                     usuarios.nombre_usuario,
                     articulos.descr_articulo,
                     boletas.pasillo,
                     boletas.metro,
                     boletas.letra
            FROM     boletas_descripcion
            INNER JOIN `boletas`  ON `boletas_descripcion`.`boc` = `boletas`.`boc` 
            INNER JOIN `usuarios`  ON `boletas`.`Usuarios_legajo_usuario` = `usuarios`.`legajo_usuario` 
            INNER JOIN `articulos`  ON `boletas_descripcion`.`Articulos_sap_articulo` = `articulos`.`sap_articulo` 
     WHERE Articulos_sap_articulo = '$busqueda'
     LIMIT 50
                         ");




            $result = mysqli_num_rows($query);
            if ($result > 0) {
            ?>

                <!DOCTYPE html>
                <html lang="es">

                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <?php include "includes/scripts.php"; ?>
                    <!-- Titulo de la web -->
                    <title>Sistema WEB</title>


                </head>

                <body>
                    <?php include "includes/header.php"; ?>

                    <table class="table table-hover">
                        <tr>
                            <th>Sap</th>
                            <th>Estado</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Ubicacion</th>
                            <th>Comentarios de la Boleta</th>
                            <th>Fecha Subida</th>
                            <th>Boc N°</th>
                            <th>Usuario</th>
                            <th>Imagen</th>
                        </tr>
                        <?php

                        while ($data = mysqli_fetch_array($query)) {

                        ?>

                            <tr>
                                <td><?php echo $data["Articulos_sap_articulo"]; ?></td>
                                <td><?php echo "ESTADO"; ?></td>
                                <td><?php echo $data["descr_articulo"]; ?></td>
                                <td><?php echo $data["cantidad_articulos"]; ?></td>
                                <td><?php echo $data["pasillo"] . "-" . $data["metro"] . "-" . $data["letra"]; ?></td>
                                <td><?php echo $data["comentarios"]; ?></td>
                                <td><?php echo $data["fecha_bol"]; ?></td>
                                <td><?php echo $data["boc"]; ?></td>
                                <td><?php echo $data["nombre_usuario"] . " " . $data["apelli_usuario"]; ?></td>

                                <td>
                                    <img height="100" width="100" src=" <?php echo imagen($data["Articulos_sap_articulo"]); ?>">
                                </td>


                            </tr>
                        <?php } ?>
            <?php } else {
                $msgSinResultados = "<span class='alert alert-danger w-50'>La busqueda no arrojo resultados  </span>";

                echo $msgSinResultados;
            }

            echo " <button class=\"btn btn-success\" onclick=\"location.href='index.php'\"> Volver</button>";


            mysqli_close($link);



            include "includes/footer.php";
            include "includes/sesiones.php";
        }
        //



        include "includes/footer.php";
        include "includes/sesiones.php";
    }


            ?>
            <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
                </body>

                </html>