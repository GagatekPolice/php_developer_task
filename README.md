# Aplikacja rekrutacyjna do Shoper.pl

Aplikacja wyliczająca odległość od siedziby firmy Shoper.pl do dowolnego miejsca w oparciu
o Here Maps API.

## Autor
- [Gagatek Paweł](https://www.linkedin.com/in/pawel-gagatek/)

## Uruchomienie lokalnie w ramach Dockera 

Korzystamy z konfiguracji zawartej w pliku .env

W głównym katalogu należy zbudować obraz:

```bash
docker-compose up -d --build
```

Domyślna konfiguracja Dockera dla lokalnego środowiska wystawia następujące porty:
- Apache 8000:80 (endpointy)
- PhpMyAdmin 8080:80
- Baza danych  9906:3306

## Wyłączenie lokalnie aplikacji w ramach Dockera

Zatrzymanie aplikacji do ponownego uruchomienia
```bash
docker-compose down
```

Zatrzymanie aplikacji z usunięciem wszystkich pobranych obrazów
```bash
docker-compose down --rmi all
```

## Dokumentacja

Dokumentacja enpointów została opisana w ramach wyeksportowanego [pliku](shoperApi.postman_collection.json) aplikacji postman

|           Path           |  Method |                 Body fields                | Query Params |
|--------------------------|:-------:|:------------------------------------------:|-------------:|
| /headquarter/all         |   GET   |                    none                    |      none    |
| /headquarter/{productId} |   GET   |                    none                    |   productId  |
| /headquarter/            |   POST  |    city, street, latitude, longitude       |      none    |
| /headquarter/{productId} |  DELETE |                    none                    |      none    |
| /headquarter/{productId} |   PUT   |    ?city, ?street, ?latitude, ?longitude   |   productId  |
| /distance/{{productId}}  |   GET   | destinationLatitude, destinationtLongitude |   productId  |
## Narzędzia deweloperskie

W celu pobrania developerskich zależności należy odpalić composer

```bash
composer install
```

### Standard kodu - php-cs-fixer

Narzędzie służące do utrzymania standardu kodu opisanego w pliku .php-cs-fixer.dist.php

```bash
bin/php-cs-fix fix
```

### Testy jednostkowe - phpunit

```bash
bin/phpunit
```