<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AdminController{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function getUsers(Request $request, Response $response): ResponseInterface
  {
    try {
      $users = $this->userModel->getUsers();
      return ResponseHelper::success($response, $users, 'Users retrieved successfully');
    } catch (\Exception $e) {
      return ResponseHelper::error($response, 'Failed to retrieve users', 500);
    }
  }

  public function getUserById(Request $request, Response $response, $args): ResponseInterface
  {
    try {
      $userId = $args['id'];
      $user = $this->userModel->getUserById($userId);
      if ($user) {
        return ResponseHelper::success($response, $user, 'User retrieved successfully');
      } else {
        return ResponseHelper::error($response, 'User not found', 404);
      }
    } catch (\Exception $e) {
      return ResponseHelper::error($response, 'Failed to retrieve user', 500);
    }
  }

  public function updateUser(Request $request, Response $response, $args): ResponseInterface
  {
    try {
      $data = $request->getParsedBody();
      $data['user_id'] = $args['id'];

      $updated = $this->userModel->updateUser($data);

      if ($updated) {
        return ResponseHelper::success($response, [], 'User updated successfully');
      } else {
        return ResponseHelper::error($response, 'Failed to update user', 400);
      }
    } catch (\Exception $e) {
      return ResponseHelper::error($response, 'Failed to update user', 500);
    }
  }

  public function deleteUser(Request $request, Response $response, $args): ResponseInterface
  {
    try {
      $userId = $args['id'];
      $deleted = $this->userModel->deleteUser($userId);

      if ($deleted) {
        return ResponseHelper::success($response, [], 'User deleted successfully');
      } else {
        return ResponseHelper::error($response, 'Failed to delete user', 400);
      }
    } catch (\Exception $e) {
      return ResponseHelper::error($response, 'Failed to delete user', 500);
    }
  }

    public function verifiedRegistration(Request $request, Response $response, $args): ResponseInterface
    {
        try {
            $data = $request->getParsedBody();
            $data['user_id'] = $args['id'];
            $data['user_isverified'] = 1;

            $verified = $this->userModel->verifiedRegistration($data);

            if ($verified) {
                return ResponseHelper::success($response, [], 'User registration verified successfully');
            } else {
                return ResponseHelper::error($response, 'Failed to verify registration', 400);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Failed to verify registration', 500);
        }
    }

}