<?php
        session_start();
        require_once("../bd/cn.php");
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../index.php"); // Si no está autenticado, redirigir al login
            exit();
        }

        // Verificar si el usuario está autenticado y obtener los valores de la sesión
        $es_admin = $_SESSION['es_admin'] ?? 0;
        $nombreu = $_SESSION['nombreusu'];
        $sucursal = $_SESSION['sucursal_asignada'];
        $nombre_persona = $_SESSION['nombre_persona'];

        // Consulta para obtener productos
        $query = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.cantidad_en_stock, p.imagen, c.nombre AS categoria 
                FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";
        $result = $conn->query($query);    
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Sistema de Caja</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Protest+Guerrilla&display=swap" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../aparence/estilostodo.css">
        
        <!-- Gráfica de PAstel -->        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


            <!-- Incluir DataTables CSS y JS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    </head>
    <body>
        <header>
        <div class="infor mb-0">
            <h3 class="m-0">Programador Ríos&nbsp;</h3>
        </div>
            <?php 
            require_once '../add/navbar.php';
            ?>
        </header>
        <main><br>
            <?php 
            require_once '../add/saludo.php';
            ?>
            