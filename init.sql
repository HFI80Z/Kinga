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

INSERT INTO vehicles(immatriculation,type,fabricant,modele,couleur,nb_sieges,km)
VALUES
  ('XYZ-999','BERLINE','Toyota','Corolla','Bleu',5,45000),
  ('ABC-123','SUV','Ford','Explorer','Noir',7,32000);
