<?php
session_start();

// Configuración de rutas
define('DATA_DIR', __DIR__ . '/data');
define('USERS_FILE', DATA_DIR . '/users.json');
define('VIDAS_FILE', DATA_DIR . '/vidas.json');
define('MANA_FILE', DATA_DIR . '/mana.json');
define('STATS_FILE', DATA_DIR . '/stats.json');
define('LOGS_FILE', DATA_DIR . '/logs.json');

// Funciones esenciales
function sanitizar($dato) {
    return htmlspecialchars($dato ?? '', ENT_QUOTES, 'UTF-8');
}

function cargarDatos($archivo) {
    if (!file_exists($archivo)) {
        return [];
    }
    $contenido = file_get_contents($archivo);
    return $contenido ? json_decode($contenido, true) : [];
}

function guardarDatos($archivo, $datos) {
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT));
}

function estaAutenticado() {
    return isset($_SESSION['usuario']);
}

function esAdmin() {
    return (
        estaAutenticado() 
        && isset($_SESSION['usuario']['rol']) 
        && $_SESSION['usuario']['rol'] === 'admin'
    );
}

function registrarLog($accion) {
    $logs = cargarDatos(LOGS_FILE);
    $logs[] = [
        'fecha' => date('Y-m-d H:i:s'),
        'usuario' => $_SESSION['usuario']['username'] ?? 'Invitado',
        'accion' => $accion
    ];
    guardarDatos(LOGS_FILE, $logs);
}