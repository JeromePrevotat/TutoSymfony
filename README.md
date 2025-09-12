# Tutorial Symfony Graphikart

## Setup

composer create-project symfony/skeleton:"7.3.\*" [project-name]
composer require webapp
php -S localhost:8000 -t public

## Controller

php bin/console make:controller [controller-name]

### Routing

config/routes.yml
controller{path, controllerName::method}
Attribut #[Route(path, name)]

list routes:
php bin/console debug:router

## Twig

concatenation operator inside var : ~
pipe angular

## ORM

config : .env
php bin/console make:entity
Create migration
php bin/console make:migration
Execute migration
php bin/console doctrine:migrations:migrate

filtre nl2br : keeps newline

entity manager -> persist
flush -> update db
em -> get reposeitory from class

## Forms

php bin/console make:form
form_start - form_row - form_end
form->handleRequest
app.flashes
Restrict to DELETE mehtod workaround : <input type="hidden" name="_method" value="DELETE">
/!\ Dont forget edit framework config with http_method_override: true /!\
FormsType and associated Validators : https://symfony.com/doc/current/reference/forms/types.html

FormEvents : https://symfony.com/doc/current/form/events.html

## Data Validation

Forms : constraints array
Entity : import Constraint as Assert
UniqueEntity : array == &&
Custom Validator : php bin/console make:validator
validator groups

## Services

php bin/console debug:autowiring [name] | grep [name]

## TP Contact

ContactFormDTO
/contact
nom email message
mailpit into bin
messenger.yml queue from async to sync
.env config

## TP Category

Entity name slug
(dessert, plat, entrée)
navigation
tableau
form
Entity -> Controller -> Route -> Forms
