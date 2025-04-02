<?php
class ApiResponse
{
  public static function success($data, $message = '', $code = 200)
  {
    header('Content-Type: application/json');
    http_response_code($code);

    $response = [
      'success' => true,
      'data' => $data,
      'message' => $message
    ];

    echo json_encode($response);
    exit;
  }

  public static function error($message, $code = 400)
  {
    http_response_code($code);
    return [
      'success' => false,
      'error' => $message
    ];
  }
}
