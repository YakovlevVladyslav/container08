<?php
require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/config.php';

// Инициализируем БД по пути из конфига
$db = new Database($config["db"]["path"]);

// Инициализируем страницу
$page = new Page(__DIR__ . '/templates/index.tpl');

// Получаем ID из URL (например, index.php?page=1), по умолчанию 1
$pageId = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Получаем данные из таблицы 'pages'
$data = $db->Read("pages", $pageId);

if ($data) {
    // Выводим отрендеренный контент
    echo $page->Render($data);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Страница не найдена</h1>";
}