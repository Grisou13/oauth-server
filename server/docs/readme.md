# Introduction

Oauth2 server with alot more than just oauth tokens.

# Requirements

- PHP 7.0
    - Composer
- Mysql

# Install

```
composer install
php artisan migrate
php artisan passport:install
```

# Usage

```
php artisan serve
```

Now visit `htpp://localhost:8000/`

# Tests

Run tests with:
`phpunit`

All tests are present in `tests/` folder.

# Available clients

- JS https://github.com/mulesoft/js-client-oauth2
- PHP https://github.com/thephpleague/oauth2-client
- Ruby https://github.com/oauth-xx/oauth2