<?php
require_once '../bd/cn.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = intval($_POST['id_usuario']);

    if ($id_usuario) {
        // Verificar si el usuario es el jefe
        if ($id_usuario == 1) {
            echo "<script>alert('No puedes eliminar a tu papi, pagarás por intentar hacer esto.'); window.location.href='../panel_administrador/configuracion.php';</script>";
            exit();
        }

        // Eliminar el usuario si no es el jefe
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_usuario);
            if ($stmt->execute()) {
                echo "<script>alert('Usuario eliminado correctamente.'); window.location.href='../panel_administrador/configuracion.php';</script>";
            } else {
                echo "<script>alert('Error al eliminar el usuario.'); window.location.href='../panel_administrador/configuracion.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error en la consulta.'); window.location.href='../panel_administrador/configuracion.php';</script>";
        }
    } else {
        echo "<script>alert('ID de usuario inválido.'); window.location.href='../panel_administrador/configuracion.php';</script>";
    }
}
$conn->close();
?>
