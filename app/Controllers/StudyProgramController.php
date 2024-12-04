<?php

namespace App\Controllers;

use App\Models\StudyProgram;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;

class StudyProgramController extends Controller {
    private StudyProgram $studyProgramModel;
    public function __construct() {
        $this->studyProgramModel = new StudyProgram();
    }

    public function index(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $offset = ($page - 1) * $limit;

        $studyPrograms = $this->studyProgramModel->getAll($limit, $offset);
        $totalStudyPrograms = $this->studyProgramModel->getTotalCount();;
        $totalPages = ceil($totalStudyPrograms / $limit);

        if (empty($studyPrograms)) {
            return ResponseHelper::error(
                $response,
                'Study programs data is empty.',
                200
            );
        }

        return ResponseHelper::withPagination(
            $response,
            $studyPrograms,
            $page,
            $totalPages,
            $totalStudyPrograms,
            $limit,
            'Successfully get study programs data.'
        );
    }

    public function show(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
        $studyProgram = $this->studyProgramModel->getById($id);

        if (!$studyProgram) {
            return ResponseHelper::error(
                $response,
                'Study program not found',
                404
            );
        }

        return ResponseHelper::success(
            $response,
            $studyProgram,
            'Successfully get study program.'
        );
    }

    public function store(Request $request, Response $response): Response {
        $data = json_decode($request->getBody(), true);

        $departmentId = $data['department_id'] ?? '';
        $studyProgramName = $data['studyprogram_name'] ?? '';

        if (empty($departmentId)) {
            return ResponseHelper::error(
                $response,
                'Department name is required!',
                400
            );
        }

        if (empty($studyProgramName)) {
            return ResponseHelper::error(
                $response,
                'Study program name is required!',
                400
            );
        }

        $insertData = [
            'department_id' => $departmentId,
            'studyprogram_name' => $studyProgramName
        ];

        $action = $this->studyProgramModel->create($insertData);

        if (!$action) {
            return ResponseHelper::error(
                $response,
                'Failed when create study program',
                500
            );
        }

        return ResponseHelper::success(
            $response,
            $insertData,
            'Successfully created study program',
            201
        );
    }

    public function update(Request $request, Response $response, array $args): Response {
        $studyProgramId = $args['id'] ?? null;

        if (!$studyProgramId) {
            return ResponseHelper::error(
                $response,
                'Study program id is required!',
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

        if (empty($data['department_id'])) {
            return ResponseHelper::error(
                $response,
                'Department id is required!',
                400
            );
        }

        if (empty($data['studyprogram_name'])) {
            return ResponseHelper::error(
                $response,
                'Study program name is required!',
                400
            );
        }

        $data['studyprogram_id'] = $studyProgramId;
        $action = $this->studyProgramModel->update($data);

        if (!$action) {
            return ResponseHelper::error(
                $response,
                'Failed when update study program',
                500
            );
        }

        return ResponseHelper::success(
            $response,
            array(),
            'Successfully updated study program'
        );
    }

    public function delete(Request $request, Response $response, array $args): Response {
        $studyProgramId = $args['id'];

        $action = $this->studyProgramModel->delete($studyProgramId);

        if (!$action) {
            return ResponseHelper::error(
                $response,
                'Failed when delete study program',
                500
            );
        }

        return ResponseHelper::success(
            $response,
            array(),
            'Successfully deleted study program'
        );
    }
}
