<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventcande";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);
$conn ->set_charset("utf8");
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// No imprimir mensajes aquí

// Aquí puedes realizar consultas o ejecutar operaciones en la base de datos
