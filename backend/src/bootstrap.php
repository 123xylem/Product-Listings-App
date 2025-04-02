<?php
// src/bootstrap.php - Load this in all entry points
require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
  require_once __DIR__ . '/../src/' . $class . '.php';
});

// Load environment vars 
if (!function_exists('getenv') || !getenv('DB_HOST')) {
  require_once __DIR__ . '/../vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
}

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/components/ErrorComponent.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
function appErrorHandler($errno, $errstr, $errfile, $errline)
{
  var_dump($errstr, 'error caught in bootstrap');

  $logFile = __DIR__ . '/../logs/error.log';

  if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0755, true);
  }

  $errorMessage = sprintf(
    "[%s] Error: [%s] %s in %s on line %d\n",
    date('Y-m-d H:i:s'),
    $errno,
    $errstr,
    $errfile,
    $errline
  );

  error_log($errorMessage, 3, $logFile);

  // For API requests, return JSON error
  if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    header('Content-Type: application/json');
    echo json_encode([
      'error' => $errstr,
      'code' => 500
    ]);
    exit;
  }

  include __DIR__ . '/../public/error.php';
  exit;
}


function appExceptionHandler($exception)
{
  var_dump($exception, 'exception in bootstrap');
  $logFile = __DIR__ . '/../logs/exception.log';

  if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0755, true);
  }

  $errorMessage = sprintf(
    "[%s] Exception: %s in %s on line %d\nStack trace: %s\n",
    date('Y-m-d H:i:s'),
    $exception->getMessage(),
    $exception->getFile(),
    $exception->getLine(),
    $exception->getTraceAsString()
  );

  error_log($errorMessage, 3, $logFile);

  if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    header('Content-Type: application/json');
    echo json_encode([
      'error' => $errorMessage,
      'code' => 500
    ]);
    exit;
  }

  // For web requests, show error page
  include __DIR__ . '/../public/error.php';
  exit;
}


// Register shutdown function to catch fatal errors
register_shutdown_function(function () {
  $error = error_get_last();
  if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
    appErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
  }
});


set_error_handler('appErrorHandler');
set_exception_handler('appExceptionHandler');
