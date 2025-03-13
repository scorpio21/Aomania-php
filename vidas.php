<?php
require 'config.php';

if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

$clases = ['Guerrero', 'Arquero', 'Mago', 'Clerigo', 'Druida'];
$constituciones = [17, 18, 19, 20, 21];
$resultados = [];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clase = sanitizar($_POST['clase']);
    $consti = (int)$_POST['consti'];
    $nivel = (int)$_POST['nivel'];

    // Validaciones
    if (!in_array($clase, $clases)) $errores[] = "Clase no válida";
    if (!in_array($consti, $constituciones)) $errores[] = "Constitución no válida";
    if ($nivel < 1 || $nivel > 300) $errores[] = "Nivel debe ser 1-300";

    if (empty($errores)) {
        // Lógica de cálculo (adaptada de VB6)
        $AumentoMinHP = 4;
        $AumentoMaxHP = 6;

        switch ($clase) {
            case 'Guerrero':
                if ($consti >= 21) list($AumentoMinHP, $AumentoMaxHP) = [9, 12];
                elseif ($consti == 20) list($AumentoMinHP, $AumentoMaxHP) = [9, 11];
                // ... completar otros casos
                break;
            case 'Mago':
                if ($consti >= 21) list($AumentoMinHP, $AumentoMaxHP) = [6, 9];
                // ... otros casos
                break;
        }

        $minHP = 16 + $AumentoMinHP * ($nivel - 1);
        $maxHP = 15 + ($consti / 3) + $AumentoMaxHP * ($nivel - 1);
        $medioHP = ($minHP + $maxHP) / 2;

        $resultados = [
            'min' => $minHP,
            'med' => $medioHP,
            'max' => $maxHP,
            'incrementos' => [
                'min' => $AumentoMinHP,
                'max' => $AumentoMaxHP,
                'med' => ($AumentoMinHP + $AumentoMaxHP) / 2
            ]
        ];

        // Guardar en historial
        $historial = cargarDatos(VIDAS_FILE);
        $historial[] = [
            'fecha' => date('Y-m-d H:i:s'),
            'usuario' => $_SESSION['usuario']['username'],
            'clase' => $clase,
            'resultados' => $resultados
        ];
        guardarDatos(VIDAS_FILE, $historial);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calculadora de Vidas</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <a href="cargar.php" class="btn volver">← Volver</a>
        <h2>Calculadora de Vidas</h2>
        
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <?php foreach ($errores as $error): ?>
                    <div class="error">⚠️ <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-vidas">
            <div class="campo">
                <label>Clase:</label>
                <select name="clase" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($clases as $c): ?>
                        <option <?= $c == ($_POST['clase'] ?? '') ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label>Constitución:</label>
                <select name="consti" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($constituciones as $num): ?>
                        <option <?= $num == ($_POST['consti'] ?? '') ? 'selected' : '' ?>><?= $num ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label>Nivel (1-300):</label>
                <input type="number" name="nivel" value="<?= $_POST['nivel'] ?? '' ?>" min="1" max="300" required>
            </div>

            <button type="submit" class="btn calcular">Calcular</button>
        </form>

        <?php if (!empty($resultados)): ?>
            <div class="resultados">
                <h3>Resultados:</h3>
                <div class="barras">
                    <div class="barra">
                        <div class="progreso" style="width: <?= ($resultados['min'] / 750) * 100 ?>%"></div>
                        <span>Mínima: <?= $resultados['min'] ?></span>
                    </div>
                    <div class="barra">
                        <div class="progreso" style="width: <?= ($resultados['med'] / 750) * 100 ?>%"></div>
                        <span>Media: <?= round($resultados['med']) ?></span>
                    </div>
                    <div class="barra">
                        <div class="progreso" style="width: <?= ($resultados['max'] / 750) * 100 ?>%"></div>
                        <span>Máxima: <?= $resultados['max'] ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>