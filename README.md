# OpenClassrooms-Projet7

[Créez un web service exposant une API](https://openclassrooms.com/projects/creez-un-web-service-exposant-une-api)

## RESTful API with Symfony 4

The API is written in PHP with the Symfony 4 framework.

The API will follow these rules:
- The API only returns JSON responses
- All API routes require authentication
- Authentication is handled via JSON web tokens(JWT)

## Getting started
The application development is based on:
- [Symfony](https://symfony.com/doc/current/index.html) v4.0.9

The following bundles/libraries are used:

### Requirements
- PHP 7.1.3 or higher
- [Composer](https://getcomposer.org/)

### Installation
#### 1. Install the project:
Copy the repository and execute:
- `composer install`

#### 2. Create Database
Creating Database:
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:database:create --env=test` for testing

Creating Database Tables/Schema:
- `php bin/console doctrine:migrations:migrate`

#### 3. Load fixtures
Loading a set of fixtures.:
- `php bin/console doctrine:fixtures:load`

#### 4. Functional Testing
Testing with Behat:
- `vendor/bin/behat`

### Author
- Daniel Thébault