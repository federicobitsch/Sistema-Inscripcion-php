<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - Sistema de Inscripción</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h1>Sistema de Inscripciones</h1>
      <p class="subtitle">Completa el formulario para registrarte</p>

      <form method="POST" action="../src/Controllers/AuthController.php">
        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
        </div>

        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" placeholder="tu@email.com" required>
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>
        </div>

        <button type="submit" name="register">Registrarse</button>
      </form>

      <p class="footer-text">¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
  </div>
</body>
</html>
