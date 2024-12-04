<?php
namespace App\Controllers;

use PDO;
use PDOException;
use Exception;
use App\Helpers\ResponseHelper;
use App\Models\SPClass;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class SPClassController extends Controller {
    private SPClass $spClassModel;

    public function __construct() {
        $this->spClassModel = new SPClass();
    }

    public function index(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $offset = ($page - 1) * $limit;

        $spClasses = $this->spClassModel->getAll($limit, $offset);
        $totalSpClasses = $this->spClassModel->getTotalCount();;
        $totalPages = ceil($totalSpClasses / $limit);

        if (empty($spClasses)) {
            return ResponseHelper::error(
                $response,
                'Sp classes data is empty.',
                200
            );
        }

        return ResponseHelper::withPagination(
            $response,
            $spClasses,
            $page,
            $totalPages,
            $totalSpClasses,
            $limit,
            'Successfully get sp classes data.'
        );
    }

    public function show(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
        $spClasses = $this->spClassModel->getById($id);

        if (!$spClasses) {
            return ResponseHelper::error(
                $response,
                'SP class not found.',
                404
            );
        }

        return ResponseHelper::success(
            $response,
            $spClasses,
            'Successfully get sp class.'
        );
    }

    public function store(Request $request, Response $response): Response {
        $data = json_decode($request->getBody(), true);

        $studyProgramId = $data['studyprogram_id'] ?? '';
        $spClassName = $data['spclass_name'] ?? '';

        if (empty($studyProgramId)) {
            return ResponseHelper::error(
                $response,
                'Study program is required!',
                400
            );
        }

        if (empty($spClassName)) {
            return ResponseHelper::error(
                $response,
                'Sp class name is required!',
                400
            );
        }

        $insertData = [
            'studyprogram_id' => $studyProgramId,
            'spclass_name' => $spClassName
        ];

        $action = $this->spClassModel->create($insertData);

        if (!$action) {
            return ResponseHelper::error(
                $response,
                'Failed when create sp class.',
                500
            );
        }

        return ResponseHelper::success(
            $response,
            $insertData,
            'Successfully created sp class.',
            201
        );
    }

    public function update(Request $request, Response $response, array $args): Response {
        $spClassId = $args['id'] ?? null;

        if (!$spClassId) {
            return ResponseHelper::error(
                $response,
                'sp class id is required!',
                400
            );
        }

        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        if (!is_array($data)) {
            return ResponseHelper::error(
                $response,
                'Invalid JSON format!',
                400
            );
        }

        if (empty($data['studyprogram_id'])) {
            return ResponseHelper::error(
                $response,
                'study program id is required!',
                400
            );
        }

        if (empty($data['spclass_name'])) {
            return ResponseHelper::error(
                $response,
                'sp class name is required!',
                400
            );
        }

        $data['spclass_id'] = $spClassId;
        $action = $this->spClassModel->update($data);

        if (!$action) {
            return ResponseHelper::error(
                $response,
                'Failed when update sp class.',
                500
            );
        }

        return ResponseHelper::success(
            $response,
            array(),
            'Successfully updated sp class.'
        );
    }

    public function delete(Request $request, Response $response, array $args): Response {
        $spClassId = $args['id'];

        $action = $this->spClassModel->delete($spClassId);

        if (!$action) {
            return ResponseHelper::error(
                $response,
                'Failed when delete sp class.',
                500
            );
        }

        return ResponseHelper::success(
            $response,
            array(),
            'Successfully deleted sp class.'
        );
    }
}