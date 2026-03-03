<?php
/**
 * CARRI - Script de Reparación del Servidor
 * ¡¡¡ BORRAR DESPUÉS DE USAR !!!
 * Acceder vía: https://carriroad.net/carri_reparar.php?secret=carri2026&action=ACCION
 */

$SECRET      = 'carri2026';
$laravelRoot = '/home/carriroa/laravel_app';
$envPath     = $laravelRoot . '/.env';
$action      = $_GET['action'] ?? '';

if (!isset($_GET['secret']) || $_GET['secret'] !== $SECRET) {
    http_response_code(403);
    die('Acceso denegado.');
}

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
        a.btn { display:inline-block; margin:6px 4px; padding:10px 18px; background:#00d4ff; color:#000; text-decoration:none; border-radius:6px; font-weight:bold; }
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
echo "<a class='btn green'  href='{$base}&action=activar_usuarios'>✅ Activar Todas las Cuentas</a> ";
echo "<a class='btn'        href='{$base}&action=ver_usuario'>📄 Ver Todos los Usuarios</a> ";
echo "<a class='btn red'    href='{$base}&action=limpiar_cache'>🗑️ Limpiar Caché</a> ";
echo "<a class='btn'        href='{$base}&action=ver_env'>📄 Ver .env</a>";
echo "<hr>";

// ── HELPER: Ejecutar comando Artisan via bootstrap de Laravel ─────────────
function runArtisan(string $laravelRoot, string $command, array $args = []): string {
    ob_start();
    try {
        $_SERVER['argv'] = ['artisan', $command];
        $_SERVER['argc'] = 2;

        require_once $laravelRoot . '/vendor/autoload.php';
        defined('LARAVEL_START') || define('LARAVEL_START', microtime(true));
        $app = require $laravelRoot . '/bootstrap/app.php';

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

if ($action === 'migrar_y_seed') {
    echo "<h2>🚀 Migrar + Seed</h2>";
    echo "<h3>Paso 1: Migraciones</h3>";
    echo "<pre>" . htmlspecialchars(runArtisan($laravelRoot, 'migrate', ['--force' => true])) . "</pre>";
    echo "<h3>Paso 2: Seeder</h3>";
    echo "<pre>" . htmlspecialchars(runArtisan($laravelRoot, 'db:seed', ['--force' => true])) . "</pre>";
    echo "<p class='ok'><strong>✔ Proceso completado. Prueba el login ahora.</strong></p>";
} elseif ($action === 'migrar') {
    echo "<h2>📦 Migraciones</h2>";
    echo "<pre>" . htmlspecialchars(runArtisan($laravelRoot, 'migrate', ['--force' => true])) . "</pre>";
    echo "<p class='ok'><strong>✔ Migraciones completadas.</strong></p>";
} elseif ($action === 'seed') {
    echo "<h2>👤 Seeder</h2>";
    echo "<pre>" . htmlspecialchars(runArtisan($laravelRoot, 'db:seed', ['--force' => true])) . "</pre>";
    echo "<p class='ok'><strong>✔ Seed completado.</strong></p>";
} elseif ($action === 'activar_usuarios') {
    echo "<h2>✅ Activando cuentas de usuario</h2>";
    $pdo = getDB($envPath);
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET active = 1");
            $stmt->execute();
            $count = $stmt->rowCount();
            echo "<p class='ok'>✔ Se activaron $count cuentas de usuario correctamente.</p>";
        } catch (\Exception $e) {
            echo "<p class='err'>❌ Error actualizando usuarios: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
} elseif ($action === 'ver_usuario') {
    echo "<h2>📄 Ver Todos los Usuarios</h2>";
    $pdo = getDB($envPath);
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>" . htmlspecialchars(print_r($users, true)) . "</pre>";
        } catch (\Exception $e) {
            echo "<p class='err'>❌ Error leyendo usuarios: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
} elseif ($action === 'insertar_usuarios') {
    echo "<h2>👤 Insertar Usuarios del Seeder (PDO directo)</h2>";
    echo "<p class='err'>Usa la opción Migrar + Seed en su lugar.</p>";
} elseif ($action === 'limpiar_cache') {
    echo "<h2>🗑️ Limpiando Caché de Laravel</h2>";
    foreach (['config:clear', 'cache:clear', 'route:clear', 'view:clear'] as $cmd) {
        $out = runArtisan($laravelRoot, $cmd);
        echo "<p>✔ <code>$cmd</code>: " . htmlspecialchars(trim($out)) . "</p>";
    }
} elseif ($action === 'ver_env') {
    echo "<h2>📄 .env del servidor</h2>";
    if (file_exists($envPath)) {
        $safe = array_map(fn($l) => preg_match('/(PASSWORD|SECRET)\s*=/i', $l) ? explode('=', $l, 2)[0] . "=***\n" : $l, file($envPath));
        echo "<pre>" . htmlspecialchars(implode('', $safe)) . "</pre>";
    }
}
?>
<hr>
<p class="err">⚠️ RECUERDA: Borra <strong>carri_reparar.php</strong> del servidor cuando termines.</p>
</body>
</html>
