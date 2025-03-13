<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizar($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $users = cargarDatos(USERS_FILE);
    $usuarioExistente = false;
    
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $usuarioExistente = true;
            break;
        }
    }

    if (!$usuarioExistente) {
        $users[] = [
            'username' => $username,
            'password' => $password,
            'rol' => 'usuario' // Rol por defecto
        ];
        guardarDatos(USERS_FILE, $users);
        registrarLog("Nuevo registro: $username");
        header('Location: login.php');
        exit;
    } else {
        $error = "El usuario ya existe";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <h2>Registro de Usuario</h2>
        <?php if (isset($error)): ?>
            <div class="error">⚠️ <?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" class="btn">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>