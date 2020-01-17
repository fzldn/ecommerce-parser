# e-Commerce Parser

jsonlines `.jsonl` file e-commerce order data parser using Laravel framework.

here's the [example file](https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl)

## Installation

You need to install [Docker](https://www.docker.com/get-docker) on your local machine before running this app. after docker installed on your local machine, run command below

```
cd ecommerce-parser
docker-compose up -d
```

after Docker container running, run these commands below

```
docker-compose exec app cp .env.example .env
docker-compose exec app composer install
docker-compose exec app php artisan migrate
```

voila, your app ready to use...

To see API Documentation you can see on this link [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation), before you playing around with API Documentation you have to run parser first with `--db` option to import parsed order data into database. see [How to running parser](#running-parser) for the detail.

Then to see Database Admin (phpMyAdmin) you can see on this link [http://localhost:8080](http://localhost:8080)

### Email Configuration

Open `.env` file with your favorite editor, and edit these lines

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@example.org
MAIL_FROM_NAME="${APP_NAME}"

MAILGUN_DOMAIN=
MAILGUN_SECRET=
```

mailgun driver example

```
MAIL_DRIVER=mailgun
...
MAILGUN_DOMAIN=your-mailgun-domain
MAILGUN_SECRET=your-mailgun-key
```

smtp driver with [mailcatcher](https://mailcatcher.me) example

```
MAIL_DRIVER=smtp
MAIL_HOST=host.docker.internal
MAIL_PORT=1025
```

or you can use your own smtp configuration

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
```

## Running Parser

Run command help for detail

```
docker-compose exec app php artisan parser:run --help
```

### Parsing Example

```
docker-compose exec app php artisan parser:run https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl
```

### Parsing Example with specific output format

```
docker-compose exec app php artisan parser:run --format=jsonl https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl
```

### Parsing Example with send to email the output data

```
docker-compose exec app php artisan parser:run --email=someone@example.org https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl
```

### Parsing Example with import to DB

```
docker-compose exec app php artisan parser:run --db https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl
```

## Coding Standard

This project using PSR-2 coding standard.

### Code Linting

Run command below for code linting following coding standard.

```
docker-compose exec app vendor/bin/phpcs
```

### Code Standard Fixer

Run command below for fixing your coding standard automatically.

```
docker-compose exec app vendor/bin/phpcbf
```

if some files can't go auto fix, you have to fix manually.

## Creator

- [Faizal Dwi Nugraha](mailto:f4154lt@yahoo.co.id)
