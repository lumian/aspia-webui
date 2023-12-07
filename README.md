# Aspia WebUI
WebUI для управления сервером обновлений программы для удаленного управления ПК Aspia (https://aspia.org)

## Возможности:
* Управление компонентами программы (host, client, console)
* Управление и загрузка инсталляторов (для удобного создания обновлений). Поддерживаются различные архитектуры и операционные системы.
* Управление обновлениями (для каждой исходной версии вы можете указать своё обновление)
* Сбор и отображение статистики обращений к серверу
* Инсталлятор webgui для простой настройки

## Системные требования:
* PHP: 7.x, 8.0, 8.1
* Директива "short_open_tag" в php.ini должна быть включена.

## Установка:
* Скопируйте содержимое директории "upload/" в корень вашего сайта;
* Перейдите на ваш сайт, откроется инсталлятор;
* Заполните необходимые поля в инсталляторе и приступайте к работе.

## Настройка Aspia WebUI:
* Перейдите в web-интерфес Aspia WebUI,
* Настройте компоненты Aspia,
* Настройте инсталляторы Aspia,
* Настройте обновления Aspia,
* Приступайте к настройке Aspia Host, Client, Console.

## Настройка Aspia на сервер обновлений
* Aspia Host:
  * Откройте Aspia Host и перейдите в меню "Aspia -> Параметры"
  * На вкладке "Основные", в разделе "Сервер обновлений" установите галочку "Использовать свой сервер обновлений" и укажите URL вашего сайта
  * Сохраните настройки нажатием на "Ок".
* Aspia Client
  * Откройте Aspia Client и перейдите в меню "Помощь -> Параметры обновления"
  * Установите галочку "Проверять обновления при запуске" и "Использовать свой сервер обновлений" и укажите URL вашего сайта
  * Сохраните настройки нажатием на "Ок".
* Aspia Console
  * Откройте Aspia Console и перейдите в меню "Помощь -> Параметры обновления"
  * Установите галочку "Проверять обновления при запуске" и "Использовать свой сервер обновлений" и укажите URL вашего сайта
  * Сохраните настройки нажатием на "Ок".

## Полезные ссылки
* Официальный сайт автора Aspia: https://aspia.org
* Официальный репозиторий Aspia: https://github.com/dchapyshev/aspia
* Помощь в настройке Aspia: https://it-35.ru/tags/aspia