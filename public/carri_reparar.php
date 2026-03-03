<?php
/**
 * CARRI - Script de Reparación del Servidor
 * ¡¡¡ BORRAR DESPUÉS DE USAR !!!
 * Acceder vía: https://carriroad.net/carri_reparar.php?secret=carri2026&action=ACCION
 *
 * Acciones disponibles:
 *   ?secret=carri2026&action=insertar_usuarios   → Inserta los usuarios del seeder
 *   ?secret=carri2026&action=ver_env             → Muestra el .env del servidor
 */

$SECRET = 'carri2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $SECRET) {
    http_response_code(403);
    die('Acceso denegado.');
}

// Path real de Laravel en el servidor según .cpanel.yml
$laravelRoot = '/home/carriroa/laravel_app';
$envPath     = $laravelRoot . '/.env';
$action      = $_GET['action'] ?? '';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carri - Reparación</title>
    <style>
        body { font-family: monospace; background: #1a1a2e; color: #e0e0e0; padding: 20px; }
        h2 { color: #00d4ff; }
        .ok   { color: #00ff88; }
        .warn { color: #ffaa00; }
        .err  { color: #ff4444; }
        pre   { background: #0d0d1a; padding: 15px; border-radius: 6px; }
        a.btn { display:inline-block; margin:8px 4px; padding:10px 20px; background:#00d4ff;
                color:#000; text-decoration:none; border-radius:6px; font-weight:bold; }
        a.btn.red { background:#ff4444; color:#fff; }
    </style>
</head>
<body>
<h1>🔧 Carri - Reparación del Servidor</h1>
<small class="warn">⚠️ BORRAR ESTE ARCHIVO DESPUÉS DE USAR</small>
<hr>

<?php
// ── MENU ─────────────────────────────────────────────────────────────────
$base = "?secret=$SECRET";
echo "<p>Selecciona una acción:</p>";
echo "<a class='btn' href='{$base}&action=insertar_usuarios'>👤 Insertar Usuarios (Seeder)</a> ";
echo "<a class='btn' href='{$base}&action=ver_env'>📄 Ver .env del servidor</a> ";
echo "<a class='btn red' href='{$base}&action=limpiar_cache'>🗑️ Limpiar caché de Laravel</a>";
echo "<hr>";

// ── HELPER: CONECTAR DB ──────────────────────────────────────────────────
function getDB($envPath) {
    if (!file_exists($envPath)) return null;
    $env      = parse_ini_file($envPath);
    $host     = $env['DB_HOST']     ?? '127.0.0.1';
    $port     = $env['DB_PORT']     ?? '3306';
    $database = $env['DB_DATABASE'] ?? '';
    $username = $env['DB_USERNAME'] ?? '';
    $password = $env['DB_PASSWORD'] ?? '';
    if (!$database || !$username) return null;
    try {
        return new PDO(
            "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
            $username, $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
        );
    } catch (PDOException $e) {
        echo "<p class='err'>❌ Error DB: " . htmlspecialchars($e->getMessage()) . "</p>";
        return null;
    }
}


// ── ACCIÓN: INSERTAR USUARIOS ────────────────────────────────────────────
if ($action === 'insertar_usuarios') {
    echo "<h2>👤 Insertar Usuarios del Seeder</h2>";

    $pdo = getDB($envPath);
    if (!$pdo) { echo "<p class='err'>❌ No se pudo conectar a la DB.</p>"; goto end; }

    echo "<p class='ok'>✔ Conectado a la base de datos</p>";

    $users = [
        ['name' => 'Admin Principal',  'email' => 'admin@carri.com',    'password' => 'Admin1234!',    'role' => 'admin'],
        ['name' => 'Agente Operativo', 'email' => 'agent@carri.com',    'password' => 'Agent1234!',    'role' => 'agent'],
        ['name' => 'Comerciante Demo', 'email' => 'merchant@carri.com', 'password' => 'Merchant1234!', 'role' => 'merchant'],
        ['name' => 'Repartidor Demo',  'email' => 'delivery@carri.com', 'password' => 'Delivery1234!', 'role' => 'delivery'],
        ['name' => 'Usuario General',  'email' => 'user@carri.com',     'password' => 'User1234!',     'role' => 'user'],
    ];

    // Detectar namespace del modelo en model_has_roles
    $modelType = 'App\\Models\\User';
    try {
        $row = $pdo->query("SELECT model_type FROM model_has_roles LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        if ($row) $modelType = $row['model_type'];
    } catch (Exception $e) {}

    echo "<p>Usando model_type: <strong>$modelType</strong></p><ul>";

    foreach ($users as $userData) {
        $email = $userData['email'];
        $role  = $userData['role'];

        // Upsert usuario
        try {
            $hash = password_hash($userData['password'], PASSWORD_BCRYPT);
            $now  = date('Y-m-d H:i:s');

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Actualizar contraseña
                $pdo->prepare("UPDATE users SET password = ?, updated_at = ? WHERE email = ?")
                    ->execute([$hash, $now, $email]);
                $userId = $existing['id'];
                $action_txt = 'actualizado';
            } else {
                // Insertar nuevo
                $pdo->prepare("INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES (?,?,?,?,?,?)")
                    ->execute([$userData['name'], $email, $hash, $now, $now, $now]);
                $userId = $pdo->lastInsertId();
                $action_txt = 'creado';
            }

            // Buscar rol
            $roleStmt = $pdo->prepare("SELECT id FROM roles WHERE name = ?");
            $roleStmt->execute([$role]);
            $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);

            if ($roleRow) {
                $roleId = $roleRow['id'];
                // Upsert pivot
                $pivot = $pdo->prepare("SELECT * FROM model_has_roles WHERE role_id=? AND model_type=? AND model_id=?");
                $pivot->execute([$roleId, $modelType, $userId]);
                if (!$pivot->fetch()) {
                    $pdo->prepare("INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (?,?,?)")
                        ->execute([$roleId, $modelType, $userId]);
                }
                echo "<li class='ok'>✔ Usuario <strong>$email</strong> ($action_txt) → rol <strong>$role</strong> asignado</li>";
            } else {
                echo "<li class='warn'>⚠️ Usuario <strong>$email</strong> ($action_txt), pero el rol '$role' NO existe en la DB</li>";
            }

        } catch (Exception $e) {
            echo "<li class='err'>❌ Error con $email: " . htmlspecialchars($e->getMessage()) . "</li>";
        }
    }

    echo "</ul><p class='ok'><strong>✔ Proceso completado.</strong> Prueba el login ahora.</p>";
}


// ── ACCIÓN: VER .ENV ─────────────────────────────────────────────────────
elseif ($action === 'ver_env') {
    echo "<h2>📄 .env del servidor</h2>";
    if (!file_exists($envPath)) {
        echo "<p class='err'>❌ .env NO encontrado en: $envPath</p>";
    } else {
        $lines = file($envPath);
        $safe  = [];
        foreach ($lines as $line) {
            // Ocultar contraseñas parcialmente
            if (preg_match('/(PASSWORD|SECRET)\s*=/i', $line)) {
                $parts = explode('=', $line, 2);
                $safe[] = $parts[0] . '=***' . PHP_EOL;
            } else {
                $safe[] = $line;
            }
        }
        echo "<pre>" . htmlspecialchars(implode('', $safe)) . "</pre>";
        echo "<p class='warn'>⚠️ Si ves <code>DB_CONNECTION=sqlite</code>, ese es el problema — el servidor necesita MySQL.</p>";
    }
}


// ── ACCIÓN: LIMPIAR CACHÉ ─────────────────────────────────────────────────
elseif ($action === 'limpiar_cache') {
    echo "<h2>🗑️ Limpiando Caché de Laravel</h2><ul>";

    $paths = [
        'bootstrap/cache/config.php'    => $laravelRoot . '/bootstrap/cache/config.php',
        'bootstrap/cache/routes-v7.php' => $laravelRoot . '/bootstrap/cache/routes-v7.php',
        'bootstrap/cache/services.php'  => $laravelRoot . '/bootstrap/cache/services.php',
        'bootstrap/cache/packages.php'  => $laravelRoot . '/bootstrap/cache/packages.php',
    ];

    foreach ($paths as $name => $path) {
        if (file_exists($path)) {
            if (@unlink($path)) {
                echo "<li class='ok'>✔ Eliminado: $name</li>";
            } else {
                echo "<li class='err'>✘ Sin permisos para borrar: $name</li>";
            }
        } else {
            echo "<li class='warn'>- No existe: $name</li>";
        }
    }

    // Limpiar storage/framework/cache
    $cacheDir = $laravelRoot . '/storage/framework/cache/data';
    if (is_dir($cacheDir)) {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($cacheDir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        $count = 0;
        foreach ($iter as $file) {
            if ($file->isFile()) { @unlink($file->getPathname()); $count++; }
        }
        echo "<li class='ok'>✔ Eliminados $count archivos de caché de datos</li>";
    }

    echo "</ul><p class='ok'><strong>✔ Caché limpiada.</strong></p>";
}

end:
?>
<hr>
<p class="err">⚠️ RECUERDA: Borra <strong>carri_reparar.php</strong> del servidor cuando termines.</p>
</body>
</html>
