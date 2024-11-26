<?php

namespace App\Controllers;

use App\Models\Department;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;

class DepartmentController
{
  private $departmentModel;

  public function __construct()
  {
    $this->departmentModel = new Department();
  }

  public function getDepartments(Request $request, Response $response): Response
  {
    $page = (int) ($request->getQueryParams()['page'] ?? 1);
    $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
    $offset = ($page - 1) * $limit;

    $departments = $this->departmentModel->getAll($limit, $offset);
    $totalDepartments = count($this->departmentModel->getAll());
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

  public function getDepartmentById(Request $request, Response $response, array $args): Response
  {
    $id = $args['id'];
    $department = $this->departmentModel->getById($id);

    if (!$department) {
      return ResponseHelper::error($response, 'Department not found', 404);
    }

    return ResponseHelper::success($response, $department, 'Department fetched successfully');
  }

  public function createDepartment(Request $request, Response $response): Response
  {
    $data = json_decode($request->getBody(), true);

    $departmentName = $data['department_name'] ?? '';

    if (empty($departmentName)) {
      return ResponseHelper::error($response, 'Department name is required!', 400);
    }

    $insertData = [
      'department_name' => $departmentName,
    ];

    $action = $this->departmentModel->create($insertData);

    if (!$action) {
      return ResponseHelper::error($response, 'Failed to create department', 500);
    }

    return ResponseHelper::success($response, $insertData, 'Department created successfully', 201);
  }

  public function updateDepartment(Request $request, Response $response, array $args): Response
  {
    $id = $args['id'] ?? null;

    if (!$id) {
      return ResponseHelper::error($response, 'Department ID is required!', 400);
    }

    $body = $request->getBody()->getContents();
    $data = json_decode($body, true);

    if (!is_array($data)) {
      return ResponseHelper::error($response, 'Invalid JSON format!', 400);
    }

    if (empty($data['department_name'])) {
      return ResponseHelper::error($response, 'Department name is required!', 400);
    }

    $data['department_id'] = $id;
    $result = $this->departmentModel->update($data);

    if (!$result) {
      return ResponseHelper::error($response, 'Failed to update department', 500);
    }

    return ResponseHelper::success($response, [], 'Department updated successfully');
  }

  public function deleteDepartment(Request $request, Response $response, array $args): Response
  {
    $id = $args['id'];

    $result = $this->departmentModel->delete($id);

    if (!$result) {
      return ResponseHelper::error($response, 'Failed to delete department', 500);
    }

    return ResponseHelper::success($response, [], 'Department deleted successfully');
  }
}
