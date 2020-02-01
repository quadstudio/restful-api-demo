## Новостной портал

RESTful API демо-приложение

> **Примечание: пример работы развернутого приложение можно посмотреть на**  [Heroku](http://restful-api-news-portal.herokuapp.com/).

Рекомендуется устанавливать приложение через 
[Composer](http://getcomposer.org).

```bash
# Установить Composer
curl -sS https://getcomposer.org/installer | php
```

Далее, запустите команду Composer для установки dev версии RESTful API демо-приложения:

```bash
composer create-project quadstudio/restful-api-demo:dev-master
```

Чтобы создать символическую ссылку, вы можете использовать следующую команду:

```bash
php artisan storage:link
```

Создайте базу данных на сервере и настройте переменные окружения в .env файле

```bash
DB_DATABASE=<база данных>
DB_USERNAME=<логин>
DB_PASSWORD=<пароль>
```

Создайте таблицы, выполнив миграцию:

```bash
php artisan migrate
```

Создайте ключи шифрования, необходимые для создания токенов безопасного доступа:

```bash
php artisan passport:install
```

Запустите тестовый web-сервер:

```bash
php artisan serve
```