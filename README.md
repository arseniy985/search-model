# HyperX Store - E-Commerce Platform

Это полнофункциональный интернет-магазин, разработанный на Laravel с поддержкой поиска товаров, корзины, оформления заказов и платежей через Stripe.

## Требования к системе

- PHP ^8.1
- Composer 2.x
- Node.js & NPM
- MySQL 5.7+ / MariaDB 10.2+
- Stripe аккаунт для работы с платежами
- Настроенный веб-сервер (Apache/Nginx)
- Firebase аккаунт для дополнительных функций

## Инструкция по развертыванию

### 1. Клонирование репозитория

```bash
git clone https://github.com/your-username/searchmodel.git
cd searchmodel
```

### 2. Установка зависимостей

```bash
# Установка PHP зависимостей
composer install

# Установка JavaScript зависимостей
npm install

# Компиляция фронтенд-ресурсов
npm run build
```

### 3. Настройка окружения

Создайте файл `.env` на основе примера:

```bash
cp .env.example .env
```

Отредактируйте файл `.env` и настройте следующие параметры:

```
# Основные настройки приложения
APP_NAME="HyperX Store"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://ваш-домен.com

# Настройки базы данных
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hyperx_store
DB_USERNAME=root
DB_PASSWORD=your_secure_password

# Stripe API ключи для платежной системы
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# Настройки почты
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@hyperxstore.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 4. Настройка базы данных

Создайте новую базу данных, затем запустите миграции и заполните её начальными данными:

```bash
# Выполните миграции
php artisan migrate

# Заполните базу данных тестовыми данными
php artisan db:seed
```

### 5. Настройка хранилища

Создайте символическую ссылку для публичного доступа к файлам:

```bash
php artisan storage:link
```

### 6. Настройка Stripe WebHook

1. Войдите в панель управления Stripe (https://dashboard.stripe.com/)
2. Перейдите в раздел Developers > Webhooks
3. Добавьте новый Webhook Endpoint: `https://ваш-домен.com/webhook/stripe`
4. Выберите события для отслеживания:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
5. Скопируйте Webhook Secret и добавьте его в `.env` (STRIPE_WEBHOOK_SECRET)

### 7. Настройка Firebase

1. Войдите в консоль Firebase (https://console.firebase.google.com/)
2. Создайте новый проект или выберите существующий
3. Добавьте веб-приложение в проект Firebase:
   - Нажмите на иконку `</>` (Add Web App)
   - Укажите имя приложения
   - Отметьте "Also set up Firebase Hosting" если нужен хостинг
   - Нажмите "Register app"
4. Получите конфигурационные данные для проекта:
   - После регистрации приложения скопируйте объект конфигурации
   - Альтернативный способ: в консоли Firebase перейдите в Project settings > General > Your apps > Web apps > Config
5. Создайте сервисный аккаунт для серверного взаимодействия:
   - Перейдите в Project settings > Service accounts
   - Нажмите "Generate new private key"
   - Сохраните JSON-файл с учетными данными
6. Поместите файл с учетными данными в директорию `storage/app/firebase/`:
   ```bash
   mkdir -p storage/app/firebase/
   mv /path/to/your-firebase-credentials.json storage/app/firebase/firebase_credentials.json
   ```
7. Добавьте данные Firebase в файл `.env`:
   ```
   FIREBASE_CREDENTIALS=firebase_credentials.json
   FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
   ```

### 8. Запуск приложения

#### Для разработки:

```bash
# Запуск dev-сервера для фронтенда
npm run dev

# Запуск PHP сервера
php artisan serve
```

#### Для production:

Настройте веб-сервер (Apache/Nginx) для указания на директорию `public/` как корневую.

Пример конфигурации Nginx:

```nginx
server {
    listen 80;
    server_name ваш-домен.com;
    root /path/to/searchmodel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 9. Настройка очередей и крон-задач

Для обработки очередей (например, отправки email) настройте супервизор:

```bash
# Запуск обработчика очередей
php artisan queue:work
```

Добавьте задачу планировщика в crontab:

```
* * * * * cd /path/to/searchmodel && php artisan schedule:run >> /dev/null 2>&1
```

## Структура проекта

- `app/` - PHP классы приложения
- `resources/` - Шаблоны и фронтенд-ресурсы
- `routes/` - Маршруты приложения
- `public/` - Публично доступные файлы
- `database/` - Миграции и сидеры
- `config/` - Файлы конфигурации

## Основные функции

1. **Каталог товаров** - просмотр и поиск товаров
2. **Корзина покупок** - добавление/удаление товаров
3. **Оформление заказа** - процесс оформления заказа через Stripe
4. **Личный кабинет** - управление профилем и просмотр заказов
5. **Повторный заказ** - функция "Купить снова" для повторения заказа
6. **Печать заказов** - возможность распечатать детали заказа

## Учётные записи по умолчанию

### Администратор
- Email: admin@example.com
- Password: password

### Пользователь
- Email: user@example.com
- Password: password
