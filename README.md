# e-Commerce Parser

jsonlines `.jsonl` file e-commerce order data parser using Laravel framework.

## Installation

After cloning this project run command below:

```
cd ecommerce-parser
cp .env.example .env
composer install
php artisan key:generate
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
