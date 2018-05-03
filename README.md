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

#### 2. Set application parameters

#### 3. Load fixtures
Execute:
- `php bin/console doctrine:fixtures:load` to load a set of fixtures.

### Author
- Daniel Thébault