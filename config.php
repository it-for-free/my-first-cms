<?php

/**
 * Конфигурация по-умолчанию
 */
class CmsConfig {
    public static int $homepage_num_articles = 6;
    public static string $db_password = 'rw45e45e4e46';
    public static string $db_dsn = 'mysql:host=localhost;dbname=cms;charset=utf8;';
    public static string $db_username = 'drl';
    public static string $class_path = 'classes';
    public static string $template_path = 'templates';
    public static string $admin_username = 'admin';
    public static string $admin_password = 'mypass';
}

try {
    // Включаем полное отображение ошибок
    ini_set("display_errors", true);
    error_reporting(E_ALL);
    
    date_default_timezone_set("Europe/Moscow");  // http://www.php.net/manual/en/timezones.php
    
    
    include 'config-local.php'; /* подключаем локальный конфигурационный файл
     *  (для конкретной машины/сервера),
     *  в котором мы можем переопределить любые поля конфигурационного массива,
     *  например имя базы данных или пароль */
    
    
    // Подключаем Классы моделей (классы, отвечающие за работу с сущностями базы данных)
    require(CmsConfig::$class_path . "/Article.php");
    require(CmsConfig::$class_path . "/Category.php");     

} catch (Exception $ex) {
    echo "При загрузке конфигураций возникла проблема!<br><br>";
    error_log($ex->getMessage());
}

/**
 * Создаст константы, хранящие настройки приложения
 * 
 * @param array $constatsNameAndValues массив, содержащий в качестве ключей имена констант, 
 *  которые нужно объявить, а в качестве значений -- знчения этих констант
 */
function defineConstants($constatsNameAndValues)
{
    // обходим массив и определяем нужные константы
    foreach ($constatsNameAndValues as $constName => $constValue) {
       define($constName, $constValue);
    }
}


