<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AdminController
{
	private $userModel;

	public function __construct()
	{
		$this->userModel = new User();
	}

    public function getUsers(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $search = (string) ($request->getQueryParams()['search'] ?? '');
        $offset = ($page - 1) * $limit;

        $departments = $this->userModel->getAll($limit, $offset, $search);
        $totalDepartments = 0;

        if (empty($search) || $search === '') {
            $totalDepartments = $this->userModel->getTotalCount();
        } else {
            $totalDepartments = count($departments);
        }
        
        $totalPages = ceil($totalDepartments / $limit);

        if (empty($departments)) {
            return ResponseHelper::error($response, 'No departments found', 404);
        }

        return ResponseHelper::withPagination(
            $response,
            $departments,
            $page,
            $totalPages,
            $totalDepartments,
            $limit
        );
    }

	public function getUserById(Request $request, Response $response, $args): ResponseInterface {
		try {
			$userId = $args['id'];
			$user = $this->userModel->getById($userId);
			if ($user) {
				return ResponseHelper::success($response, $user, 'User retrieved successfully');
			} else {
				return ResponseHelper::error($response, 'User not found', 404);
			}
		} catch (\Exception $e) {
			return ResponseHelper::error($response, 'Failed to retrieve user', 500);
		}
	}

	public function updateUser(Request $request, Response $response, $args): ResponseInterface {
		try {
			$data = $request->getParsedBody();
			$data['user_id'] = $args['id'];

			$action = $this->userModel->update($data);

			if ($action) {
				return ResponseHelper::success($response, [], 'User updated successfully');
			} else {
				return ResponseHelper::error($response, 'Failed to update user', 400);
			}
		} catch (\Exception $e) {
			return ResponseHelper::error($response, 'Failed to update user', 500);
		}
	}

	public function deleteUser(Request $request, Response $response, $args): ResponseInterface {
		try {
			$userId = $args['id'];
			$action = $this->userModel->delete($userId);

			if ($action) {
				return ResponseHelper::success($response, [], 'User deleted successfully');
			} else {
				return ResponseHelper::error($response, 'Failed to delete user', 400);
			}
		} catch (\Exception $e) {
			return ResponseHelper::error($response, 'Failed to delete user', 500);
		}
	}

	public function verifiedRegistration(Request $request, Response $response, $args): ResponseInterface {
		try {
			$data = $request->getParsedBody();
			$data['user_id'] = $args['id'];
			$data['user_isverified'] = 1;

			$action = $this->userModel->verifiedRegistration($data);

			if ($action) {
				return ResponseHelper::success($response, [], 'User registration verified successfully');
			} else {
				return ResponseHelper::error($response, 'Failed to verify registration', 400);
			}
		} catch (\Exception $e) {
			return ResponseHelper::error($response, 'Failed to verify registration', 500);
		}
	}
}
