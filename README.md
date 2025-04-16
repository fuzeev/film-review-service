# Сервис для оставления отзывов к фильмам

Позволяет просматривать список фильмов, добавлять новые фильмы и оставлять отзывы. Реализована регистрация и авторизация с использованием JWT.

---

## 📂 Архитектура

Проект построен по принципам **Clean Architecture** на фреймворке Symfony:

```
src/
├── Domain/         # Доменный слой: сущности, интерфейсы репозиториев, бизнес-логика
├── Application/    # Слой приложения: сценарии (UseCases), DTO для входящих и исходящих данных
├── Infrastructure/ # Инфраструктурный слой: реализации репозиториев, внешних сервисов и т. д.
└── Presentation/   # Слой представления: контроллеры, маршрутизация, валидация запросов
```

**Flow обработки запроса:**
1. Symfony-рoутинг направляет HTTP-запрос в метод контроллера.
2. Контроллер валидирует входные параметры и формирует DTO.
3. Вызывается соответствующий UseCase из слоя Application.
4. UseCase оперирует доменными сущностями и абстракциями (репозитории, сервисы), внедрёнными через DI.
5. UseCase возвращает результат, который контроллер трансформирует в HTTP-ответ.

---

## ⚙️ Развертывание

1. Создайте файлы окружения:
    - `.env.local`
    - `.env.test.local`

### Пример `.env.local`
```dotenv
POSTGRES_USER=app
POSTGRES_PASSWORD=123456
POSTGRES_DB=app
POSTGRES_EXTERNAL_PORT=5438
DATABASE_URL="postgresql://app:123456@127.0.0.1:5438/app?serverVersion=17&charset=utf8"
JWT_PASSPHRASE=test
```

### Пример `.env.test.local`
```dotenv
DATABASE_URL="postgresql://app:123456@127.0.0.1:5438/app?serverVersion=17&charset=utf8"
```

2. Установите зависимости:
   ```bash
   symfony composer install
   ```

3. Сгенерируйте ключи для JWT:
```bash
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

4. Запустите контейнеры и сервер:
```bash
make up
symfony server:start
```

5. Для создания тестовой базы данных:
```bash
symfony console --env=test doctrine:database:create
symfony console --env=test doctrine:schema:create
```

---

## 🛠 Доступные команды (Makefile)

| Команда           | Описание                                         |
|-------------------|--------------------------------------------------|
| `make ecs`        | Запустить проверку кода через ECS                |
| `make stan`       | Анализ кода с PHPStan                            |
| `make ecs-fix`    | Автофикс стиля ECS                               |
| `make psalm`      | Проверка с помощью Psalm                         |
| `make psalm-info` | Psalm с подробной информацией                    |
| `make fixtures`   | Загрузка фикстур в тестовую БД                   |
| `make test`       | Запуск тестов PHPUnit                            |
| `make full-check` | Последовательно: `ecs`, `stan`, `psalm` и `test` |
| `make up`         | Сборка и запуск контейнеров                      |
| `make down`       | Остановка контейнеров                            |
| `make restart`    | Перезапуск: `down` + `up`                        |
| `make migration`  | Генерация миграции Doctrine                      |

---

## 📝 Прочее

- Код проверяется через ECS + PHPStan + Psalm.
- Все внешние зависимости устанавливаются через Composer.

---
