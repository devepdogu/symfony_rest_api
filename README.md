# Symfony REST API | Docker
 Symfony rest api with docker and jwt
 
 
 
# Preparation
- Extract files
- Compose docker
- Compose symfony
  - Create new symfony project with 'codebase' name
  - Go to created file with 'codebase' on terminal
  - Create jwt config file
  - Install require symfony packages 
  - Generate jwt key
- Move files
- Change giving files
- Migration
- Done 

# The final folder structure without symfony
![Last Tree](https://dogukandemir.net/img/symfony.png)
## Compose docker
 Find and extract the folder named 'docker'  in downloaded main folder
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
# Move files
 After symfony installation find the  folder named 'symfony'  in downloaded main folder 
 
 

 