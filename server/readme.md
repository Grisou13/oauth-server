# Requirements

- php
  - pdo
  - mbstring
  - xml
  - json
- composer
- mysql

# Install
```
composer install
chmod 755 -R storage/
php artisan generate:key
cp .env.example .env
# edit your database settings in .env
php artisan passport:install
```
