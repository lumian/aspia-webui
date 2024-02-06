<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Базовая информация о сайте
$config['aspia']['site_title']					= 'Aspia WebUI';
$config['aspia']['link_author']					= 'https://github.com/lumian/aspia-webui';

// Данные для авторизации
$config['aspia']['credentials']['login']		= 'admin';
$config['aspia']['credentials']['password']		= 'admin';

// Директория для загрузки инсталляторов (локальный путь в системе) с завершающим '/'
$config['aspia']['storage_path']				= './storage/';
// URL адрес, по которому доступна директория по протоколу HTTPS с завершающим '/'
$config['aspia']['storage_url']					= 'https://'.$_SERVER['HTTP_HOST'].'/storage/';

// Список поддерживаемых инсталляторов
$config['aspia']['support_os']					= array('windows'); // Доступные ОС, в формате массива
$config['aspia']['support_arch']				= array('x86', 'x86_64'); // Доступные архитектуры, в формате массива
$config['aspia']['support_extensions']			= 'msi|deb|dmg'; // Доступные расширения файлов инсталляторов

// Флаг установленной системы (TRUE - установлена / FALSE - не установлена)
$config['aspia']['system_installed']			= FALSE;
