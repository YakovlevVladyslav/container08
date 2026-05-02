<?php
require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// Путь к тестовой базе данных
$testDbPath = __DIR__ . '/test_database.sqlite';
// Удаляем старую тестовую базу, если она осталась
if (file_exists($testDbPath)) unlink($testDbPath);

// --- Тесты класса Database ---

// Тест 1: Проверка подключения и создания таблицы
function testDbConnection() {
    global $testDbPath;
    $db = new Database($testDbPath);
    return assertExpression($db instanceof Database, "Database connected", "Database connection failed");
}

// Тест 2: Проверка метода Create
function testDbCreate() {
    global $testDbPath;
    $db = new Database($testDbPath);
    $db->Execute("CREATE TABLE test_table (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)");
    
    $id = $db->Create("test_table", ["name" => "TestName"]);
    return assertExpression($id == 1, "Record created with ID 1", "Failed to create record");
}

// Тест 3: Проверка метода Count
function testDbCount() {
    global $testDbPath;
    $db = new Database($testDbPath);
    $count = $db->Count("test_table");
    return assertExpression($count === 1, "Count is correct (1)", "Count mismatch");
}

// Тест 4: Проверка метода Read
function testDbRead() {
    global $testDbPath;
    $db = new Database($testDbPath);
    $data = $db->Read("test_table", 1);
    return assertExpression($data['name'] === "TestName", "Data read correctly", "Data read mismatch");
}

// Тест 5: Проверка метода Update
function testDbUpdate() {
    global $testDbPath;
    $db = new Database($testDbPath);
    $db->Update("test_table", 1, ["name" => "UpdatedName"]);
    $data = $db->Read("test_table", 1);
    return assertExpression($data['name'] === "UpdatedName", "Data updated correctly", "Update failed");
}

// Тест 6: Проверка метода Delete
function testDbDelete() {
    global $testDbPath;
    $db = new Database($testDbPath);
    $db->Delete("test_table", 1);
    $data = $db->Read("test_table", 1);
    return assertExpression($data === false, "Record deleted successfully", "Delete failed");
}

// --- Тесты класса Page ---

// Тест 7: Проверка рендеринга страницы
function testPageRender() {
    $tempTemplate = __DIR__ . '/test_template.tpl';
    file_put_contents($tempTemplate, "Hello, <?php echo \$name; ?>!");
    
    $page = new Page($tempTemplate);
    $output = $page->Render(["name" => "World"]);
    
    unlink($tempTemplate); // Удаляем временный шаблон
    return assertExpression($output === "Hello, World!", "Page rendered correctly", "Render output mismatch");
}

// Регистрация тестов
$testFramework->add('Database Connection', 'testDbConnection');
$testFramework->add('Database Create', 'testDbCreate');
$testFramework->add('Database Count', 'testDbCount');
$testFramework->add('Database Read', 'testDbRead');
$testFramework->add('Database Update', 'testDbUpdate');
$testFramework->add('Database Delete', 'testDbDelete');
$testFramework->add('Page Render', 'testPageRender');

// Запуск
$testFramework->run();

echo PHP_EOL . "FINAL RESULT: " . $testFramework->getResult() . PHP_EOL;

// Очистка после тестов
if (file_exists($testDbPath)) unlink($testDbPath);