-- Crear base y tablas
CREATE DATABASE IF NOT EXISTS agenda_juanita
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE agenda_juanita;

-- Tabla clientes
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  correo VARCHAR(150) NOT NULL,
  dni VARCHAR(20) NOT NULL,
  telefono VARCHAR(30),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_clientes_correo (correo),
  UNIQUE KEY uq_clientes_dni (dni)
) ENGINE=InnoDB;

-- Tabla citas
CREATE TABLE IF NOT EXISTS citas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  asunto VARCHAR(200) NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  direccion VARCHAR(255) NOT NULL,
  referencia VARCHAR(255),
  notas TEXT,
  estado ENUM('Pendiente','Confirmada','Cancelada','Completada') DEFAULT 'Pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_citas_clientes
    FOREIGN KEY (client_id) REFERENCES clientes(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  KEY idx_citas_fecha (fecha),
  KEY idx_citas_cliente (client_id)
) ENGINE=InnoDB;
