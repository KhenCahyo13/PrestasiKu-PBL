<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Ramsey\Uuid\Uuid;

class AuthController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function register(Request $request, Response $response): ResponseInterface
  {
    $data = json_decode($request->getBody(), true);
    $uuid = Uuid::uuid4();

    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($passwordPattern, $data['user_password'])) {
      $response->getBody()->write(json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.']));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $existingUser = $this->userModel->getUserByUsername($data['user_username']);
    if ($existingUser) {
      $response->getBody()->write(json_encode(['success' => false, 'message' => 'Username already exists!']));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if (!isset($data['role_id']) || !in_array($data['role_id'], ['39DC23CD-CB63-4073-A15A-2D8A878A3F58', 'FBA2D7AA-4F83-4C48-9C9A-4EB7F8A253F8'])) {
      $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid role!']));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $insertData = [
      'user_id' => $uuid->toString(),
      'user_username' => $data['user_username'],
      'user_password' => password_hash($data['user_password'], PASSWORD_DEFAULT),
      'role_id' => $data['role_id'],
      'detail_id' => $uuid->toString()
    ];

    if ($data['role_id'] === '39DC23CD-CB63-4073-A15A-2D8A878A3F58') {
      $insertData += [
        'spclass_id' => $data['spclass_id'],
        'detail_name' => $data['detail_name'],
        'detail_nim' => $data['detail_nim'],
        'detail_date_of_birth' => $data['detail_date_of_birth'],
        'detail_phone_number' => $data['detail_phone_number'],
        'detail_email' => $data['detail_email'],
        'detail_profile' => $data['detail_profile']
      ];
      $result = $this->userModel->createStudent($insertData);
    } else {
      $insertData += [
        'department_id' => $data['department_id'],
        'detail_name' => $data['detail_name'],
        'detail_nip' => $data['detail_nip'],
        'detail_phone_number' => $data['detail_phone_number'],
        'detail_email' => $data['detail_email'],
        'detail_profile' => $data['detail_profile']
      ];
      $result = $this->userModel->createLecture($insertData);
    }

    if ($result) {
      $responseData = ['success' => true, 'message' => 'Registration successful!', 'data' => $insertData];
    } else {
      $responseData = ['success' => false, 'message' => 'Failed to register user.'];
    }

    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($result ? 201 : 500);
  }

  public function login(Request $request, Response $response): ResponseInterface
  {
    $body = $request->getBody();
    $data = json_decode($body, true);

    if (!empty($data['user_username']) && !empty($data['user_password'])) {
      $user = $this->userModel->getUserByUsername($data['user_username']);

      if ($user) {
        if (password_verify($data['user_password'], $user['user_password'])) {
          if ($user['user_isverified'] == 1) {
            $_SESSION['user'] = [
              'id' => $user['user_id'],
              'username' => $user['user_username'],
              'role' => $user['role_id']
            ];

            $responseData = [
              'success' => true,
              'message' => 'Login successful!',
              'data' => $_SESSION['user']
            ];
          } else {
            $responseData = [
              'success' => false,
              'message' => 'You are not verified by the admin!',
            ];
          }
        } else {
          $responseData = [
            'success' => false,
            'message' => 'Invalid password!',
          ];
        }
      } else {
        $responseData = [
          'success' => false,
          'message' => 'User not found!',
        ];
      }
    } else {
      $responseData = [
        'success' => false,
        'message' => 'Username and password are required!',
      ];
    }

    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
  }


  public function logout(Request $request, Response $response): ResponseInterface
  {
    if (!isset($_SESSION['user'])) {
      $responseData = [
        'success' => false,
        'message' => 'You are not logged in!',
      ];
      $response->getBody()->write(json_encode($responseData));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    unset($_SESSION['user']);
    session_destroy();

    $responseData = [
      'success' => true,
      'message' => 'Logout successful!',
    ];

    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
  }
}
