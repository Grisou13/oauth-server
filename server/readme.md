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

# Integrating with remote logging service

To integrate the oauth service with a remote login service, you will need to change `APP_ENV` from `local` to `production`.

That's all!

# How to use the app

The oauth server allows you to create oauth clients for your projects.

You will need to first create a project, then create an oauth client for that project.

Each project contains scopes that are accessible to the outside and to your clients.

Other people can ask for access to your projects, and you will need to validate them.
Same for you, if you want to access somebodies project, you will first need their approval for the project before requesting any scopes for that project.


# What needs to be done

- Create a complete auth flow in the auhtorization process (so users can change account when summoned on oauth authorization page)
- Fix bugs in frontend on disconnect
- Determine if tokens can be issued with specific scopes
- Get a better UI and UX with the frontend
- Create more secure code, as of right now, most of the stuff is hard coded
- Make a public list of avaiable scopes
