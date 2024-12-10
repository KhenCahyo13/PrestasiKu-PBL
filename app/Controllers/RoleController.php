<?php
namespace App\Controllers;

use App\Models\Role;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;

class RoleController extends Controller {
    private Role $roleModel;
    public function __construct() {
        $this->roleModel = new Role();
    }

    public function index(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $search = (string) ($request->getQueryParams()['search'] ?? '');
        $offset = ($page - 1) * $limit;

        $roles = $this->roleModel->getAll($limit, $offset, $search);
        $totalRoles = 0;

        if (empty($search) || $search === '') {
            $totalRoles = $this->roleModel->getTotalCount();
        } else {
            $totalRoles = count($roles);
        }
        
        $totalPages = ceil($totalRoles / $limit);

        if (empty($roles)) {
            return ResponseHelper::error($response, 'No roles found.', 404);
        }

        return ResponseHelper::withPagination(
            $response,
            $roles,
            $page,
            $totalPages,
            $totalRoles,
            $limit,
            'Successfully get roles data.'
        );
    }
}