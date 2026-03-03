<?php
/**
 * CARRI - Script de Diagnóstico del Servidor
 * ¡¡¡ BORRAR DESPUÉS DE USAR !!!
 * Acceder vía: https://carriroad.net/carri_diagnostico.php?secret=carri2026
 */

$SECRET = 'carri2026';
if (!isset($_GET['secret']) || $_GET['secret'] !== $SECRET) {
    http_response_code(403);
    die('Acceso denegado.');
}

// Path real de Laravel en el servidor según .cpanel.yml
$laravelRoot = '/home/carriroa/laravel_app';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carri - Diagnóstico</title>
    <style>
        body { font-family: monospace; background: #1a1a2e; color: #e0e0e0; padding: 20px; }
        h2 { color: #00d4ff; border-bottom: 1px solid #333; padding-bottom: 6px; }
        .ok   { color: #00ff88; }
        .warn { color: #ffaa00; }
        .err  { color: #ff4444; }
        pre   { background: #0d0d1a; padding: 15px; border-radius: 6px; overflow-x: auto; font-size: 13px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        td, th { border: 1px solid #333; padding: 6px 12px; text-align: left; }
        th { background: #0d0d1a; color: #00d4ff; }
    </style>
</head>
<body>
<h1>🔍 Carri - Diagnóstico del Servidor</h1>
<small class="warn">⚠️ BORRAR ESTE ARCHIVO DESPUÉS DE USAR</small>

<?php

// ── 1. PHP INFO ──────────────────────────────────────────────────────────
echo "<h2>1. PHP</h2><table>";
echo "<tr><th>Variable</th><th>Valor</th></tr>";
$phpInfo = [
    'Versión PHP'           => phpversion(),
    'Extension pdo'         => extension_loaded('pdo')     ? '<span class="ok">✔ cargada</span>' : '<span class="err">✘ falta</span>',
    'Extension pdo_mysql'   => extension_loaded('pdo_mysql')? '<span class="ok">✔ cargada</span>' : '<span class="err">✘ falta</span>',
    'Extension mbstring'    => extension_loaded('mbstring') ? '<span class="ok">✔ cargada</span>' : '<span class="err">✘ falta</span>',
    'Extension openssl'     => extension_loaded('openssl')  ? '<span class="ok">✔ cargada</span>' : '<span class="err">✘ falta</span>',
    'max_execution_time'    => ini_get('max_execution_time').'s',
    'memory_limit'          => ini_get('memory_limit'),
];
foreach ($phpInfo as $k => $v) echo "<tr><td>$k</td><td>$v</td></tr>";
echo "</table>";


// ── 2. .ENV ──────────────────────────────────────────────────────────────
echo "<h2>2. Archivo .env</h2>";
$envPath = $laravelRoot . '/.env';
if (!file_exists($envPath)) {
    echo "<p class='err'>❌ .env NO encontrado en: $envPath</p>";
} else {
    echo "<p class='ok'>✔ .env encontrado</p>";
    $envContent = file_get_contents($envPath);

    // Mostrar sin exponer contraseñas completas
    $lines = explode("\n", $envContent);
    $safe = [];
    foreach ($lines as $line) {
        if (preg_match('/(PASSWORD|SECRET|KEY)\s*=/i', $line)) {
            $parts = explode('=', $line, 2);
            $val = isset($parts[1]) ? '***' . substr(trim($parts[1]), -4) : '***';
            $safe[] = $parts[0] . '=' . $val;
        } else {
            $safe[] = $line;
        }
    }
    echo "<pre>" . htmlspecialchars(implode("\n", $safe)) . "</pre>";
}


// ── 3. CONEXIÓN A LA BASE DE DATOS ──────────────────────────────────────
echo "<h2>3. Conexión a la Base de Datos</h2>";
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    $connection = $env['DB_CONNECTION'] ?? 'mysql';
    $host       = $env['DB_HOST']       ?? '127.0.0.1';
    $port       = $env['DB_PORT']       ?? '3306';
    $database   = $env['DB_DATABASE']   ?? '';
    $username   = $env['DB_USERNAME']   ?? '';
    $password   = $env['DB_PASSWORD']   ?? '';

    echo "<table><tr><th>Parámetro</th><th>Valor</th></tr>";
    echo "<tr><td>Driver</td><td>$connection</td></tr>";
    echo "<tr><td>Host</td><td>$host:$port</td></tr>";
    echo "<tr><td>Database</td><td>$database</td></tr>";
    echo "<tr><td>Usuario</td><td>$username</td></tr>";
    echo "</table>";

    if ($connection === 'mysql' && $host && $database && $username) {
        try {
            $pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
                $username, $password,
                [PDO::ATTR_TIMEOUT => 5]
            );
            echo "<p class='ok'>✔ Conexión MySQL exitosa</p>";

            // Verificar tablas clave
            $tables = ['users', 'roles', 'model_has_roles', 'migrations'];
            echo "<table><tr><th>Tabla</th><th>Estado</th><th>Filas (aprox.)</th></tr>";
            foreach ($tables as $t) {
                try {
                    $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
                    echo "<tr><td>$t</td><td class='ok'>✔ existe</td><td>$count</td></tr>";
                } catch (Exception $e) {
                    echo "<tr><td>$t</td><td class='err'>✘ no existe</td><td>-</td></tr>";
                }
            }
            echo "</table>";

            // Ver usuarios existentes
            try {
                $users = $pdo->query("SELECT id, name, email, created_at FROM users LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
                echo "<h2>3b. Usuarios en la DB</h2>";
                if ($users) {
                    echo "<table><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Creado</th></tr>";
                    foreach ($users as $u) {
                        echo "<tr><td>{$u['id']}</td><td>{$u['name']}</td><td>{$u['email']}</td><td>{$u['created_at']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p class='warn'>⚠️ Tabla users está vacía — necesitas correr el seeder</p>";
                }
            } catch (Exception $e) {
                echo "<p class='err'>Error consultando users: " . htmlspecialchars($e->getMessage()) . "</p>";
            }

        } catch (PDOException $e) {
            echo "<p class='err'>❌ Error de conexión MySQL: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } elseif ($connection === 'sqlite') {
        echo "<p class='warn'>⚠️ El .env usa SQLite — en producción debería ser MySQL</p>";
    }
}


// ── 4. ÚLTIMAS LÍNEAS DEL LOG DE LARAVEL ────────────────────────────────
echo "<h2>4. Últimas líneas del Log de Laravel</h2>";
$logPath = $laravelRoot . '/storage/logs/laravel.log';
if (!file_exists($logPath)) {
    echo "<p class='warn'>⚠️ No existe el log: $logPath</p>";
} else {
    $logContent = file_get_contents($logPath);
    // Últimas 100 líneas
    $logLines = explode("\n", $logContent);
    $last100 = array_slice($logLines, -100);
    echo "<pre>" . htmlspecialchars(implode("\n", $last100)) . "</pre>";
}


// ── 5. PERMISOS DE DIRECTORIOS ───────────────────────────────────────────
echo "<h2>5. Permisos de Directorios</h2><table>";
echo "<tr><th>Directorio</th><th>Existe</th><th>Escribible</th></tr>";
$dirs = [
    'storage/logs'         => $laravelRoot . '/storage/logs',
    'storage/framework'    => $laravelRoot . '/storage/framework',
    'bootstrap/cache'      => $laravelRoot . '/bootstrap/cache',
    'vendor'               => $laravelRoot . '/vendor',
];
foreach ($dirs as $name => $path) {
    $exists    = is_dir($path)      ? "<span class='ok'>✔</span>" : "<span class='err'>✘</span>";
    $writable  = is_writable($path) ? "<span class='ok'>✔</span>" : "<span class='err'>✘</span>";
    echo "<tr><td>$name</td><td>$exists</td><td>$writable</td></tr>";
}
echo "</table>";

?>

<hr>
<p class="err">⚠️ RECUERDA: Borra <strong>carri_diagnostico.php</strong> del servidor cuando termines.</p>
</body>
</html>
