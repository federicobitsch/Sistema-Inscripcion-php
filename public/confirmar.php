<?php
declare(strict_types=1);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../src/controllers/Captcha.php';
require_once __DIR__ . '/../src/models/Inscripcion.php';

// Inicializamos captcha
Captcha::generate();

$db = new Database();
$conn = $db->connect();
$model = new Inscripcion($conn);
$userId = intval($_SESSION['user_id']);
$registro = $model->obtenerPorUsuario($userId);

if (!$registro) {
    die("No existe inscripción para confirmar. <a href='inscripcion.php'>Volver</a>");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captchaInput = trim($_POST['captcha'] ?? '');
    if (!Captcha::verify($captchaInput)) {
        $msg = "❌ Captcha incorrecto. Intente de nuevo.";
    } else {
        // Validar campos completos
        if (empty($registro['materia']) || empty($registro['documento'])) {
            $msg = "❌ Faltan completar datos o adjuntar archivo.";
        } else {
            // Confirmar inscripción
            $data = [
                'estado' => 'confirmada',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $model->actualizar($data, $userId);
            $msg = "✅ Inscripción confirmada correctamente. 
            <a href='descargar_comprobante.php'>Descargar comprobante PDF</a>";
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Confirmar inscripción</title>
<link rel="stylesheet" href="confirmar.css">
<style>body{font-family:Arial;padding:20px;}input{padding:6px;margin:5px;}button{padding:8px 16px;}</style>
</head>
<body>

<h2>Confirmar inscripción</h2>
<p><a href="inscripcion.php">Volver a inscripción</a></p>

<?php if($msg): ?>
<div style="background:#eef;padding:10px;border:1px solid #888;">
  <?php echo $msg; ?>
</div>
<?php endif; ?>

<form method="POST">
  <p>¿Cuánto es 2 + 3?</p>
  <input type="text" name="captcha" required placeholder="Respuesta">
  <button type="submit">Confirmar</button>
</form>

</body>
</html>
