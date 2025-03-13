<?php
header('Content-Type: text/html; charset=UTF-8');
require 'config.php';

if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

$clases = ['Mago', 'Guerrero', 'Arquero', 'Clerigo', 'Druida'];
$carismas = [17, 18, 19, 20, 21];
$criaturas = ['Dragón', 'Grifo', 'Unicornio', 'Fénix', 'Tigre']; // Criaturas con acentos
$resultados = [];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clase = sanitizar($_POST['clase']);
    $carisma = (int)$_POST['carisma'];
    $criatura = sanitizar($_POST['criatura']);

    // Validaciones
    if (!in_array($clase, $clases)) $errores[] = "Selecciona una clase válida";
    if (!in_array($carisma, $carismas)) $errores[] = "Selecciona un valor de carisma válido";
    if (!in_array($criatura, $criaturas)) $errores[] = "Selecciona una criatura válida";

    if (empty($errores)) {
        // Lógica de cálculo (adaptada de VB6)
        $skillNecesario = 0;

        switch (strtoupper($clase)) {
            case 'MAGO':
                $skillNecesario = 50 + ($carisma * 2);
                break;
            case 'GUERRERO':
                $skillNecesario = 60 + ($carisma * 1.5);
                break;
            case 'ARQUERO':
                $skillNecesario = 55 + ($carisma * 1.8);
                break;
            case 'CLERIGO':
                $skillNecesario = 45 + ($carisma * 2.2);
                break;
            case 'DRUIDA':
                $skillNecesario = 40 + ($carisma * 2.5);
                break;
            default:
                $skillNecesario = 50; // Valor por defecto
        }

        // Ajustar según la criatura
        switch ($criatura) {
            case 'Dragón':
                $skillNecesario += 30;
                break;
            case 'Grifo':
                $skillNecesario += 20;
                break;
            case 'Unicornio':
                $skillNecesario += 25;
                break;
            case 'Fénix':
                $skillNecesario += 35;
                break;
            case 'Tigre':
                $skillNecesario += 15;
                break;
        }

        $resultados = [
            'clase' => $clase,
            'carisma' => $carisma,
            'criatura' => $criatura,
            'skill' => round($skillNecesario)
        ];

        // Guardar en historial
        $historial = cargarDatos(DATA_DIR . '/domar.json') ?? [];
        $historial[] = [
            'fecha' => date('Y-m-d H:i:s'),
            'usuario' => $_SESSION['usuario']['username'],
            'resultados' => $resultados
        ];
        guardarDatos(DATA_DIR . '/domar.json', $historial);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Calculadora de Domar</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <a href="cargar.php" class="btn volver">? Volver</a>
        <h2>Calculadora de Domar</h2>
        
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <?php foreach ($errores as $error): ?>
                    <div class="error">?? <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-domar">
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
                <label>Carisma:</label>
                <select name="carisma" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($carismas as $num): ?>
                        <option <?= $num == ($_POST['carisma'] ?? '') ? 'selected' : '' ?>><?= $num ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label>Criatura:</label>
                <select name="criatura" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($criaturas as $cri): ?>
                        <option <?= $cri == ($_POST['criatura'] ?? '') ? 'selected' : '' ?>><?= $cri ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn calcular">Calcular</button>
        </form>

        <?php if (!empty($resultados)): ?>
            <div class="resultados">
                <h3>Resultados:</h3>
                <p>El skill necesario para domar un/a <strong><?= $resultados['criatura'] ?></strong> con un pj <strong><?= $resultados['clase'] ?></strong> y carisma <strong><?= $resultados['carisma'] ?></strong> es:</p>
                <div class="skill">
                    <span><?= $resultados['skill'] ?> Skill</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>