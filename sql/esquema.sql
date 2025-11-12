CREATE DATABASE IF NOT EXISTS sistema_inscripcion CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sistema_inscripcion;

-- Tabla de usuarios
CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de inscripciones
CREATE TABLE inscripcion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL UNIQUE,
  materia VARCHAR(100) NOT NULL,
  documento VARCHAR(255),
  estado ENUM('borrador', 'confirmada', 'pendiente') DEFAULT 'borrador',
  created_at DATETIME,
  updated_at DATETIME,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- Usuario demo
INSERT INTO usuario (nombre, email, password, is_admin) VALUES
('Federico', 'fede@mail.com', '$2y$10$/D0HiBy30jWVKFIRZupmVeiaQ7ioZVYc4HT8Hdhvx.lMCdWiM7KVu', 1);
-- Contraseña: Demo1234
