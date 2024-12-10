<?php
namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;
use App\Models\AchievementCategory;

class AchievementCategoryController extends Controller {
    private AchievementCategory $achievementCategoryModel;
    public function __construct() {
        $this->achievementCategoryModel = new AchievementCategory();
    }

    public function index(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $search = (string) ($request->getQueryParams()['search'] ?? '');
        $offset = ($page - 1) * $limit;

        $categories = $this->achievementCategoryModel->getAll($limit, $offset, $search);
        $totalCategories = 0;

        if (empty($search) || $search === '') {
            $totalCategories = $this->achievementCategoryModel->getTotalCount();
        } else {
            $totalCategories = count($categories);
        }
        
        $totalPages = ceil($totalCategories / $limit);

        if (empty($categories)) {
            return ResponseHelper::error($response, 'No roles found.', 404);
        }

        return ResponseHelper::withPagination(
            $response,
            $categories,
            $page,
            $totalPages,
            $totalCategories,
            $limit,
            'Successfully get roles data.'
        );
    }
}