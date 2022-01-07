# Symfony REST API | Docker
 Symfony rest api with docker and jwt
 
 
 
# Preparation
- Compose docker
- Compose symfony
  - Create new symfony project with 'codebase' name
  - Go to created file with 'codebase' on terminal
  - Create jwt config file
  - Install require symfony packages 
  - Generate jwt key
- Move files
- Database
- Migration
- Done 

# The final folder structure without symfony
![Last Tree](https://dogukandemir.net/img/symfony.png)
## Compose docker
 Open in terminal to be established location and execute the code below 
 ```bash
docker-compose up -d
```
 **This process may take a long time and output must be like this** 
 - Container symfony_api-db                           Started                                                                                                                     
 - Container symfony_api-db-admin                     Started                                                                                                                       
 - Container symfony_api-server                       Started  
 Check  http://localhost:8101/ This page give 404 error
 
## Compose symfony
 After docker installation execute the code below for symfony
  ```bash
composer create-project symfony/skeleton codebase
```
 Check  http://localhost:8101/ This page load symfony default page
 
 Execute code below
```bash
  cd .\codebase\
```
 We need the jwt config file
```bash
  mkdir config/jwt
```
 Than we need the symfony bundle 
 ```bash
 composer require symfony/orm-pack symfony/maker-bundle sensio/framework-extra-bundle lexik/jwt-authentication-bundle
```
Finally we need the jwt key
```bash
 php bin/console lexik:jwt:generate-keypair
```
## Move files
 After symfony installation find and move named 'codebase' the folder named 'symfony files' in downloaded main folder
 Change it all files.

## Database
  After change the the folder named 'codebase' we need the database. Find the codebase/.env file. Inside the .env file find the start DATABASE_URL= line and change this
 ```.env
  DATABASE_URL="mysql://api_user:api_password@localhost:33016/symfony_api?serverVersion=mariadb-10.4.11&charset=utf8"
 ```
## Migration
 Last process created table into the database. Execute the code below. If raise error ignore it
 ```bash
 php bin/console make:migration
 ```
 ```bash
php bin/console doctrine:migrations:migrate
 ```
## Done
 Open postman and import the file named 'symfony_api.postman_collection.json' 
 **All requests need the Bearer Token (exclude register and login request)**
 
# Example Postman Request
 - Register
   - ![Register](https://dogukandemir.net/img/postman_register.png)
   
 - Login
   - ![Login](https://dogukandemir.net/img/postman_login.png)
 
 - With Token Add
   - ![Login](https://dogukandemir.net/img/postman_product.png)
   
 - Without Token Add
   - ![Login](https://dogukandemir.net/img/postman_without.png)
 
 
