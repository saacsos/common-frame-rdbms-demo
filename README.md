# Common Frame RDBMS Demo

## Development
```bash
cp .env.example .env
composer install
php artisan db:wipe
php artisan migrate --seed
php artisan serve
```

## Demo API
* แสดงข้อมูลสถานประกอบการทั้งหมด
  * `GET` `/api/establishments`
* เพิ่มหรือแก้ไขข้อมูลสถานประกอบการ
  * `POST` `/api/establishments`
