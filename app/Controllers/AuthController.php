<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Role;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Ramsey\Uuid\Uuid;

class AuthController
{
	private $userModel;
	private $roleModel;

	public function __construct() {
		$this->userModel = new User();
		$this->roleModel = new Role();
	}

	public function register(Request $request, Response $response): ResponseInterface {
		$data = json_decode($request->getBody(), true);
		$detailsId = Uuid::uuid4();

		// Check Password Pattern
		$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
		if (!preg_match($passwordPattern, $data['user_password'])) {
			return ResponseHelper::error(
				$response,
				'Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.',
				400
			);
		}

		// Check if username already exists
		$existingUser = $this->userModel->getByUsername($data['user_username']);
		if ($existingUser) {
			return ResponseHelper::error(
				$response,
				'Username already exists.',
				400
			);
		}

		// Check role
		$role = $this->roleModel->getById($data['role_id']);
		if (!$role) {
			return ResponseHelper::error(
				$response,
				'Role not found.',
				400
			);
		}

		$results = false;
		$userData = array(
			'role_id' => $data['role_id'],
			'user_username' => $data['user_username'],
			'user_password' => password_hash($data['user_password'], PASSWORD_DEFAULT),
		);

		if ($role['role_name'] == 'Student') {
			$userData['details_student_id'] = $detailsId->toString();
			$studentDetailsData = array(
				'detail_id' => $detailsId->toString(),
				'spclass_id' => $data['spclass_id'],
				'detail_name' => $data['detail_name'],
				'detail_nim' => $data['detail_nim'],
				'detail_dateofbirth' => $data['detail_dateofbirth'],
				'detail_phonenumber' => $data['detail_phonenumber'],
				'detail_email' => $data['detail_email'],
			);
			$results = $this->userModel->createStudent($userData, $studentDetailsData);
		} else if ($role['role_name'] == 'Lecturer') {
			$userData['details_lecturer_id'] = $detailsId->toString();
			$lecturerDetailsData = array(
				'detail_id' => $detailsId->toString(),
				'department_id' => $data['department_id'],
				'detail_name' => $data['detail_name'],
				'detail_nip' => $data['detail_nip'],
				'detail_phonenumber' => $data['detail_phonenumber'],
				'detail_email' => $data['detail_email'],
			);
			$results = $this->userModel->createLecture($userData, $lecturerDetailsData);
		}

		if ($results) {
			return ResponseHelper::success(
				$response,
				array(),
				'Successfully register.',
				201
			);
		} else {
			return ResponseHelper::error(
				$response,
				'Failed to register.',
				400
			);
		}
	}

	public function login(Request $request, Response $response): ResponseInterface {
		$body = $request->getBody();
		$data = json_decode($body, true);

		if (!empty($data['user_username']) || !empty($data['user_password'])) {
			$user = $this->userModel->getByUsername($data['user_username']);

			if ($user) {
				if (password_verify($data['user_password'], $user['user_password'])) {
					if ($user['user_isverified'] == 1) {
						$_SESSION['user'] = [
							'id' => $user['user_id'],
							'username' => $user['user_username'],
							'role' => $user['role_name']
						];

						return ResponseHelper::success(
							$response,
							$user,
							'Successfully login.',
							200
						);
					} else {
						return ResponseHelper::error(
							$response,
							'Your account is not verified yet.',
							400
						);
					}
				} else {
					return ResponseHelper::error(
						$response,
						'Username or password is incorrect.',
						400
					);
				}
			} else {
				return ResponseHelper::error(
					$response,
					'Username or password is incorrect.',
					400
				);
			}
		} else {
			return ResponseHelper::error(
				$response,
				'Username or password is required',
				400
			);
		}
	}


	public function logout(Request $request, Response $response): ResponseInterface {
		if (!isset($_SESSION['user'])) {
			return ResponseHelper::error(
				$response,
				'You are not logged in.',
				400
			);
		}

		unset($_SESSION['user']);
		session_destroy();

		return ResponseHelper::success(
			$response,
			array(),
			'Successfully logged out.',
			200
		);
	}
}
