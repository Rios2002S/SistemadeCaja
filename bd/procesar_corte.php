<?php
require_once '../headfooter/head.php'; 
require_once '../bd/consultas/consultas.php'; 

// Verificar si el total vendido fue enviado correctamente
if (isset($_POST['total_vendido']) && is_numeric($_POST['total_vendido'])) {
    // Obtener el monto total vendido y las observaciones
    $totalVendido = $_POST['total_vendido'];
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

    // Obtener el ID del usuario que realizó el corte
    $id_usuario = $_SESSION['id_usuario']; // Asumiendo que ya tienes la sesión iniciada

    // Consulta para insertar el retiro
    $query = "INSERT INTO retiros (monto, fecha, id_usuario, observaciones) 
              VALUES ('$totalVendido', NOW(), '$id_usuario', '$observaciones')";

    // Ejecutar la consulta
    if ($conn->query($query) === TRUE) {
        // Si la inserción fue exitosa, redirigir a la página de corte con un mensaje de éxito
        header("Location: ../panel_administrador/corte_caja.php?success=1");
        exit();
    } else {
        // Si ocurrió un error en la consulta, mostrar mensaje de error
        echo "Error al procesar el corte de caja: " . $conn->error;
    }
} else {
    // Si no se envió un monto válido, redirigir con error
    header("Location: ../panel_administrador/corte.php?error=1");
    exit();
}

require_once '../headfooter/footer.php'; 
?>
