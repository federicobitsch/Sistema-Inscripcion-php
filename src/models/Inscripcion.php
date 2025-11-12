<?php
require_once __DIR__ . '/../../lib/Crud.php';

class Inscripcion {
    private $crud;
    private $conn;
    public function __construct($conn) {
        $this->crud = new Crud($conn);
        $this->conn = $conn;
    }

    public function obtenerPorUsuario(int $usuario_id) {
        return $this->crud->readOne("SELECT * FROM inscripcion WHERE usuario_id = ?", [$usuario_id]);
    }

    public function crear(array $data) {
        return $this->crud->create('inscripcion', $data); // devuelve insert_id o true dep. impl.
    }

    public function actualizar(array $data, int $usuario_id) {
        return $this->crud->update('inscripcion', $data, 'usuario_id = ?', [$usuario_id]);
    }
}
