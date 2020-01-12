# e-Commerce Parser

jsonlines `.jsonl` file e-commerce order data parser using Laravel framework.

here's the [example file](https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl)

## Installation

After cloning this project run command below:

```
cd ecommerce-parser
cp .env.example .env
composer install
php artisan key:generate
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
