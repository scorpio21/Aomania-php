<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Menú - AoMania</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <?php if (estaAutenticado()): ?>
            <h1>Bienvenido, <?= $_SESSION['usuario']['username'] ?></h1>
            <div class="menu">
                <a href="vidas.php" class="btn vida">❤️ Calcular Vidas</a>
                <a href="mana.php" class="btn mana">🔮 Calcular Maná</a>
                <a href="domar.php" class="btn domar">🐉 Calculadora de Domar</a>
                <a href="modificador.php" class="btn stats">⚔️ Modificar Stats</a>
                <a href="informacio.php" class="btn modificadores">📊 Modificadores de Clase</a>
  
                <?php if (esAdmin()): ?>
                    <a href="admin.php" class="btn admin">👑 Administración</a>
                <?php endif; ?>
               
                <a href="logout.php" class="btn salir">🚪 Cerrar Sesión</a>
            </div>
        <?php else: ?>
            <div class="login-required">
                <p>¡Debes iniciar sesión para continuar!</p>
                <a href="login.php" class="btn">🔑 Iniciar Sesión</a>
                <a href="registro.php" class="btn">📝 Registrarse</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>