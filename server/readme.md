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

# What needs to be done

- Create a complete auth flow in the auhtorization process (so users can change account when summoned on oauth authorization page)
- Fix bugs in frontend on disconnect
- Determine if tokens can be issued with specific scopes
- Get a better UI and UX with the frontend
- Create more secure code, as of right now, most of the stuff is hard coded
