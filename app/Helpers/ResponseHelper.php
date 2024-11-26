<?php

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
  public static function success(ResponseInterface $response, array $data = [], string $message = 'Success', int $statusCode = 200): ResponseInterface
  {
    $responseData = [
      'success' => true,
      'message' => $message,
      'data' => $data
    ];

    $response->getBody()->write(json_encode($responseData));
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus($statusCode);
  }

  public static function error(ResponseInterface $response, string $message, int $statusCode = 400): ResponseInterface
  {
    $responseData = [
      'success' => false,
      'message' => $message
    ];

    $response->getBody()->write(json_encode($responseData));
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus($statusCode);
  }

  public static function withPagination(ResponseInterface $response, array $data, int $currentPage, int $totalPages, int $totalItems, int $itemsPerPage, string $message = 'Success'): ResponseInterface
  {
    $responseData = [
      'success' => true,
      'message' => $message,
      'data' => $data,
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'items_per_page' => $itemsPerPage
      ]
    ];

    $response->getBody()->write(json_encode($responseData));
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
  }
}
