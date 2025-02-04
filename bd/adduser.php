<?php
session_start();
require_once 'cn.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreusu = $_POST['nombreusu'];
    $pas = $_POST['contrausu'];
    $claveAdminIngresada = $_POST['contrasena'];
    $sucursalAsignada = $_POST['sucursal_asignada'];

    // Verificar si el usuario actual es administrador
    if (!isset($_SESSION['es_admin'])) {
        die("Acceso denegado. Debes estar autenticado como administrador.");
    }

    // Consultar la clave del administrador desde la base de datos
    $sqlAdmin = "SELECT contrasena FROM usuarios WHERE id_usuario = ?";
    $stmtAdmin = $conn->prepare($sqlAdmin);
    $stmtAdmin->bind_param("i", $_SESSION['es_admin']);
    $stmtAdmin->execute();
    $stmtAdmin->bind_result($claveAdminBD);
    $stmtAdmin->fetch();
    $stmtAdmin->close();

    // Verificar la clave del administrador
    if (!password_verify($claveAdminIngresada, $claveAdminBD)) {
        die("Clave de administrador incorrecta. No tienes permisos para realizar el registro.");
    }

    // Generar el hash de la nueva contraseña
    $hashedPassword = password_hash($pas, PASSWORD_DEFAULT);

    // Preparar y ejecutar la inserción
    $sql = "INSERT INTO usuarios (nombreusu, contrasena, sucursal_asignada) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $nombreusu, $hashedPassword, $sucursalAsignada);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: ../panel_administrador/configuracion.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
