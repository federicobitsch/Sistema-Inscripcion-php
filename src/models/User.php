<?php
require_once __DIR__ . '/../../lib/Crud.php';

class User {
    private $crud;
    private $conn;

    public function __construct($conn) {
        $this->crud = new Crud($conn);
        $this->conn = $conn;
    }

    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->crud->create('usuario', $data);
    }

    public function findByEmail($email) {
        return $this->crud->readOne("SELECT * FROM usuario WHERE email = ?", [$email]);
    }

    public function findById($id) {   // FUNCIONAL PARA PDF
        return $this->crud->readOne("SELECT * FROM usuario WHERE id = ?", [$id]);
    }

    public function all() {
        return $this->crud->readAll("SELECT * FROM usuario");
    }

    public function delete($id) {
        return $this->crud->delete('usuario', 'id = ?', [$id]);
    }
}
?>
