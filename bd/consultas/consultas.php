<?php
require_once("../bd/cn.php");
// Total de Productos
$sqlTotalProductos = "SELECT COUNT(*) AS total_productos FROM productos;";
$resultTotalProductos = $conn->query($sqlTotalProductos);
$total_productos = $resultTotalProductos->fetch_assoc()['total_productos'];

// Producto Más Caro
$sqlProductoMasCaro = "SELECT nombre, precio FROM productos ORDER BY precio DESC LIMIT 1;";
$resultProductoMasCaro = $conn->query($sqlProductoMasCaro);
$producto_mas_caro = $resultProductoMasCaro->fetch_assoc();

// Producto Más Barato
$sqlProductoMasBarato = "SELECT nombre, precio FROM productos ORDER BY precio ASC LIMIT 1;";
$resultProductoMasBarato = $conn->query($sqlProductoMasBarato);
$producto_mas_barato = $resultProductoMasBarato->fetch_assoc();

// Consulta para contar el total de ventas
$sqlTotalVentas = "SELECT COUNT(*) AS total_ventas FROM ventas;";
$resultTotalVentas = $conn->query($sqlTotalVentas);
$total_ventas = $resultTotalVentas->fetch_assoc()['total_ventas'];

// Consulta para obtener el total de dinero vendido
$sqlTotalDineroVendido = "SELECT SUM(total) AS total_dinero_vendido FROM ventas;";
$resultTotalDineroVendido = $conn->query($sqlTotalDineroVendido);
$total_dinero_vendido = $resultTotalDineroVendido->fetch_assoc()['total_dinero_vendido'];

// Consulta para obtener el total de dinero vendido desde el último retiro en el mes actual
$sqlTotalDineroVendidoMes = "
    SELECT SUM(total) AS total_dinero_vendido_mes 
    FROM ventas 
    WHERE fecha_venta >= (
        SELECT IFNULL(MAX(fecha), '1970-01-01') 
        FROM retiros
    ) 
    AND MONTH(fecha_venta) = MONTH(CURRENT_DATE) 
    AND YEAR(fecha_venta) = YEAR(CURRENT_DATE);
";

$resultTotalDineroVendidoMes = $conn->query($sqlTotalDineroVendidoMes);
$total_dinero_vendido_mes = $resultTotalDineroVendidoMes->fetch_assoc()['total_dinero_vendido_mes'];


// Consulta para obtener el total de dinero vendido hoy
$sqlTotalDineroVendidoHoy = "SELECT SUM(total) AS total_dinero_vendido_hoy 
                             FROM ventas 
                             WHERE DATE(fecha_venta) = CURDATE();";
$resultTotalDineroVendidoHoy = $conn->query($sqlTotalDineroVendidoHoy);
$total_dinero_vendido_hoy = $resultTotalDineroVendidoHoy->fetch_assoc()['total_dinero_vendido_hoy'];

// Consulta para obtener las ventas por usuario, incluyendo el nombre del usuario
$sqlVentasPorUsuario = "SELECT u.nombreusu, SUM(v.total) AS total_ventas_usuario 
                        FROM ventas v
                        JOIN usuarios u ON v.id_usuario = u.id_usuario
                        GROUP BY u.nombreusu;";
$resultVentasPorUsuario = $conn->query($sqlVentasPorUsuario);

// Consulta para obtener los productos vendidos y su cantidad total
$sqlProductosVendidos = "SELECT p.nombre, SUM(dv.cantidad) AS cantidad_vendida 
                         FROM detalleventas dv
                         JOIN productos p ON dv.id_producto = p.id_producto
                         GROUP BY dv.id_producto;";
$resultProductosVendidos = $conn->query($sqlProductosVendidos);

// Consulta para obtener los 12 productos con stocke bajo
$sqlProductosMenorStock = "SELECT nombre, cantidad_en_stock 
                           FROM productos 
                           ORDER BY cantidad_en_stock ASC 
                           LIMIT 12;";
$resultProductosMenorStock = $conn->query($sqlProductosMenorStock);

// Consulta para obtener stocke bajo
$sqlTodosProductosMenorStock = "SELECT nombre, cantidad_en_stock 
                           FROM productos 
                           WHERE cantidad_en_stock < 5
                           ORDER BY cantidad_en_stock ASC;";
$resultTodosProductosMenorStock = $conn->query($sqlTodosProductosMenorStock);

// Consulta para obtener con stocke = 1 o terminado
$sqlProductosterminados = "SELECT nombre, cantidad_en_stock 
                           FROM productos 
                           WHERE cantidad_en_stock <= 1
                           ORDER BY cantidad_en_stock ASC;";
$resultProductosterminados = $conn->query($sqlProductosterminados);

// Consulta para obtener todos los productos
$sqlProductos = "SELECT id_producto, nombre, precio, cantidad_en_stock 
                 FROM productos;";
$resultProductos = $conn->query($sqlProductos);

// Obtener las ventas
$sqlVentas = "SELECT v.id_venta, v.fecha_venta, v.total, u.nombreusu AS vendedor, 
           dv.id_producto, p.nombre AS producto, dv.cantidad, dv.precio, dv.subtotal
    FROM ventas v
    JOIN usuarios u ON v.id_usuario = u.id_usuario
    JOIN detalleventas dv ON v.id_venta = dv.id_venta
    JOIN productos p ON dv.id_producto = p.id_producto
    GROUP BY v.id_venta
    ORDER BY v.fecha_venta DESC, v.id_venta, dv.id_detalle";
$resultVentas = $conn->query($sqlVentas);

//Obtener info para el reporte ventas:
$sqlVentasPDF = "SELECT v.id_venta, v.fecha_venta, v.total, u.nombreusu AS vendedor, 
           dv.id_producto, p.nombre AS producto, dv.cantidad, dv.precio, dv.subtotal
    FROM ventas v
    JOIN usuarios u ON v.id_usuario = u.id_usuario
    JOIN detalleventas dv ON v.id_venta = dv.id_venta
    JOIN productos p ON dv.id_producto = p.id_producto
    ORDER BY v.id_venta DESC, dv.id_detalle";
$resultVentasPDF = $conn->query($sqlVentasPDF);

// Consulta para obtener Inventario inventario.php
$sqlInventario = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.cantidad_en_stock, p.imagen, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        ORDER BY p.id_producto DESC";
$resultInventario = $conn->query($sqlInventario);

// Total de unidades disponibles de todos los productos
$sqlTotalUnidades = "SELECT SUM(cantidad_en_stock) AS total_unidades FROM productos;";
$resultTotalUnidades = $conn->query($sqlTotalUnidades);
$total_unidades = $resultTotalUnidades->fetch_assoc()['total_unidades'];

// Consulta para obtener todos los productos
$sqlUsuarios = "SELECT id_usuario, nombreusu, contrasena, es_admin, sucursal_asignada 
                 FROM usuarios;";
$resultUsuarios = $conn->query($sqlUsuarios);

// Sucursales nombre, agg usuario
$sqlSucursales = "SELECT nombre_sucursal FROM sucursales";
$resultSucursales = $conn->query($sqlSucursales);

// Sucursales nombre, edit usuario
$sqlSucursalesEditU = "SELECT nombre_sucursal FROM sucursales";
$resultSucursalesEditU = $conn->query($sqlSucursalesEditU);

// Sucursales 
$sqlDatosSucursales = "SELECT id_sucursal, nombre_sucursal, direccion, num_tel FROM sucursales";
$resultDatosSucursales = $conn->query($sqlDatosSucursales);

// Consulta para Caja
$consultaCaja = "SELECT id_producto, nombre, descripcion, precio, cantidad_en_stock FROM productos WHERE cantidad_en_stock > 0";
$resultadoCaja = $conn->query($consultaCaja);

// Venta Mayor
$consultaVentaMayor = "SELECT fecha_venta, total FROM ventas ORDER BY total DESC LIMIT 1";
$resultadoVentaMayor = $conn->query($consultaVentaMayor);

// Consultar el total vendido desde el último retiro
$queryVentas = "SELECT IFNULL(SUM(total), 0) AS total_vendido 
                FROM ventas 
                WHERE fecha_venta >= (SELECT IFNULL(MAX(fecha), '1970-01-01') FROM retiros)";
$resultVentas2 = $conn->query($queryVentas);
$totalVendido = $resultVentas2->fetch_assoc()['total_vendido'];

// Consultar retiros anteriores
$queryRetiros = "SELECT IFNULL(SUM(monto), 0) AS total_retiros FROM retiros";
$resultRetiros = $conn->query($queryRetiros);
$totalRetiros = $resultRetiros->fetch_assoc()['total_retiros'];

$query = "SELECT r.id, r.monto, r.fecha, u.nombreusu AS nombreusu, r.observaciones
          FROM retiros r
          JOIN usuarios u ON r.id_usuario = u.id_usuario
          ORDER BY r.fecha DESC";

$resultadoRetiros = $conn->query($query);

// Consulta SQL para dividir los totales
$queryVentas = "
SELECT 
    SUM(CASE WHEN p.nombre = 'Lotines' THEN dv.cantidad * dv.precio ELSE 0 END) AS total_lotines,
    SUM(CASE WHEN p.nombre != 'Lotines' THEN dv.cantidad * dv.precio ELSE 0 END) AS total_otros
FROM detalleventas dv
INNER JOIN productos p ON dv.id_producto = p.id_producto
INNER JOIN ventas v ON dv.id_venta = v.id_venta
WHERE v.fecha_venta >= (SELECT IFNULL(MAX(fecha), '1970-01-01') FROM retiros);
";

// Ejecutar la consulta
$resultVentas = $conn->query($queryVentas);

// Valores predeterminados
$totalLotines = 0;
$totalOtros = 0;

// Verificar resultados
if ($resultVentas && $resultVentas->num_rows > 0) {
    $row = $resultVentas->fetch_assoc();
    $totalLotines = $row['total_lotines'];
    $totalOtros = $row['total_otros'];
}
?>