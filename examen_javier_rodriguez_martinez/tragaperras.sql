CREATE DATABASE IF NOT EXISTS tragaperras
  CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE tragaperras;

CREATE TABLE jugadores (
  login     VARCHAR(20) NOT NULL PRIMARY KEY,
  clave     VARCHAR(20) NOT NULL,
  nombre    VARCHAR(50) NOT NULL,
  partidas  INT NOT NULL DEFAULT 0,
  tiradas   INT NOT NULL DEFAULT 0,
  balance   INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO jugadores (login, clave, nombre) VALUES
  ('ana',   'ana123',   'Ana García'),
  ('luis',  'luis123',  'Luis Pérez'),
  ('marta', 'marta123', 'Marta Díaz'),
  ('pedro', 'pedro123', 'Pedro Sanz');
