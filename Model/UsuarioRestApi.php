<?php

header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once './bd.php';
$con = new bd();

$method = $_SERVER['REQUEST_METHOD'];

// Metodo para peticiones tipo GET
if ($method == "GET") {
    $token = $_GET['token'];
    /* $desde = $_GET['desde'];
      $hasta = $_GET['hasta'];

      $sql = "select u.documento, u.nombre_completo, h.nombre_dedo, h.huella, h.imgHuella "
      . "from usuarios u "
      . "inner join huellas h on u.documento  = h. documento limit " . $desde . "," . $hasta . " "; */
    $sql = "select u.documento, u.nombre_completo, h.nombre_dedo, h.huella, h.imgHuella, u.pc_serial "
            . "from usuarios u inner join huellas h on u.documento = h.documento "
            . "where u.pc_serial = '" . base64_encode($token) . "'";
    $rs = $con->findAll($sql);
    /* $sql_ = "select count(documento) total from usuarios";
      $rs_c = $con->findAll($sql_);

      $arrayResponse = array();
      for ($index = 0; $index < count($rs); $index++) {
      $arrayObject = array();
      $arrayObject["count"] = $rs_c[0]['total'];
      $arrayObject["documento"] = $rs[$index]["documento"];
      $arrayObject["nombre_completo"] = $rs[$index]["nombre_completo"];
      $arrayObject["nombre_dedo"] = $rs[$index]["nombre_dedo"];
      $arrayObject["huella"] = $rs[$index]["huella"];
      $arrayObject["imgHuella"] = $rs[$index]["imgHuella"];
      $arrayResponse[] = $arrayObject;
      }
      //echo count($arrayResponse); die; */
    //echo json_encode($arrayResponse);
    echo json_encode($rs);
}

// Metodo para peticiones tipo POST
if ($method == "POST") {
    $jsonString = file_get_contents("php://input");
    $jsonOBJ = json_decode($jsonString, true);
    $query = "update huellas_temp set huella = '" . $jsonOBJ['huella'] . "', imgHuella = '" . $jsonOBJ['imageHuella'] . "',"
            . "update_time = NOW(), statusPlantilla = '" . $jsonOBJ['statusPlantilla'] . "',"
            . "texto = '" . $jsonOBJ['texto'] . "'"
            . "where pc_serial = '" . base64_encode($jsonOBJ['serial']) . "'";

    //echo $query;
    $row = $con->exec($query);
    $con->desconectar();
    echo json_encode("Filas Agregadas POST: " . $row);
}


// Metodo para peticiones tipo PUT
if ($method == "PUT") {
    //$jsonString = file_get_contents("php://input");
    $jsonString = stripslashes(file_get_contents("php://input"));
    //$jsonOBJ = json_decode($jsonString);
    $jsonOBJ = json_decode($jsonString); //, true);

    if ($jsonOBJ->option == "verificar") {
      $query = "update huellas_temp set imgHuella = '" . $jsonOBJ->imageHuella . "',"
              . "update_time = NOW(),"
              . "statusPlantilla = '" . $jsonOBJ->statusPlantilla . "',"
              . "texto = '" . $jsonOBJ->texto . "',"
              . "documento =  '" . $jsonOBJ->documento . "',"
              . "nombre = '" . $jsonOBJ->nombre . "',"
              . "dedo =  '" . $jsonOBJ->dedo . "'"
              . "where pc_serial = '" . base64_encode($jsonOBJ->serial) . "'";//$jsonOBJ->serial . "'";
    } else {
      $query = "update huellas_temp set imgHuella = '" . $jsonOBJ->imageHuella . "', "
              . "update_time = NOW(), statusPlantilla = '" . $jsonOBJ->statusPlantilla . "',"
              . "texto = '" . $jsonOBJ->texto . "', opc = 'stop'"
              . "where pc_serial = '" . base64_encode($jsonOBJ->serial) . "'";//$jsonOBJ->serial . "'";
    } 
      
    //echo $query;

    $row = $con->exec($query);
    $con->desconectar();
    echo json_encode("Filas Actualizadas PUT: " . $row);
}



// Metodo para peticiones tipo PATCH
if ($method == "PATCH") {
    $jsonString = file_get_contents("php://input");
    $jsonOBJ = json_decode($jsonString, true);
    $query = "update huellas_temp set imgHuella = '" . $jsonOBJ['imgHuella'] . "',"
            . "update_time = NOW(), statusPlantilla = '" . $jsonOBJ['statusPlantilla'] . "', texto = '" . $jsonOBJ['texto'] . "', "
            . "documento = '" . $jsonOBJ['documento'] . "', nombre = '" . $jsonOBJ['nombre'] . "',"
            . "dedo = '" . $jsonOBJ['dedo'] . "' where pc_serial = '" . $jsonOBJ['serial'] . "'";
    $row = $con->exec($query);
    $con->desconectar();
    echo json_encode("Filas Actualizadas PATCH: " . $row);
}