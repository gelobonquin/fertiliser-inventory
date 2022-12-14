
# Figured Inventory

A simple  Laravel application that helps a user understand how much quantity of a product is available for use.

## Built With
- Laravel 9
- PHP 8.0 above
- Vue 3 and Axios
- Tailwind CSS

I'm consider myself a [YAGNI](https://jasonmccreary.me/articles/practicing-yagni//) practitioner to make the application simpler, easy to understand and avoid over-engineering.

## Prerequisites
- [Composer](https://getcomposer.org/) 
- [NPM/Node](https://nodejs.org/en/) - My local machine node version is v14.17.1 and npm version is 6.14.13, but also compatible with the latest one 
- Nginx or [Laragon](https://laragon.org/)
- [Valet](https://laravel.com/docs/9.x/valet)

## Installation
    # Clone the repository
    git clone https://github.com/gelobonquin/fertiliser-inventory.git

    # Run composer install
    composer install

    # Create environment variables
    cp .env.example .env

    # Update DB connection in .env and create database, make sure you are runnig with the same port
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=fertiliser_inventory
    DB_USERNAME=root
    DB_PASSWORD=

    # Generate key
    php artisan key:generate

    # Migrate tables and seeder
    php artisan migrate:fresh --seed

    # Run npm and watch
    npm install
    npm run watch

    # Clear cache if needed
    php artisan config:clear
    php artisan cache:clear

## Usage
Access the application in http://fertiliser-inventory.test/



## Directories
- `storage/fertiliser_inventory.csv` - csv data source
- `resources/js/app.js` - Vue instance
- `resources/js/components/App.vue` - Vue file
- `resources/js/Error.js` - Error handling
- `database/migrations` - Contains database migrations
- `database/seeders` - Contains database seeders
- `database/factories` - Contain factories
- `routes/api.php` - Routes
- `app/Http/Controllers/InventoryController.php` - Controller
- `app/Http/Requests/InventoryRequest.php` - Request
- `app/Actions/CreateInventoryApplication.php` - Handle creation of inventory application
- `app/Http/Services/InventoryService.php` - Inventory Service
- `tests` - Contains feature and unit tests


## Testing
    php artisan test