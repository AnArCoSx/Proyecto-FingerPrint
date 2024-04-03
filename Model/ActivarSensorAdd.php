<?php

include_once './bd.php';
$con = new bd();
$delete = "delete from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
//$delete = "delete from huellas_temp where pc_serial = '" . $_GET['token'] . "'";
$con->exec($delete);
$insert = "insert into huellas_temp (pc_serial, texto, statusPlantilla, opc) "
        . "values ('" . $_POST['token'] . "', 'El sensor de huella dactilar esta activado', 'Muestras Restantes: 4', 'capturar')";
/*$insert = "insert into huellas_temp (pc_serial, texto, opc) "
        . "values ('" . $_GET['token'] . "', 'El sensor de huella dactilar esta activado', 'leer')";*/
$row = $con->exec($insert);
$con->desconectar();
echo json_encode("{\"filas\":$row}");
//header("Location: ../verificar.php?token=" . $_GET['token']);

