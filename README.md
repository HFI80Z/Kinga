## Kinga ## 

Une application de gestion de véhicules, réparateurs et maintenances, avec système d’authentification et interface CRUD complète pour les utilisateurs et administrateurs.

## 👨‍💻 Réalisé par
Lory Esteban, Nguyen Anthony

# 🚀 Fonctionnalités

- Authentification et inscription des utilisateurs
- Ajout, modification et suppression de véhicules
- Gestion des réparateurs
- Suivi et historique de maintenance
- Formulaire
- Base de données PostgreSQL
- Interface web propre et modulable

# 🛠️ Prérequis

- Docker
- Docker Compose
- Git
- Navigateur web (Chrome, Firefox...)


## 📦 Installation

# Clonez le projet:

```
git clone https://github.com/HFI80Z/Kinga.git
cd Kinga
```
# Lancez l'application avec Docker Compose : 
```
docker compose up --build
```
# Accédez à l'application via votre navigateur : 
```
http://localhost:8000
```
# L'Accès à pgAdmin pgAdmin est accessible via votre navigateur : 
```
http://localhost:5050
```
# 📁 Structure du projet 
```
└── Kinga.git
    ├── README.md
    ├── composer.json
    ├── composer.lock
    ├── docker-compose.yml
    ├── Dockerfile
    ├── init.sql
    ├── public/
    │   ├── index.php
    │   └── .htaccess
    ├── src/
    │   ├── config/
    │   │   └── Database.php
    │   ├── controllers/
    │   │   ├── AuthController.php
    │   │   ├── MaintenanceController.php
    │   │   ├── RepairerController.php
    │   │   └── VehicleController.php
    │   ├── core/
    │   │   ├── Auth.php
    │   │   ├── Controller.php
    │   │   ├── Model.php
    │   │   ├── Router.php
    │   │   └── View.php
    │   ├── models/
    │   │   ├── Maintenance.php
    │   │   ├── MaintenancePart.php
    │   │   ├── Repairer.php
    │   │   ├── User.php
    │   │   └── Vehicle.php
    │   └── views/
    │       ├── auth/
    │       │   ├── login.php
    │       │   └── register.php
    │       ├── maintenance/
    │       │   ├── form.php
    │       │   ├── history.php
    │       │   └── index.php
    │       ├── repairers/
    │       │   ├── admin.php
    │       │   └── form.php
    │       └── vehicles/
    │           ├── admin.php
    │           ├── form.php
    │           ├── index.php
    │           └── modal.php
    └── vendor/
        ├── autoload.php
        └── composer/
            ├── autoload_classmap.php
            ├── autoload_namespaces.php
            ├── autoload_psr4.php
            ├── autoload_real.php
            ├── autoload_static.php
            ├── ClassLoader.php
            ├── installed.json
            ├── installed.php
            ├── InstalledVersions.php
            └── LICENSE
```
# 🐘 Configuration PostgreSQL
environment:
- DB_HOST=db
- DB_PORT=5432
- DB_NAME=vehicles_db
- DB_USER=user
- DB_PASS=password

# pgAdmin
environment: 
- POSTGRES_USER: user
- POSTGRES_PASSWORD: password
- POSTGRES_DB: vehicles_db

# 🔨 Développement 
Pour le développement, les volumes Docker sont configurés pour refléter les changements en temps réel :

## 🚀 Commandes utiles

# Démarrer l'application:
```
docker compose up
```
# Démarrer l'application en arrière-plan
```
docker compose up -d
```
# Arrêter l'application
```
docker compose down
```
# Reconstruire les containers
```
docker compose up --build
```
# Voir les logs
```
docker compose logs
```
# Accéder au container PHP
```
docker compose exec php bash
```
# Accéder à la base de données
```
docker compose exec db psql -U postgres -d todolist
```
# Accéder à pgAdmin
```
http://localhost:5050
```
# Redémarrer pgAdmin si nécessaire
```
docker compose restart pgadmin
```
# Configuration initiale de pgAdmin Connectez-vous avec :

- Email: admin@local.com

- Mot de passe: admin

# Pour ajouter le serveur PostgreSQL :

- Clic droit sur "Servers" → "Register" → "Server"
- Dans l'onglet "General" : Name: projet_db (ou autre nom de votre choix)
- Dans l'onglet "Connection" : Host name/address:
- db Port: 5432
- Maintenance database: vehicles_db
- Username: user
- Password: password

# Vous pouvez maintenant :

- Visualiser la structure de la base de données
- Exécuter des requêtes SQL
- Gérer les tables et les données
- Exporter/Importer des données



