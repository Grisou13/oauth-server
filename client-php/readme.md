# Requirements

- php
- composer

# Install
```
composer install
cp .env.example .env
```

# Examples

There is a complete example on how to use the client for an auth token in `example/index.php`.

# Documentation

For any info on available methods for the client, please refer to the [](http://oauth2-client.thephpleague.com/usage/)

## Running the example

```
cd example

cp .env.example .env

# Update .env with your actual client id and client secrets
nano .env
```

# TODO

- __Change $domain for oauth service in `src/Client.php` to the actual uri of the oauth service__
