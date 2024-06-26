docker-compose exec vue bash

1  vue create .  (if first create yagni)
 
2  npm run serve

3  exit

docker-compose exec php bash

1  composer install

2  add .env 

3  php artisan key:generate && php artisan migrate

4  exit

[Laravel](http://127.0.0.1:80)
[Vue](http://127.0.0.1:8080)
