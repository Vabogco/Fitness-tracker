# Fata app

Symfony based small application to track fitness activity by user.

## Software

1. PHP 8.2
2. PostgreSQL 16.2
3. Symfony 5.10

# Installation

Docker should be installed before cloning project.
Install docker-compose command.

Clone current repository.
Build docker images using compose file from project directory:

``` bash
docker-compose -f compose.yaml up -d
```

Go inside your docker application container.

``` bash
docker-compose exec php sh
```

Make migration to DB from the first start

``` bash
php bin/console doctrine:migrations:migrate
```

Now everything is ready to run the tracking application.
Follow next steps from this documentaion.

## Usage

Create user:

``` bash
curl -X POST http://localhost:7777/api/v1/user -d "{\"name\":\"Mike\"}"
```

Create activity:

``` bash
curl -X POST http://localhost:7777/api/v1/activity -d "{\"user_id\":1,\"activity_type\":\"SWIMMING\",\"activity_date\":\"2024-08-24 26:15:23\",\"name\":\"HikingWalk\",\"distance\":\"18\",\"distance_unit\":\"MI\",\"elapsed_time\":\"1000\"}"
```

Get activities:

``` bash
curl http://localhost:7777/api/v1/activities
```

Get activities by type:

``` bash
curl http://localhost:7777/api/v1/activities?activity_type=HIKING
```

Get distance by type:

``` bash
curl http://localhost:7777/api/v1/activities/distance?activity_type=HIKING
```

Get elapsed time by type:

``` bash
curl http://localhost:7777/api/v1/activities/elapsed_time?activity_type=HIKING
```

## Run unit tests

### Inside the container

```bash
php bin/phpunit tests/unit
```
