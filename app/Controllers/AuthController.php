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

    public function __construct() {
      $this->userModel = new User();
    }

    public function register(Request $request, Response $response): ResponseInterface {
      $body = $request->getBody();
      $data = json_decode($body, true);     

      $uuid = Uuid::uuid4();
      $role_id = "39DC23CD-CB63-4073-A15A-2D8A878A3F58";

      $userUsername = $data['user_username'] ?? '';
      $userPassword = $data['user_password'] ?? '';
      $userSPClassId = $data['spclass_id'] ?? '';
      $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

      $userStudentDetail = $data['student_details'] ?? [];

      $insertData = [
        'user_username' => $userUsername,
        'user_password' => $hashedPassword,
        'role_id' => $role_id,
        'detail_student_id' => $uuid->toString(),
        'spclass_id' => $userSPClassId,
        'detail_name' => $userStudentDetail['detail_name'] ?? '',
        'detail_nim' => $userStudentDetail['detail_nim'] ?? '',
        'detail_date_of_birth' => $userStudentDetail['detail_date_of_birth'] ?? '',
        'detail_phone_number' => $userStudentDetail['detail_phone_number'] ?? '',
        'detail_email' => $userStudentDetail['detail_email'] ?? '',
        'detail_profile' => $userStudentDetail['detail_profile'] ?? '',
      ];

      $action = $this->userModel->createStudent($insertData);

      if ($action) {
        $responseData = array(
          'success' => true,
          'message' => 'Successfully registered!',
          'data' => $insertData
        );
      } else {
        $responseData = array(
          'success' => false,
          'message' => 'Failed to register!',
          'data' => null
        );
      }

      $response->getBody()->write(json_encode($responseData));

      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
    }

    public function login(Request $request, Response $response): ResponseInterface
    {
      $body = $request->getBody();
      $data = json_decode($body, true);

      $username = $data['user_username'] ?? '';
      $password = $data['user_password'] ?? '';

      if (empty($username) || empty($password)) {
        $responseData = [
          'success' => false,
          'message' => 'Username and password are required!',
        ];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
      }

      $user = $this->userModel->getUserByUsername($username);

      if (!$user || !password_verify($password, $user['user_password'])) {
        $responseData = [
          'success' => false,
          'message' => 'Invalid username or password!',
        ];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
      }

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

?>