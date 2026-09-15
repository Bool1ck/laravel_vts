# Облік нових точок приєднань

Веб-сервіс для автоматизації обліку, моніторингу та верифікації нових точок приєднань до електричних мереж.
Система дозволяє координувати процес підключення від подачі заявки до фінального запуску.

## 🚀 Стек технологій
* **Backend:** Laravel 13 / PHP 8.3
* **Frontend:** Blade, Vite, Tailwind CSS
* **База даних:** MySQL
* **Середовище розробки:** Laravel Sail (Docker)
* **Тестування:** Pest PHP

## ✨ Основний функціонал
* 📍 **Облік точок:** Створення, редагування та фільтрація точок приєднань за статусами.
* 📊 **Моніторинг етапів:** Відстеження прогресу підключення (заявка, ТУ, проектування, замовлення матеріалів, монтаж).
* 🔒 **Рольова модель:** Розмежування прав доступу для інженерів, головних інженерів та адміністраторів.
* 🛠️ **Автоматизоване тестування:** Високе покриття коду unit та feature тестами для стабільності системи.

## 🛠️ Запуск проекту (Локально через Docker)

Завдяки використанню **Laravel Sail**, вам не потрібно встановлювати PHP, MySQL або Node.js локально на комп'ютер.
Достатньо мати встановлений **Docker**.


### Кроки інсталяції

1. Клонуйте репозиторій:
```bash
git clone https://github.com/Bool1ck/laravel_vts
cd laravel_vts
```

2. Встановіть PHP-залежності за допомогою тимчасового Docker-контейнера:
```bash
docker run --rm \
    -u "(id -u):(id -g)" \
    -v "\$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-cli:latest \
    composer install --ignore-platform-reqs
```

3. Налаштуйте конфігурацію (створіть файл `.env` з `.env.example`):
```bash
cp .env.example .env
```

4. Запустіть Docker-контейнери (Laravel Sail):
```bash
./vendor/bin/sail up -d
```

5. Згенеруйте ключ додатка:
```bash
./vendor/bin/sail artisan key:generate
```

6. Запустіть міграції та наповніть БД тестовими даними (seeders):
```bash
./vendor/bin/sail artisan migrate --seed
```

7. Встановіть та запустіть фронтенд (Vite):
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

Тепер проект доступний за адресою: `http://localhost`.

## 🧪 Тестування
Проект покритий тестами за допомогою сучасного фреймворку **Pest**. Для запуску тестів у Docker-середовищі виконайте:
```bash
./vendor/bin/sail artisan test
# або напряму через Pest
./vendor/bin/sail pest
```

## 📈 Плани по доработке (Roadmap)
* [ ] Генерація звітів у форматі PDF/Excel (через Laravel Excel).

## 👥 Контакти
* **Автор:** [Андрій]
* **Email:** [boolick@gmail.com]
* **LinkedIn:** [www.linkedin.com/in/andriy-balabas-a63b7922a]
* **GitHub:** [Bool1ck](https://github.com/Bool1ck)
