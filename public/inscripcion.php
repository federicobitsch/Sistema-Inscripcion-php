<?php
// public/inscripcion.php
declare(strict_types=1);
session_start();

// seguridad: solo usuarios logueados
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// zona horaria para timestamps correctos
date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../src/models/Inscripcion.php';
require_once __DIR__ . '/../src/utils/UploadHandler.php';

// Conexión
$db = new Database();
$conn = $db->connect();
$model = new Inscripcion($conn);

$userId = intval($_SESSION['user_id']);
$registro = $model->obtenerPorUsuario($userId);

// Mensajes para mostrar en la vista
$errors = [];
$success = null;

// Si viene POST -> guardar (crear o actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitizar entrada
    $materia = trim(filter_input(INPUT_POST, 'materia', FILTER_SANITIZE_STRING));

    // Validaciones backend
    if ($materia === '' || strlen($materia) < 2) {
        $errors[] = "La materia es obligatoria y debe tener al menos 2 caracteres.";
    }

    // Manejo de archivo (opcional si ya existe registro)
    $archivoRuta = $registro['documento'] ?? null;
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] !== UPLOAD_ERR_NO_FILE) {
        try {
            // UploadHandler::guardar valida tamaño/extensión y devuelve ruta relativa 'uploads/{id}/archivo'
            $nuevaRuta = UploadHandler::guardar($_FILES['documento'], $userId);

            // Si había un archivo anterior, lo eliminamos para no acumular
            if (!empty($archivoRuta) && file_exists(__DIR__ . '/../' . $archivoRuta)) {
                @unlink(__DIR__ . '/../' . $archivoRuta);
            }

            $archivoRuta = $nuevaRuta;
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    } else {
        // Si no sube y no hay registro previo -> error
        if (!$registro) {
            $errors[] = "Debe adjuntar un documento (PDF/JPG/PNG).";
        }
    }

    // Si no hay errores -> guardar en BD
    if (empty($errors)) {
        $data = [
            'usuario_id'   => $userId,
            'materia'      => $materia,
            'documento'    => $archivoRuta,
            'estado'       => 'borrador',
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        if ($registro) {
            // update: evitar cambiar created_at
            $ok = $model->actualizar($data, $userId);
            if ($ok) $success = "Inscripción actualizada correctamente.";
            else $errors[] = "Fallo al actualizar la inscripción.";
        } else {
            // create: set created_at
            $data['created_at'] = date('Y-m-d H:i:s');
            $ok = $model->crear($data);
            if ($ok) $success = "Inscripción creada correctamente.";
            else $errors[] = "Fallo al crear la inscripción.";
        }
        // recargar registro después de guardar
        $registro = $model->obtenerPorUsuario($userId);
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="inscripcion.css">
  <title>Inscripción</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px}input,select{padding:6px;margin:6px 0;width:100%}label{font-weight:bold}</style>
</head>
<body>

<h2>Inscripción</h2>
<p><a href="logout.php">Cerrar sesión</a> | <a href="confirmar.php">Confirmar inscripción</a></p>

<?php if($success): ?>
  <div style="background:#e6ffee;padding:10px;border:1px solid #2ecc71"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if(!empty($errors)): ?>
  <div style="background:#ffe6e6;padding:10px;border:1px solid #e74c3c">
    <ul><?php foreach($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
  </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" novalidate>
  <label for="materia">Materia / Curso (obligatorio)</label>
  <input id="materia" name="materia" type="text" required value="<?php echo htmlspecialchars($registro['materia'] ?? ''); ?>">

  <label for="documento">Documento (PDF/JPG/PNG) <?php echo $registro && $registro['documento'] ? "(actual: " . htmlspecialchars(basename($registro['documento'])) . ")" : ""; ?></label>
  <input id="documento" name="documento" type="file" accept=".pdf,.jpg,.jpeg,.png">

  <button type="submit">Guardar</button>
</form>
<?php if($registro): ?>
<div class="status-info"> 
  <h3>Estado actual</h3>
  <p><strong>Materia:</strong> <?php echo htmlspecialchars($registro['materia']); ?></p>
  <p><strong>Documento:</strong> <?php echo $registro['documento'] ? "<a href='../" . htmlspecialchars($registro['documento']) . "' target='_blank'>Ver</a>" : "No existe"; ?></p>
  <p><strong>Estado:</strong> <?php echo htmlspecialchars($registro['estado']); ?></p>
  <p><strong>Creado:</strong> <?php echo htmlspecialchars($registro['created_at']); ?></p>
  <p><strong>Última actualización:</strong> <?php echo htmlspecialchars($registro['updated_at']); ?></p>
</div>
<?php endif; ?>

</body>
</html>
