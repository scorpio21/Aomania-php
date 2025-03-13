<?php
require 'config.php';

if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

$stats = cargarDatos(STATS_FILE) ?? []; // Inicializar como array
$errores = [];
$exito = '';

// Obtener stats del usuario actual
$usuario = $_SESSION['usuario']['username'];
$misStats = array_filter($stats, function($item) use ($usuario) {
    return $item['usuario'] === $usuario;
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitizar($_POST['nombre']);
    $fuerza = (int)$_POST['fuerza'];
    $agilidad = (int)$_POST['agilidad'];
    $inteligencia = (int)$_POST['inteligencia'];

    // Validaciones
    if ($fuerza < 1 || $fuerza > 100) $errores[] = "Fuerza inválida (1-100)";
    if ($agilidad < 1 || $agilidad > 100) $errores[] = "Agilidad inválida (1-100)";
    if ($inteligencia < 1 || $inteligencia > 100) $errores[] = "Inteligencia inválida (1-100)";

    if (empty($errores)) {
        $nuevoStat = [
            'usuario' => $usuario,
            'nombre' => $nombre,
            'fuerza' => $fuerza,
            'agilidad' => $agilidad,
            'inteligencia' => $inteligencia,
            'fecha' => date('Y-m-d H:i:s')
        ];

        $stats[] = $nuevoStat;
        guardarDatos(STATS_FILE, $stats);
        $exito = "¡Stats modificados exitosamente!";
        registrarLog("Modificación de stats: $nombre");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modificador de Stats</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <a href="cargar.php" class="btn volver">← Volver</a>
        <h2>Modificador de Stats</h2>
        
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <?php foreach ($errores as $error): ?>
                    <div class="error">⚠️ <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($exito): ?>
            <div class="exito">✅ <?= $exito ?></div>
        <?php endif; ?>

        <form method="post" class="form-stats">
            <div class="campo">
                <label>Nombre del Personaje:</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="campo">
                <label>Fuerza (1-100):</label>
                <input type="number" name="fuerza" min="1" max="100" required>
            </div>

            <div class="campo">
                <label>Agilidad (1-100):</label>
                <input type="number" name="agilidad" min="1" max="100" required>
            </div>

            <div class="campo">
                <label>Inteligencia (1-100):</label>
                <input type="number" name="inteligencia" min="1" max="100" required>
            </div>

            <button type="submit" class="btn guardar">💾 Guardar Stats</button>
        </form>

        <?php if (!empty($misStats)): ?>
            <div class="mis-stats">
                <h3>Tus Stats Guardados:</h3>
                <ul>
                    <?php foreach ($misStats as $stat): ?>
                        <li>
                            <strong><?= $stat['nombre'] ?></strong>:
                            Fuerza <?= $stat['fuerza'] ?>,
                            Agilidad <?= $stat['agilidad'] ?>,
                            Inteligencia <?= $stat['inteligencia'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>