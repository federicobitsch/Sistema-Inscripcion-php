<?php
class Crud {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // CREATE
    public function create(string $table, array $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error en la preparación del INSERT: " . $this->conn->error);
        }

        $types = str_repeat("s", count($data)); 
        $stmt->bind_param($types, ...array_values($data));

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar INSERT: " . $stmt->error);
        }

        return $this->conn->insert_id;
    }

    // READ 
    public function readOne(string $query, array $params = []) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) throw new Exception("Error en SELECT: " . $this->conn->error);

        if (!empty($params)) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // READ ALL
    public function readAll(string $query, array $params = []) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) throw new Exception("Error en SELECT: " . $this->conn->error);

        if (!empty($params)) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // UPDATE
    public function update(string $table, array $data, string $where, array $params = []) {
        $set = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $where";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception("Error en UPDATE: " . $this->conn->error);

        $types = str_repeat("s", count($data) + count($params));
        $values = [...array_values($data), ...$params];
        $stmt->bind_param($types, ...$values);

        $stmt->execute();
        return $stmt->affected_rows >= 0;
    }

    // DELETE
    public function delete(string $table, string $where, array $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception("Error en DELETE: " . $this->conn->error);

        if (!empty($params)) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
?>
