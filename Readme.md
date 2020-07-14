# Hotels  Restful Api
RESTful API backend For rating Hotels Feedback.

## Installation

```
docker-compose build 
docker-compose up -d
docker-compose exec php sh
docker-compose exec php composer install
docker-compose exec php bin/console doctrine:schema:create
docker-compose run php php bin/console assets:install
docker-compose exec php bin/console doctrine:fixtures:load

```

# How to use
1. Overtime Endpoint Ex:
http://localhost/api/v1/hotel-reviews?hotel_id=1&start_date=2018-01-01&end_date=2020-02-01
 

## Tools
* Symfony 5
* Docker
* PHP7.4
* Doctine
* Mysql



```

##  APi Collection
```
https://www.getpostman.com/collections/edde5b4aa7b913be2ac1
```

## Test
```
php bin/console doctrine:schema:create --env=test
bin/console doctrine:fixtures:load --env=test
docker-compose exec php  bin/phpunit

```

