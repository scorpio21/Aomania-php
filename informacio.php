<?php
header('Content-Type: text/html; charset=UTF-8');
require 'config.php';

if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

// Inicializar variables
$clases = [
    "GUERRERO", "PALADIN", "TRABAJADOR", "ASESINO", "LADRON", 
    "BARDO", "PIRATA", "CLERIGO", "DRUIDA", "ARQUERO", "MAGO", 
    "BRUJO", "BANDIDO"
];

$modificadores = [
    'evasion' => 1.0,
    'poder_arma' => 1.0,
    'poder_proyectil' => 1.0,
    'daño_arma' => 1.0,
    'daño_proyectil' => 1.0,
    'evasion_escudo' => 1.0,
    'poder_wrestling' => 1.0,
    'daño_wrestling' => 1.0
];

// Procesar selección de clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clase'])) {
    $claseSeleccionada = strtoupper(sanitizar($_POST['clase']));
    
    // Calcular modificadores
    switch ($claseSeleccionada) {
        case "GUERRERO":
            $modificadores = [
                'evasion' => 1.0,
                'poder_arma' => 1.0,
                'poder_proyectil' => 0.85,
                'daño_arma' => 1.1,
                'daño_proyectil' => 0.9,
                'evasion_escudo' => 1.0,
                'poder_wrestling' => 0.6,
                'daño_wrestling' => 0.4
            ];
            break;
            
        case "PALADIN":
            $modificadores = [
                'evasion' => 0.8,
                'poder_arma' => 0.9,
                'poder_proyectil' => 0.8,
                'daño_arma' => 0.85,
                'daño_proyectil' => 0.8,
                'evasion_escudo' => 0.9,
                'poder_wrestling' => 0.4,
                'daño_wrestling' => 0.4
            ];
            break;

        case "TRABAJADOR":
            $modificadores = [
                'evasion' => 0.8,
                'poder_arma' => 0.8,
                'poder_proyectil' => 0.5,
                'daño_arma' => 0.5,
                'daño_proyectil' => 0.5,
                'evasion_escudo' => 0.7,
                'poder_wrestling' => 0.5,
                'daño_wrestling' => 0.4
            ];
            break;

        case "ASESINO":
            $modificadores = [
                'evasion' => 1.0,
                'poder_arma' => 0.8,
                'poder_proyectil' => 0.75,
                'daño_arma' => 0.85,
                'daño_proyectil' => 0.75,
                'evasion_escudo' => 0.8,
                'poder_wrestling' => 0.4,
                'daño_wrestling' => 0.4
            ];
            break;

        case "LADRON":
            $modificadores = [
                'evasion' => 0.95,
                'poder_arma' => 0.75,
                'poder_proyectil' => 0.7,
                'daño_arma' => 0.75,
                'daño_proyectil' => 0.7,
                'evasion_escudo' => 0.7,
                'poder_wrestling' => 0.8,
                'daño_wrestling' => 1.05
            ];
            break;

        case "BARDO":
            $modificadores = [
                'evasion' => 0.75,
                'poder_arma' => 0.7,
                'poder_proyectil' => 0.7,
                'daño_arma' => 0.75,
                'daño_proyectil' => 0.7,
                'evasion_escudo' => 0.75,
                'poder_wrestling' => 0.4,
                'daño_wrestling' => 0.4
            ];
            break;

        case "PIRATA":
            $modificadores = [
                'evasion' => 0.8,
                'poder_arma' => 0.8,
                'poder_proyectil' => 0.75,
                'daño_arma' => 0.85,
                'daño_proyectil' => 0.75,
                'evasion_escudo' => 0.75,
                'poder_wrestling' => 0.5,
                'daño_wrestling' => 0.4
            ];
            break;

        case "CLERIGO":
            $modificadores = [
                'evasion' => 0.8,
                'poder_arma' => 0.8,
                'poder_proyectil' => 0.7,
                'daño_arma' => 0.75,
                'daño_proyectil' => 0.7,
                'evasion_escudo' => 0.9,
                'poder_wrestling' => 0.4,
                'daño_wrestling' => 0.4
            ];
            break;

        case "DRUIDA":
            $modificadores = [
                'evasion' => 0.5,
                'poder_arma' => 0.75,
                'poder_proyectil' => 0.7,
                'daño_arma' => 0.75,
                'daño_proyectil' => 0.7,
                'evasion_escudo' => 0.6,
                'poder_wrestling' => 0.4,
                'daño_wrestling' => 0.4
            ];
            break;

        case "ARQUERO":
            $modificadores = [
                'evasion' => 0.8,
                'poder_arma' => 0.5,
                'poder_proyectil' => 1.2,
                'daño_arma' => 0.75,
                'daño_proyectil' => 1.6,
                'evasion_escudo' => 0.75,
                'poder_wrestling' => 0.2,
                'daño_wrestling' => 0.6
            ];
            break;

        case "MAGO":
        case "BRUJO":
            $modificadores = [
                'evasion' => 0.5,
                'poder_arma' => 0.5,
                'poder_proyectil' => 0.6,
                'daño_arma' => 0.6,
                'daño_proyectil' => 0.6,
                'evasion_escudo' => 0.6,
                'poder_wrestling' => 0.3,
                'daño_wrestling' => 0.5
            ];
            break;

        case "BANDIDO":
            $modificadores = [
                'evasion' => 0.9,
                'poder_arma' => 0.5,
                'poder_proyectil' => 0.5,
                'daño_arma' => 0.5,
                'daño_proyectil' => 0.5,
                'evasion_escudo' => 0.7,
                'poder_wrestling' => 0.95,
                'daño_wrestling' => 1.05
            ];
            break;

        default:
            $modificadores = [
                'evasion' => 1.0,
                'poder_arma' => 1.0,
                'poder_proyectil' => 1.0,
                'daño_arma' => 1.0,
                'daño_proyectil' => 1.0,
                'evasion_escudo' => 1.0,
                'poder_wrestling' => 1.0,
                'daño_wrestling' => 1.0
            ];
            break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificadores de Clase</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <a href="cargar.php" class="btn volver">← Volver al Menú</a>
        <h2>Modificadores de Clase</h2>
        
        <form method="post">
            <div class="campo">
                <label>Seleccionar Clase:</label>
                <select name="clase" required>
                    <option value="">Selecciona una clase</option>
                    <?php foreach ($clases as $clase): ?>
                        <option value="<?= $clase ?>" <?= isset($claseSeleccionada) && $claseSeleccionada === $clase ? 'selected' : '' ?>>
                            <?= $clase ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn">Calcular Modificadores</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="resultados-modificadores">
                <h3>Modificadores para <?= $claseSeleccionada ?></h3>
                <ul class="lista-modificadores">
                    <li><span class="etiqueta">Evasion:</span> <span class="valor"><?= $modificadores['evasion'] ?></span></li>
                    <li><span class="etiqueta">Poder Arma:</span> <span class="valor"><?= $modificadores['poder_arma'] ?></span></li>
                    <li><span class="etiqueta">Poder Proyectil:</span> <span class="valor"><?= $modificadores['poder_proyectil'] ?></span></li>
                    <li><span class="etiqueta">Daño Arma:</span> <span class="valor"><?= $modificadores['daño_arma'] ?></span></li>
                    <li><span class="etiqueta">Daño Proyectil:</span> <span class="valor"><?= $modificadores['daño_proyectil'] ?></span></li>
                    <li><span class="etiqueta">Evasion Escudo:</span> <span class="valor"><?= $modificadores['evasion_escudo'] ?></span></li>
                    <li><span class="etiqueta">Poder Wrestling:</span> <span class="valor"><?= $modificadores['poder_wrestling'] ?></span></li>
                    <li><span class="etiqueta">Daño Wrestling:</span> <span class="valor"><?= $modificadores['daño_wrestling'] ?></span></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>