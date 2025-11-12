<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Sistema de Inscripción</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h1>Sistema de Inscripciones</h1>
      <p class="subtitle">Ingresa tus credenciales</p>

      <form method="POST" action="../src/controllers/AuthController.php">
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" placeholder="darkor@gmail.com" required>
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
        </div>

        <button type="submit" name="login">Entrar</button>
      </form>

      <p class="footer-text">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
  </div>
</body>
</html>
