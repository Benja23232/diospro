<?php
// Mostrar errores a pantalla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

// 1. **CORRECCIÓN DE RUTA:** Asegúrate de que esta línea es correcta para tu estructura:
require dirname(__DIR__).'/vendor/autoload.php';

// Forzar entorno y debug
// 2. **CORRECCIÓN CLAVE:** 'dev' debe estar entre comillas
$_SERVER['APP_ENV']   = 'dev'; 
$_SERVER['APP_DEBUG'] = true;       // <-- activamos debug!

// 3. **IMPORTANTE:** Cambiar a configuración local de XAMPP
// Asume que el usuario es 'root', la contraseña está vacía y la base de datos es 'Diospro_db'
$_SERVER['APP_SECRET']    = '5f2eee12ceeeee45d635929058b5cf63'; // Mantener el mismo secret
$_SERVER['DATABASE_URL']  = 'mysql://root:@127.0.0.1:3306/Diospro_db?serverVersion=8.0&charset=utf8mb4';

try {
    $kernel   = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
    $request = Request::createFromGlobals();
    $response= $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    // Mostrar mensaje + traza completa
    echo '<h2>Error capturado:</h2>';
    echo '<pre>' . $e->getMessage() . "\n\n" . $e->getTraceAsString() . '</pre>';
    exit;
}