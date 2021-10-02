# Symfony 5.3, Doctrine, PHPUnit, PHPSpec, PHP 8, Nginx, Mysql, Docker

## Getting started

```bash
cd docker/ && docker-compose up --build
```

### Symfony console from host machine
```bash
docker exec -it php-fpm bin/console
```

### Running tests
```bash
docker exec -it php-fpm bin/phpunit
```

### Running it from browser
```
http://localhost/facts?q=%7B%22expression%22%3A%20%7B%22fn%22%3A%20%22%2B%22%2C%20%22a%22%3A%20%22sales%22%2C%20%22b%22%3A%202%7D%2C%22security%22%3A%20%22ABC%22%7D
```

### Running it from cli
```bash
curl -X POST -H "Content-Type: application/json" -d '{"expression": {"fn": "+", "a": "sales", "b": 2},"security": "ABC"}' http://localhost/facts
```
