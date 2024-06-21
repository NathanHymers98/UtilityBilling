# UtilityBilling

A small project that calculates energy bills for a household based on a given rate.

1) Install required libraries via Composer:

```
composer install
```

2) Create Database and load data fixtures:

```
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

3) Start local server:

```
symfony server:start
```

Then head to http://127.0.0.1:8000/input-rates