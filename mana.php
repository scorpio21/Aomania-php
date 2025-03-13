<?php
require 'config.php';

if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

$clases = ['Mago', 'Brujo', 'Clerigo', 'Druida', 'Hechicero'];
$inteligencias = [17, 18, 19, 20, 21];
$resultados = [];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clase = sanitizar($_POST['clase']);
    $inteligencia = (int)$_POST['inteligencia'];
    $nivel = (int)$_POST['nivel'];

    // Validaciones
    if (!in_array($clase, $clases)) $errores[] = "Clase no válida";
    if (!in_array($inteligencia, $inteligencias)) $errores[] = "Inteligencia no válida";
    if ($nivel < 1 || $nivel > 300) $errores[] = "Nivel debe ser 1-300";

    if (empty($errores)) {
        // Lógica de cálculo
        $AumentoMinMP = 4;
        $AumentoMaxMP = 6;

        switch ($clase) {
            case 'Mago':
                if ($inteligencia >= 21) list($AumentoMinMP, $AumentoMaxMP) = [9, 12];
                elseif ($inteligencia == 20) list($AumentoMinMP, $AumentoMaxMP) = [8, 11];
                elseif ($inteligencia == 19) list($AumentoMinMP, $AumentoMaxMP) = [7, 10];
                break;
            
            case 'Brujo':
                if ($inteligencia >= 21) list($AumentoMinMP, $AumentoMaxMP) = [8, 11];
                elseif ($inteligencia == 20) list($AumentoMinMP, $AumentoMaxMP) = [7, 10];
                break;
            
            case 'Clerigo':
                if ($inteligencia >= 21) list($AumentoMinMP, $AumentoMaxMP) = [7, 10];
                elseif ($inteligencia == 20) list($AumentoMinMP, $AumentoMaxMP) = [6, 9];
                break;
            
            default:
                $AumentoMinMP = 5;
                $AumentoMaxMP = 8;
        }

        $minMP = 15 + $AumentoMinMP * ($nivel - 1);
        $maxMP = 20 + ($inteligencia / 2) + $AumentoMaxMP * ($nivel - 1);
        $medioMP = ($minMP + $maxMP) / 2;

        $resultados = [
            'min' => $minMP,
            'med' => $medioMP,
            'max' => $maxMP,
            'incrementos' => [
                'min' => $AumentoMinMP,
                'max' => $AumentoMaxMP,
                'med' => ($AumentoMinMP + $AumentoMaxMP) / 2
            ]
        ];

        // Guardar en historial
        $historial = cargarDatos(DATA_DIR . '/mana.json');
        $historial[] = [
            'fecha' => date('Y-m-d H:i:s'),
            'usuario' => $_SESSION['usuario']['username'],
            'clase' => $clase,
            'resultados' => $resultados
        ];
        guardarDatos(DATA_DIR . '/mana.json', $historial);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calculadora de Maná</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <a href="cargar.php" class="btn volver">← Volver</a>
        <h2>Calculadora de Maná</h2>
        
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <?php foreach ($errores as $error): ?>
                    <div class="error">⚠️ <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-mana">
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
                <label>Inteligencia:</label>
                <select name="inteligencia" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($inteligencias as $num): ?>
                        <option <?= $num == ($_POST['inteligencia'] ?? '') ? 'selected' : '' ?>><?= $num ?></option>
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
                        <div class="progreso" style="width: <?= ($resultados['min'] / 1000) * 100 ?>%"></div>
                        <span>Mínimo: <?= round($resultados['min']) ?> MP</span>
                    </div>
                    <div class="barra">
                        <div class="progreso" style="width: <?= ($resultados['med'] / 1000) * 100 ?>%"></div>
                        <span>Medio: <?= round($resultados['med']) ?> MP</span>
                    </div>
                    <div class="barra">
                        <div class="progreso" style="width: <?= ($resultados['max'] / 1000) * 100 ?>%"></div>
                        <span>Máximo: <?= round($resultados['max']) ?> MP</span>
                    </div>
                </div>
                
                <div class="incrementos">
                    <h4>Incrementos por nivel:</h4>
                    <p>Mínimo: <?= $resultados['incrementos']['min'] ?> MP</p>
                    <p>Máximo: <?= $resultados['incrementos']['max'] ?> MP</p>
                    <p>Promedio: <?= $resultados['incrementos']['med'] ?> MP</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>