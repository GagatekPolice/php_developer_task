# Aplikacja rekrutacyjna do Shoper.pl

Aplikacja wyliczająca odległość od siedziby firmy Shoper.pl do dowolnego miejsca w oparciu
o Here Maps API.

## Autor
- [Gagatek Paweł](https://www.linkedin.com/in/pawel-gagatek/)

## Uruchomienie lokalnie w ramach Dockera 

Korzystamy z konfiguracji zawartej w pliku .env

Pobieramy zależności opisane w composer.json

```bash
composer install
```

W głównym katalogu należy zbudować obraz:

```bash
docker-compose -f docker-compose.yml up -d --build
```

Domyślna konfiguracja Dockera dla lokalnego środowiska wystawia następujące porty:
- Apache 8080 (endpointy)

## Narzędzia deweloperskie

### Standard kodu - php-cs-fixer

Narzędzie słżuące do utrzymania standardu kodu opisanego w pliku .php-cs-fixer.dist.php

```bash
bin/php-cs-fix fix
```