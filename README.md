# Vehicle Manager MVC Project

## Installation

```bash
docker-compose up -d
```

## Structure

- `src/`
  - `core/`  : framework de base (Router, Controller, Model, View, Auth)
  - `config/`: configuration base de données
  - `controllers/VehicleController.php`: logique listing et CRUD
  - `models/Vehicle.php`         : modèle Vehicle
  - `views/vehicles/`            : vues index, form, modal
- `public/index.php`  : point d'entrée

## Usage

- Lancer `docker-compose up`
- Accéder à `http://localhost:8000` pour voir la liste des véhicules
- Auth en admin pour créer/modifier/supprimer
