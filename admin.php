<?php
require 'config.php';

if (!esAdmin()) {
    header('Location: cargar.php');
    exit;
}

$usuarios = cargarDatos(USERS_FILE);
$historial = cargarDatos(VIDAS_FILE);
?>

<!DOCTYPE html>
<html>
<body>
    <h1>Panel de Administración</h1>
    
    <h2>Usuarios Registrados</h2>
    <ul>
        <?php foreach ($usuarios as $user): ?>
            <li><?= $user['username'] ?> (<?= $user['rol'] ?>)</li>
        <?php endforeach; ?>
    </ul>

    <h2>Historial de Cálculos</h2>
    <table>
        <?php foreach ($historial as $registro): ?>
            <tr>
                <td><?= $registro['fecha'] ?></td>
                <td><?= $registro['usuario'] ?></td>
                <td><?= $registro['clase'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>