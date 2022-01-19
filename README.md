# Common Frame RDBMS Demo

## Development
```bash
cp .env.example .env
php artisan db:wipe
php artisan migrate --seed
php artisan serve
```

## Demo API
* `GET` `/api/establishments`
* `POST` `/api/establishments`
