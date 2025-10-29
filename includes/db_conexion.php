<?php
// Configuración para la base de datos de ELEMENTOS (Ayudantia)
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario'); // Cambia a tu usuario de MySQL
define('DB_PASS', 'tu_password'); // Cambia a tu contraseña de MySQL
define('DB_NAME_AYUDANTIA', 'Ayudantia');

// Configuración para la base de datos de PRESTAMOS (Biblioteca)
define('DB_NAME_BIBLIOTECA', 'Biblioteca');

// Función de conexión a la DB de Ayudantia
function conectarAyudantia() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME_AYUDANTIA);
    if ($conexion->connect_error) {
        die("Conexión fallida a Ayudantia: " . $conexion->connect_error);
    }
    $conexion->set_charset("utf8");
    return $conexion;
}

// Función de conexión a la DB de Biblioteca
function conectarBiblioteca() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME_BIBLIOTECA);
    if ($conexion->connect_error) {
        die("Conexión fallida a Biblioteca: " . $conexion->connect_error);
    }
    $conexion->set_charset("utf8");
    return $conexion;
}
?>
