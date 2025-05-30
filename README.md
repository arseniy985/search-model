# HyperX Store - E-Commerce Platform

Это полнофункциональный интернет-магазин, разработанный на Laravel с поддержкой поиска товаров, корзины, оформления заказов и платежей через Stripe.

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

#### Настройка туннелирования для локальной разработки через Stripe CLI

Для тестирования webhook-ов на локальном окружении можно настроить туннелирование через Stripe CLI:

1. Установите Stripe CLI:
   ```bash
   # macOS
   brew install stripe/stripe-cli/stripe
   
   # Windows (через scoop)
   scoop install stripe
   ЛИБО  ЖЕ С САЙТА https://docs.stripe.com/stripe-cli
   
   # Linux
   # Загрузите бинарный файл с https://github.com/stripe/stripe-cli/releases/latest
   ```

2. Авторизуйтесь в Stripe:
   ```bash
   stripe login
   ```

3. Запустите туннель для перенаправления webhook-ов на ваш локальный сервер:
   ```bash
   stripe listen --forward-to http://localhost:8000/webhook/stripe
   ```

4. Сохраните выданный webhook signing secret в `.env` файл:
   ```
   STRIPE_WEBHOOK_SECRET=whsec_значение_из_вывода_команды
   ```

5. В отдельном терминале вы можете тестировать события:
   ```bash
   stripe trigger payment_intent.succeeded
   ```

Теперь все webhook-события будут перенаправляться на ваш локальный сервер через защищенный туннель.

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

## Основные функции

1. **Каталог товаров** - просмотр и поиск товаров
2. **Корзина покупок** - добавление/удаление товаров
3. **Оформление заказа** - процесс оформления заказа через Stripe
4. **Личный кабинет** - управление профилем и просмотр заказов
5. **Повторный заказ** - функция "Купить снова" для повторения заказа
6. **Печать заказов** - возможность распечатать детали заказа
7. **AI-ассистент** - интеллектуальный чат-бот для поиска товаров на основе meta-llama/llama-4-scout

## AI-ассистент для поиска товаров

В приложении реализован AI-чатбот на основе модели Meta Llama 4 Scout через OpenRouter API. Он помогает пользователям находить товары на основе их запросов.

### Возможности AI-ассистента

- Понимание текстовых запросов пользователя на естественном языке
- Рекомендация товаров на основе описания потребностей
- Предоставление ссылок на подходящие товары в каталоге
- Рекомендация альтернатив, если запрашиваемые товары отсутствуют
- Отображение карточек с изображениями рекомендуемых товаров

### Настройка OpenRouter API

Для работы AI-ассистента необходимо получить API-ключ от сервиса OpenRouter:

1. Зарегистрируйтесь на [OpenRouter](https://openrouter.ai/)
2. Создайте API-ключ в разделе "API Keys"
3. Добавьте ключ в файл `.env`:
   ```
   OPENROUTER_API_KEY=your_api_key_here
   ```

## Учётные записи по умолчанию

### Администратор
- Email: admin@example.com
- Password: admin123

### Пользователь
- Email: user@example.com
- Password: password
