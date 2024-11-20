<?php
    namespace App\Controllers;

    use App\Models\User;
    use Psr\Http\Message\ResponseInterface;
    use Slim\Psr7\Request;
    use Slim\Psr7\Response;

    class AuthController extends Controller
    {
        private $userModel;

        public function __construct() {
            $this->userModel = new User();
        }

        public function register(Request $request, Response $response): ResponseInterface {
            $data = $request->getParsedBody();

            $username = $data['user_username'] ?? '';
            $password = $data['user_password'] ?? '';

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertData = array(
                'user_username' => $username,
                'user_password' => $hashedPassword
            );

            $action = $this->userModel->create($insertData);

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
    }
?>