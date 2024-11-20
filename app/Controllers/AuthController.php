<?php
    namespace App\Controllers;

    use App\Models\User;

    class UserController extends Controller {
        private $userModel;

        public function __construct() {
            $this->userModel = new User();
        }

        public function register() {
            $hashedPassword = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
            $data = array(
                'user_username' => $_POST['user_username'],
                'user_password' => $hashedPassword
            );

            $action = $this->userModel->create($data);

            if ($action) {
                $response = array(
                    'success' => true,
                    'message' => 'Successfully registered!',
                    'data' => $action
                );

                echo json_encode($response);
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Failed to register!',
                    'data' => null
                );
                echo json_encode($response);
            }
        }
    }
?>