<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizar($_POST['username']);
    $password = sanitizar($_POST['password']);

    $users = cargarDatos(USERS_FILE);
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['usuario'] = $user;
            registrarLog("Inicio de sesión exitoso");
            header('Location: cargar.php');
            exit;
        }
    }
    $error = "Usuario o contraseña incorrectos";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)): ?>
            <div class="error">⚠️ <?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" class="btn">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>