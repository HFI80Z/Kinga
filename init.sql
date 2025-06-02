CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

INSERT INTO users(email,password,role)
VALUES ('admin@example.com','admin','admin');

CREATE TABLE vehicles (
  id SERIAL PRIMARY KEY,
  immatriculation VARCHAR(20) NOT NULL UNIQUE,
  type VARCHAR(50),
  fabricant VARCHAR(100),
  modele VARCHAR(100),
  couleur VARCHAR(50),
  nb_sieges INTEGER,
  km INTEGER,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS repairers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  contact VARCHAR(255),
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS maintenance (
  id SERIAL PRIMARY KEY,
  vehicle_id INTEGER NOT NULL REFERENCES vehicles(id) ON DELETE CASCADE,
  repairer_id INTEGER REFERENCES repairers(id) ON DELETE SET NULL,
  reason TEXT NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS maintenance_parts (
  id SERIAL PRIMARY KEY,
  maintenance_id INTEGER NOT NULL REFERENCES maintenance(id) ON DELETE CASCADE,
  part_name VARCHAR(255) NOT NULL,
  quantity INTEGER NOT NULL DEFAULT 1
);
