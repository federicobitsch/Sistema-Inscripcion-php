<?php
class UploadHandler {
    public static function guardar(array $file, int $userId): string {
        if (!isset($file['error'])) throw new Exception("No se recibió archivo.");
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error al subir archivo (code {$file['error']}).");
        }

        $allowed = ['pdf','jpg','jpeg','png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) throw new Exception("Formato no permitido: {$ext}");
        if ($file['size'] > $maxSize) throw new Exception("Archivo demasiado grande (máx 2MB).");

        $dir = __DIR__ . '/../../uploads/' . intval($userId) . '/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
        $newName = "INSCRIPCION_{$userId}_{$safeBase}." . $ext;
        $dest = $dir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $dest)) throw new Exception("No se pudo mover el archivo al servidor.");

        return 'uploads/' . intval($userId) . '/' . $newName; // ruta relativa para guardar en DB
    }
}
