<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'Default Title'; ?></title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="?page=1">Главная</a> | <a href="?page=2">О нас</a>
        </nav>
    </header>
    
    <main>
        <h1><?php echo $header ?? ''; ?></h1>
        <div class="content">
            <?php echo $content ?? ''; ?>
        </div>
    </main>
</body>
</html>