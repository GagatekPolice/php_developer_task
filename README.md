# Aplikacja rekrutacyjna do Shoper.pl

Aplikacja wyliczająca odległość od siedziby firmy Shoper.pl do dowolnego miejsca w oparciu
o Here Maps API.

## Authors
- [Gagatek Paweł](https://www.linkedin.com/in/pawel-gagatek/)

## Uruchomienie lokalnie w ramach Dockera 

Korzystamy z konfiguracji zawartej w pliku .env

Pobieramy zalezności opisane w composer.json

```bash
composer install
```

W głównym katalogu należy zbudować obrazy:

```bash
docker-compose -f docker-compose.yml up -d --build
```

Domyślna konfiguracja Dockera dla lokalnego środowiska wystawia następujące porty:
- Apache 8080 (endpointy)

## Narzędzia deweloperskie

### Standard kodu - php-cs-fixer

Narzędzie słuące do utzymania standardu kodu opisane w pliku .php_cs_dist

```bash
bin/php-cs-fix fix
```