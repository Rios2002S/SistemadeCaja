<?php
// deletesucursal.php

require_once 'cn.php';

if (isset($_POST['id_sucursal'])) {
    $id_sucursal = $_POST['id_sucursal'];

    // Eliminar la sucursal de la base de datos
    $sql = "DELETE FROM sucursales WHERE id_sucursal = '$id_sucursal'";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../panel_administrador/configuracion.php'); // Redirigir después de eliminar
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
