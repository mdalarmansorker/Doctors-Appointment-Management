# Doctors Appointment Management

### Packages used for this project
- Ant Design Vue
- Axios (for making HTTP requests)

### Installation Command
```
composer install
npm install
cp .env.example .env
php artisan key:generate
```
Upgrade the .env file with database name, username and password then run this.
```
php artisan migrate
```
After creating the migration we need to run the seeder to create an admin user
```
php artisan migrate:fresh --seed
```

