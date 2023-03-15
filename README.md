## Steps
1. Create database 
    name : parking_slot_db
2. Add db credentials to the config file with your db credentials
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3308
    DB_DATABASE=parking_slot_db
    DB_USERNAME=root
    DB_PASSWORD=
3. Download the project : master branch (it have vendor folders also so not need to do composer install)
4. Migrate DB 
     php artisan migrate
5. uploaded postman collection of api in the root folder
