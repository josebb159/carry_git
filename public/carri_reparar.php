<?php
/**
 * CARRI - Script de Reparación del Servidor
 * ¡¡¡ BORRAR DESPUÉS DE USAR !!!
 * Acceder vía: https://carriroad.net/carri_reparar.php?secret=carri2026&action=ACCION
 *
 * Acciones disponibles:
 *   &action=migrar              → Corre php artisan migrate --force
 *   &action=seed                → Corre php artisan db:seed
 *   &action=migrar_y_seed       → Migra + seed todo
 *   &action=insertar_usuarios   → Inserta usuarios directamente vía PDO
 *   &action=limpiar_cache       → Borra archivos de caché de Laravel
 *   &action=ver_env             → Muestra el .env del servidor
 */

$SECRET      = 'carri2026';
$laravelRoot = '/home/carriroa/laravel_app';
$envPath     = $laravelRoot . '/.env';
$action      = $_GET['action'] ?? '';

if (!isset($_GET['secret']) || $_GET['secret'] !== $SECRET) {
    http_response_code(403);
    die('Acceso denegado.');
}

// Aumentar tiempo de ejecución para migraciones largas
set_time_limit(120);

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carri - Reparación</title>
    <style>
        body  { font-family: monospace; background: #1a1a2e; color: #e0e0e0; padding: 20px; }
        h2    { color: #00d4ff; }
        .ok   { color: #00ff88; }
        .warn { color: #ffaa00; }
        .err  { color: #ff4444; }
        pre   { background: #0d0d1a; padding: 15px; border-radius: 6px; white-space: pre-wrap; word-break: break-word; }
        a.btn { display:inline-block; margin:6px 4px; padding:10px 18px; background:#00d4ff;
                color:#000; text-decoration:none; border-radius:6px; font-weight:bold; }
        a.btn.green  { background:#00ff88; }
        a.btn.red    { background:#ff4444; color:#fff; }
        a.btn.yellow { background:#ffaa00; }
        hr { border-color: #333; margin: 20px 0; }
    </style>
</head>
<body>
<h1>🔧 Carri - Reparación del Servidor</h1>
<small class="warn">⚠️ BORRAR ESTE ARCHIVO DESPUÉS DE USAR</small>
<hr>

<?php
$base = "?secret=$SECRET";
echo "<p><strong>Selecciona una acción:</strong></p>";
echo "<a class='btn green'  href='{$base}&action=migrar_y_seed'>🚀 Migrar + Seed (recomendado)</a> ";
echo "<a class='btn'        href='{$base}&action=migrar'>📦 Solo Migrar</a> ";
echo "<a class='btn'        href='{$base}&action=seed'>👤 Solo Seed</a> ";
echo "<a class='btn yellow' href='{$base}&action=insertar_usuarios'>👤 Insertar Usuarios (manual PDO)</a> ";
echo "<a class='btn red'    href='{$base}&action=limpiar_cache'>🗑️ Limpiar Caché</a> ";
echo "<a class='btn'        href='{$base}&action=ver_env'>📄 Ver .env</a>";
echo "<hr>";

// ── HELPER: Ejecutar comando Artisan via bootstrap de Laravel ─────────────
function runArtisan(string $laravelRoot, string $command, array $args = []): string {
    ob_start();
    try {
        $_SERVER['argv'] = ['artisan', $command];
        $_SERVER['argc'] = 2;

        // Cargar autoloader de Composer (indispensable)
        require_once $laravelRoot . '/vendor/autoload.php';

        defined('LARAVEL_START') || define('LARAVEL_START', microtime(true));

        $app = require $laravelRoot . '/bootstrap/app.php';

        /** @var \Illuminate\Contracts\Console\Kernel $kernel */
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        $input  = new \Symfony\Component\Console\Input\ArrayInput(array_merge(['command' => $command], $args));
        $output = new \Symfony\Component\Console\Output\BufferedOutput();

        $status = $kernel->handle($input, $output);
        $result = $output->fetch();

        $kernel->terminate($input, $status);

        return $result ?: "(sin salida, código: $status)";
    } catch (\Throwable $e) {
        return "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
    } finally {
        ob_end_clean();
    }
}

// ── HELPER: Conectar DB directamente ─────────────────────────────────────
function getDB(string $envPath): ?PDO {
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
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 10]
        );
    } catch (\PDOException $e) {
        echo "<p class='err'>❌ Error DB: " . htmlspecialchars($e->getMessage()) . "</p>";
        return null;
    }
}


// ══════════════════════════════════════════════════════════════════════════
// ACCIÓN: MIGRAR + SEED
// ══════════════════════════════════════════════════════════════════════════
if ($action === 'migrar_y_seed') {
    echo "<h2>🚀 Migrar + Seed</h2>";

    echo "<h3>Paso 1: Migraciones</h3>";
    $out = runArtisan($laravelRoot, 'migrate', ['--force' => true]);
    echo "<pre>" . htmlspecialchars($out) . "</pre>";

    echo "<h3>Paso 2: Seeder</h3>";
    $out2 = runArtisan($laravelRoot, 'db:seed', ['--force' => true]);
    echo "<pre>" . htmlspecialchars($out2) . "</pre>";

    echo "<p class='ok'><strong>✔ Proceso completado. Prueba el login ahora.</strong></p>";
    echo "<p><a class='btn' href='https://carriroad.net/api/v10/signin' target='_blank'>🔗 Probar API</a></p>";
}


// ══════════════════════════════════════════════════════════════════════════
// ACCIÓN: SOLO MIGRAR
// ══════════════════════════════════════════════════════════════════════════
elseif ($action === 'migrar') {
    echo "<h2>📦 Migraciones</h2>";
    $out = runArtisan($laravelRoot, 'migrate', ['--force' => true]);
    echo "<pre>" . htmlspecialchars($out) . "</pre>";
    echo "<p class='ok'><strong>✔ Migraciones completadas.</strong></p>";
}


// ══════════════════════════════════════════════════════════════════════════
// ACCIÓN: SOLO SEED
// ══════════════════════════════════════════════════════════════════════════
elseif ($action === 'seed') {
    echo "<h2>👤 Seeder</h2>";
    $out = runArtisan($laravelRoot, 'db:seed', ['--force' => true]);
    echo "<pre>" . htmlspecialchars($out) . "</pre>";
    echo "<p class='ok'><strong>✔ Seed completado.</strong></p>";
}


// ══════════════════════════════════════════════════════════════════════════
// ACCIÓN: INSERTAR USUARIOS MANUALMENTE (PDO directo, sin artisan)
// ══════════════════════════════════════════════════════════════════════════
elseif ($action === 'insertar_usuarios') {
    echo "<h2>👤 Insertar Usuarios del Seeder (PDO directo)</h2>";

    $pdo = getDB($envPath);
    if (!$pdo) { echo "<p class='err'>❌ No se pudo conectar a la DB.</p>"; goto done; }
    echo "<p class='ok'>✔ Conectado a MySQL</p>";

    $users = [
        ['name' => 'Admin Principal',  'email' => 'admin@carri.com',    'password' => 'Admin1234!',    'role' => 'admin'],
        ['name' => 'Agente Operativo', 'email' => 'agent@carri.com',    'password' => 'Agent1234!',    'role' => 'agent'],
        ['name' => 'Comerciante Demo', 'email' => 'merchant@carri.com', 'password' => 'Merchant1234!', 'role' => 'merchant'],
        ['name' => 'Repartidor Demo',  'email' => 'delivery@carri.com', 'password' => 'Delivery1234!', 'role' => 'delivery'],
        ['name' => 'Usuario General',  'email' => 'user@carri.com',     'password' => 'User1234!',     'role' => 'user'],
    ];

    // Detectar model_type real
    $modelType = 'App\\Models\\User';
    try {
        $row = $pdo->query("SELECT model_type FROM model_has_roles LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        if ($row) $modelType = $row['model_type'];
    } catch (\Exception $e) {}
    echo "<p>model_type detectado: <strong>$modelType</strong></p><ul>";

    foreach ($users as $u) {
        try {
            $hash = password_hash($u['password'], PASSWORD_BCRYPT);
            $now  = date('Y-m-d H:i:s');

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$u['email']]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $pdo->prepare("UPDATE users SET password=?, updated_at=? WHERE email=?")
                    ->execute([$hash, $now, $u['email']]);
                $userId = $existing['id'];
                $txt = 'actualizado';
            } else {
                $pdo->prepare("INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES (?,?,?,?,?,?)")
                    ->execute([$u['name'], $u['email'], $hash, $now, $now, $now]);
                $userId = $pdo->lastInsertId();
                $txt = 'creado';
            }

            $roleStmt = $pdo->prepare("SELECT id FROM roles WHERE name = ?");
            $roleStmt->execute([$u['role']]);
            $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);

            if ($roleRow) {
                $roleId = $roleRow['id'];
                $pivot  = $pdo->prepare("SELECT 1 FROM model_has_roles WHERE role_id=? AND model_type=? AND model_id=?");
                $pivot->execute([$roleId, $modelType, $userId]);
                if (!$pivot->fetch()) {
                    $pdo->prepare("INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (?,?,?)")
                        ->execute([$roleId, $modelType, $userId]);
                }
                echo "<li class='ok'>✔ <strong>{$u['email']}</strong> ($txt) → rol '{$u['role']}' asignado</li>";
            } else {
                echo "<li class='warn'>⚠️ <strong>{$u['email']}</strong> ($txt) — rol '{$u['role']}' no existe en DB</li>";
            }
        } catch (\Exception $e) {
            echo "<li class='err'>❌ {$u['email']}: " . htmlspecialchars($e->getMessage()) . "</li>";
        }
    }
    echo "</ul><p class='ok'><strong>✔ Listo. Prueba el login ahora.</strong></p>";
}


// ══════════════════════════════════════════════════════════════════════════
// ACCIÓN: LIMPIAR CACHÉ
// ══════════════════════════════════════════════════════════════════════════
elseif ($action === 'limpiar_cache') {
    echo "<h2>🗑️ Limpiando Caché de Laravel</h2>";

    // Intentar via artisan primero
    echo "<h3>Via Artisan</h3>";
    foreach (['config:clear', 'cache:clear', 'route:clear', 'view:clear'] as $cmd) {
        $out = runArtisan($laravelRoot, $cmd);
        $icon = str_contains(strtolower($out), 'error') ? '❌' : '✔';
        echo "<p>$icon <code>$cmd</code>: " . htmlspecialchars(trim($out)) . "</p>";
    }

    // Borrar archivos cache manualmente como fallback
    echo "<h3>Archivos de bootstrap/cache</h3><ul>";
    foreach (['config.php','routes-v7.php','services.php','packages.php','events.php'] as $f) {
        $path = "$laravelRoot/bootstrap/cache/$f";
        if (file_exists($path)) {
            echo @unlink($path)
                ? "<li class='ok'>✔ Eliminado: $f</li>"
                : "<li class='err'>✘ Sin permisos: $f</li>";
        } else {
            echo "<li class='warn'>- No existe: $f</li>";
        }
    }
    echo "</ul><p class='ok'><strong>✔ Caché limpiada.</strong></p>";
}


// ══════════════════════════════════════════════════════════════════════════
// ACCIÓN: VER .ENV
// ══════════════════════════════════════════════════════════════════════════
elseif ($action === 'ver_env') {
    echo "<h2>📄 .env del servidor</h2>";
    if (!file_exists($envPath)) {
        echo "<p class='err'>❌ .env no encontrado en: $envPath</p>";
    } else {
        $lines = file($envPath);
        $safe  = array_map(fn($l) =>
            preg_match('/(PASSWORD|SECRET)\s*=/i', $l)
                ? explode('=', $l, 2)[0] . "=***\n"
                : $l,
            $lines
        );
        echo "<pre>" . htmlspecialchars(implode('', $safe)) . "</pre>";
    }
}

done:
?>
<hr>
<p class="err">⚠️ RECUERDA: Borra <strong>carri_reparar.php</strong> del servidor cuando termines.</p>
</body>
</html>
