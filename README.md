## Requirements

* [Docker](https://docs.docker.com/engine/installation/)
* [docker-compose](https://docs.docker.com/compose/install/)

Installation
------------

```shell script
cd docker
docker-compose up -d --build
```

Load fixtures
-------------
```shell script
docker-compose exec php-fpm php bin/console doctrine:fixtures:load
```
How run script
-------------

```shell script
docker-compose exec php-fpm php bin/console app:get-sad-patients
```
or
```shell script
docker-compose exec php-fpm php bin/console app:get-sad-patients 20 --timeout=1
```
```shell script
Description:
  Calculate % of patients who did not receive a prescription on time

Usage:
  app:get-sad-patients [options] [--] [<execution_time>]

Arguments:
  execution_time           Custom execution time in seconds (default 300)

Options:
  -t, --timeout[=TIMEOUT]  Custom timeout in seconds (default 1)
  -h, --help               Display this help message
```