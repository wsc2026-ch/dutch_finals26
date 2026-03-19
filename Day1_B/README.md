# Day1 B

## Usage

### Database

Start a MySQL database, either by running the provided docker compose file or using your own database.

```bash
docker compose up -d
docker compose cp '.\mediafiles\2025-2026_F_OpdrachtB_Webdeveloper 2025 - 2026-database 2.sql' db:/tmp/db.sql
docker compose exec db bash -c "mysql -uroot -p db < /tmp/db.sql"
```

MySQL should have Port: 3306, User: root, Password: password, Database: db

### Webserver

Start a PHP webserver for with

```bash
php -S localhost:8000
```

Open http://localhost:8000/init.php to create the users table with and admin user

Open http://localhost:8000 to view website. Admin credentials are User: `administrator`: Password: `To3gangsc0de`
