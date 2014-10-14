<?php
if (file_exists(realpath(__DIR__.'/../Chayka.Helpers/autoload.php'))){
    require_once realpath(__DIR__.'/../Chayka.Helpers/autoload.php');
} elseif (file_exists('vendors/autoload.php')) {
    require_once 'vendors/autoload.php';
}

spl_autoload_register(function ($class) {

    // префикс пространства имён проекта
    $prefix = 'Chayka\\MVC\\';

    // базовая директория для этого префикса
    $base_dir = __DIR__ . '/src/';

    // класс использует префикс?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // нет. Пусть попытается другой автозагрузчик
        return;
    }

    // заменяем префикс базовой директорией, заменяем разделители пространства имён
    // на разделители директорий в относительном имени класса, добавляем .php
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    // если файл существует, подключаем его
    if (file_exists($file)) {
        require_once $file;
    }
});