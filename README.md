## On clone
```
docker compose build
```

## On subsequent runs
```
docker compose up -d
docker compose exec app bash

npm run dev

php artisan queue:listen

php artisan reverb:start --debug
```