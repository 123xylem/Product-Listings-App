<?php
class CorsHandler
{
  public static function setHeaders()
  {
    //Respond with correct allowOrigin pending on the origin of the request
    $allowedOrigins = explode(',', $_ENV['CORS_ALLOWED_ORIGINS']);
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (in_array($origin, $allowedOrigins)) {
      header('Access-Control-Allow-Origin: ' . $origin);
    }

    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
  }
}
