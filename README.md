# Тестовое задание на реализацию создания/редактирования документа.

Создать модель документа. С полями title, body, author_id;

Создать форму создания/редактирования документа авторизованному пользователю;

> не обязательно реализовать механизмы регистрации пользователей достаточно использовать модель пользователя по умолчанию https://github.com/yiisoft/yii2-app-basic/blob/master/models/User.php

Создать механизм который позволит менять статусы этого документа. По умолчанию при создании документ будет в «Черновик», затем документ можно перевести только  в статус «На проверке» и только после этого в статус «Проверен»

При назначении статуса «Проверен» реализовать механизм отправки email уведомления c текстом:
```
Уважаемый ФИО,
Ваш документ {title} получил статус «Проверен»
```
Где {title} - это название документа.

> Уведомление не нужно отсылать на реальную почту. Можно использовать фейковый адрес.
Например test@test.test и настройку рассылки использовать по умолчанию для хранения письма в локальные файлы.

*Подсказка:* для этого в настройка конфигурации SwiftMailer надо установить параметр:  `'useFileTransport' => true`