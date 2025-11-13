<?php
require_once __DIR__ . '/../models/Inscripcion.php';
require_once __DIR__ . '/../utils/UploadHandler.php';
require_once __DIR__ . '/../utils/Captcha.php';

class InscripcionController {
    private $model;
    private $upload;

    public function __construct($conn) {
        $this->model = new Inscripcion($conn);   // nombre correcto
        $this->upload = new UploadHandler();
    }

    public function getByUser($uid) {
        return $this->model->obtenerPorUsuario($uid);  // coincide con Inscripcion.php
    }

    public function saveDraft($uid, $data, $file) {
        $existing = $this->getByUser($uid);
        $filePath = null;

        
        if ($file && $file['error'] != UPLOAD_ERR_NO_FILE) {
            $filePath = $this->upload::guardar($file, $uid);
            $data['documento'] = $filePath;
        }

        $data['usuario_id'] = $uid;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($existing) {
            return $this->model->actualizar($data, $uid);
        } else {
            return $this->model->crear($data);
        }
    }

    public function confirm($uid, $captchaInput) {
        if (!Captcha::verify($captchaInput)) {
            throw new Exception("Captcha inválido.");
        }
        $data = [
            'estado' => 'confirmada',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->model->actualizar($data, $uid);
    }
}
?>
