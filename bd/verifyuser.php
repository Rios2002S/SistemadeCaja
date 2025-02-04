<?php
session_start(); // Inicia la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usu = $_POST['nombreusu'];
    $pas = $_POST['contrasena'];

    require_once 'cn.php';

    // Crear conexión
    $conex = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conex->connect_error) {
        die("Error de conexión: " . $conex->connect_error);
    }

    // Preparar y ejecutar la consulta
    $sql = "SELECT id_usuario, nombreusu, contrasena, es_admin, sucursal_asignada, nombre_persona FROM usuarios WHERE nombreusu = ?";
    $stmt = $conex->prepare($sql);

    // Verificar la preparación de la consulta
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }

    $stmt->bind_param("s", $usu);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombreusu, $hashedPassword, $es_admin, $sucursal, $nombre_perosona);
    $stmt->fetch();

    // Verificar la contraseña
    if (password_verify($pas, $hashedPassword)) {
        // La contraseña es correcta, iniciar sesión
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['nombreusu'] = $nombreusu;
        $_SESSION['es_admin'] = $es_admin;
        $_SESSION['sucursal_asignada'] = $sucursal;
        $_SESSION['nombre_persona'] = $nombre_perosona;

        // Redirigir a la página correspondiente
        if ($es_admin) {
            header("Location: ../panel_administrador/inicio.php"); // Redirigir al panel de administrador
        } else {
            header("Location: ../vendedor/caja_vendedor.php"); // Redirigir al panel de usuario
        }
        exit();
    } else {
        // La contraseña es incorrecta
        echo "Usuario o contraseña incorrectos. Inténtalo de nuevo.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conex->close();
}
?>
