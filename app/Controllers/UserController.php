<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserController extends Controller {
	private $userModel;

	public function __construct() {
		$this->userModel = new User();
	}

    public function index(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $search = (string) ($request->getQueryParams()['search'] ?? '');
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getAll($limit, $offset, $search);
        $totalUsers = 0;

        if (empty($search) || $search === '') {
            $totalUsers = $this->userModel->getTotalCount();
        } else {
            $totalUsers = count($users);
        }
        
        $totalPages = ceil($totalUsers / $limit);

        if (empty($users)) {
            return ResponseHelper::error($response, 'No users found', 404);
        }

        return ResponseHelper::withPagination(
            $response,
            $users,
            $page,
            $totalPages,
            $totalUsers,
            $limit,
            'Successfully get users data.'
        );
    }

	public function show(Request $request, Response $response, $args): ResponseInterface {
        $userId = $args['id'];
        $user = $this->userModel->getById($userId);

        if ($user) {
            return ResponseHelper::success($response, $user, 'Successfully get user data by id.');
        } else {
            return ResponseHelper::error($response, 'Failed when get user data by id.', 400);
        }
	}

	public function verifyUserRegistration(Request $request, Response $response, $args): ResponseInterface {
		try {
			$data = $request->getParsedBody();
			$verificationAction = (string) ($request->getQueryParams()['action'] ?? '');
			$data['user_id'] = $args['id'];
			$data['user_isverified'] = null;

			if ($verificationAction === '') {
				return ResponseHelper::error($response, 'You must send the action value to verify this user!', 400);
			} else {
				if ($verificationAction === 'reject') {
					$data['user_isverified'] = 0;
				} else if ($verificationAction === 'approve') {
					$data['user_isverified'] = 1;
				}
			}

			$action = $this->userModel->verifiedRegistration($data);

			if ($action) {
				return ResponseHelper::success($response, [], 'Successfully verified user registration.');
			} else {
				return ResponseHelper::error($response, 'Failed to verify registration.', 400);
			}
		} catch (\Exception $e) {
			return ResponseHelper::error($response, 'Failed to verify registration.', 500);
		}
	}
}
