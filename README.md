# robo-test

Реализация тестового задания на вакансию php-разработчика в компании Робофинанс https://github.com/RoboFinance/test-assignments/blob/main/tasks/php_dev_assignment.md

## Установка

1. Создать папку проекта и зайти в нее.
2. Клонировать репозиторий командой `git clone https://github.com/Egen-ko/robo-test.git` .
3. установить зависимости командой `composer install`
4. Создать файл локальных настроек командой `composer dump-env prod` . Отредактировать в полученном файле `.env.local.php` параметры подключения к БД.
5. Создать БД проекта коммандой `php bin/console doctrine:database:create`.
6. Создать необходимые данные в БД командой `php bin/console doctrine:migrations:migrate`.
7. Настроить доменное имя и виртуальный хост на папку /public проекта.
8. Для запуска тестов использовать команду `php bin/phpunit tests/` (при первом запуске произойдет автоматическая установка phpunit).
