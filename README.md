#Deploy Documentation

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





# API Documentation

## Endpoints

### 1. Get Accounts

- **URL**: `/api/v2/zoho/get_accounts`
- **Method**: `GET`
- **Description**: Получить список всех контрагентов.

### 2. Get Stages

- **URL**: `/api/v2/zoho/get_stages`
- **Method**: `GET`
- **Description**: Получить список всех стадий сделки.

### 3. Store Account

- **URL**: `/api/v2/zoho/store_account`
- **Method**: `POST`
- **Description**: Создать нового контрагента.
- **Parameters**:
  - `Account_Name` (string, required): Имя контрагента.
  - `Website` (string, required): Вебсайт контрагента.
  - `Phone` (string, required): Телефон контрагента.
  - `Billing_City` (string, required): Город контрагента.

### 4. Store Deal

- **URL**: `/api/v2/zoho/store_deal`
- **Method**: `POST`
- **Description**: Создать новую сделку для контрагента.
- **Parameters**:
  - `Deal_Name` (string, required): Имя сделки.
  - `Stage` (string, required): Стадия сделки.
  - `Amount` (float, required): Сумма сделки.
  - `Closing_Date` (string, required): Дата оканчания периода сделки.
  - `Account_Name.name` (string, required): Имя контрагента.
  - `Account_Name.id` (string, required): Айди контрагента.

### 5. Initialize Token

- **URL**: `/api/v2/zoho/init_token`
- **Method**: `POST`
- **Description**: Добавляет токен в систему. 
             
             (решает проблемму Zoho Token is Missing)
- **Parameters**:
  - `access_token` (string, required): Token доступа.
  - `refresh_token` (string, required): Token обновления.
  
  
### 6. Check Token

- **URL**: `/api/v2/zoho/check_token`
- **Method**: `GET`
- **Description**: Проверяет добавлен ли токен в систему.


### 7. Delete Token

- **URL**: `/api/v2/zoho/delete_token`
- **Method**: `POST`
- **Description**: Удаляет токен из системы.


##p.s. чтобы получить api токены нужно:

 1 зайти в коноль и выбрать подключение  https://api-console.zoho.eu/
 
 2 параметры подключения вставить в браузер (учтите что урл должен совпадать с урлом в консоли)
 Больше информации об этом  https://www.zoho.com/crm/developer/docs/api/v2/access-refresh.html
 ````
 https://accounts.zoho.eu/oauth/v2/auth?response_type=code&client_id=<client_id>&scope=ZohoCRM.modules.ALL&redirect_uri=<redirect_uri>&access_type=offline&prompt=consent 
 ````
 3 теперь нас переадресует в наше приложение. обратите внимание что в урл в браузере
 появился параметр code. скопируйте его
 
 4 теперь когда есть все параметры. методом пост отправьте запрос. ответом будут токены api
 
  ````
  https://accounts.zoho.eu/oauth/v2/token?client_id=<client_id>&client_secret=<client_secret>&grant_type=authorization_code&code=<authorization_code>&redirect_uri=<redirect_uri>
   ````
   
##p.s. также добавьте свои переменные окружения в .env
````
ZOHO_CLIENT_ID=

ZOHO_CLIENT_SECRET=

ZOHO_REDIRECT_URI=
````