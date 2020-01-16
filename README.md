# e-Commerce Parser

jsonlines `.jsonl` file e-commerce order data parser using Laravel framework.

here's the [example file](https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl)

## Installation

After cloning this project run commands below:

```
cd ecommerce-parser
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
```

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
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

or you can to use your own smtp configuration

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
```

### Database Configuration

Open `.env` file with your favorite editor, and edit these lines

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

## Parsing Input

Run command help for detail

```
php artisan parser:run --help
```

### Parsing Example

```
php artisan parser:run https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl
```

### Parsing Example with specific output format

```
php artisan parser:run https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl --format=jsonl
```

### Parsing Example with send to email the output data

```
php artisan parser:run https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl --email=someone@example.org
```

### Parsing Example with import to DB

```
php artisan parser:run https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl --db
```

## API Documentation

To turn on API server run command below

```
php artisan serve
```

To see API Documentation you can see on this link [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

## Coding Standard

This project using PSR-2 coding standard.

### Code Linting

Run command below for code linting following coding standard.

```
vendor/bin/phpcs
```

### Code Standard Fixer

Run command below for fixing your coding standard automatically.

```
vendor/bin/phpcbf
```

if some files can't go auto fix, you have to fix manually.

## Creator

- [Faizal Dwi Nugraha](mailto:f4154lt@yahoo.co.id)
