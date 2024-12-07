<?php

namespace App\Controllers;

use App\Models\Achievement;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;

class AchievementController
{
    private $achievementModel;

    public function __construct()
    {
        $this->achievementModel = new Achievement();
    }

    public function createAchievement(Request $request, Response $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        if (empty($data['user_id']) || empty($data['achievement_title']) || empty($data['achievement_description'])) {
            return ResponseHelper::error($response, 'Missing required fields: user_id, achievement_title, or achievement_description.', 400);
        }

        $approvers = $data['approvers'] ?? [];

        $adminApprover = [
            'user_id' => '2CC961F5-A938-426C-A81D-68D909ACC792',
        ];

        $approvers[] = $adminApprover;

        $files = [];
        if (!empty($uploadedFiles['files'])) {
            foreach ($uploadedFiles['files'] as $file) {
                $filePath = $this->uploadFile($file, $data['user_id']);
                if ($filePath) {
                    $files[] = [
                        'file_title' => $file->getClientFilename(),
                        'file_description' => 'Uploaded file for achievement',
                        'file_path' => $filePath
                    ];
                }
            }
        }

        $data['approvers'] = $approvers;
        $data['files'] = $files;
        $data['achievement_type'] = $data['achievement_type'] ?? 'Individual';
        $data['achievement_event_location'] = $data['achievement_event_location'] ?? '';
        $data['achievement_event_city'] = $data['achievement_event_city'] ?? '';
        $data['achievement_scope'] = $data['achievement_scope'] ?? 'Regional';
        $data['verification_code'] = $data['verification_code'] ?? 'MP';
        $data['verification_status'] = $data['verification_status'] ?? 'Pending';
        $data['verification_notes'] = $data['verification_notes'] ?? 'Pending';

        try {
            $result = $this->achievementModel->create($data);

            if ($result) {
                return ResponseHelper::success($response, $data, 'Achievement created successfully!', 201);
            } else {
                return ResponseHelper::error($response, 'Failed to create achievement.', 500);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Error: ' . $e->getMessage(), 500);
        }
    }

    private function uploadFile($file, $userId)
    {
        $directory = __DIR__ . '/../../public/uploads/' . $userId;

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filename = $file->getClientFilename();
        $filePath = $directory . '/' . $filename;

        try {
            $file->moveTo($filePath);
            return '/uploads/' . $userId . '/' . $filename;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPendingAchievements(Request $request, Response $response, array $args): ResponseInterface
    {
        $userId = $args['id'] ?? null;

        if (empty($userId)) {
            return ResponseHelper::error($response, 'user_id is required.', 400);
        }

        try {
            $pendingAchievements = $this->achievementModel->getPendingAchievementsByApprover($userId);

            if (empty($pendingAchievements)) {
                return ResponseHelper::error($response, 'No pending achievements found.', 404);
            }

            return ResponseHelper::success($response, $pendingAchievements, 'Pending achievements retrieved successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Error: ' . $e->getMessage(), 500);
        }
    }

    public function getApprovedAchievements(Request $request, Response $response, array $args): ResponseInterface
    {
        $userId = $args['id'] ?? null;

        if (empty($userId)) {
            return ResponseHelper::error($response, 'user_id is required.', 400);
        }

        try {
            $approvedAchievements = $this->achievementModel->getApprovedAchievementsByApprover($userId);

            if (empty($approvedAchievements)) {
                return ResponseHelper::error($response, 'No approved achievements found.', 404);
            }

            return ResponseHelper::success($response, $approvedAchievements, 'Approved achievements retrieved successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Error: ' . $e->getMessage(), 500);
        }
    }

    public function approveAchievement(Request $request, Response $response, array $args): ResponseInterface
    {
        $userId = $args['id'];
        $data = $request->getParsedBody();

        if (empty($data['achievement_id']) || empty($data['action'])) {
            return ResponseHelper::error($response, 'Missing required fields: achievement_id or action.', 400);
        }

        $data['user_id'] = $userId;

        if (empty($userId)) {
            return ResponseHelper::error($response, 'Missing required field: user_id.', 400);
        }

        try {
            $result = $this->achievementModel->processApproveAchievement($data);

            if ($result) {
                return ResponseHelper::success($response, [], 'Achievement approval process successful.');
            } else {
                return ResponseHelper::error($response, 'Failed to process achievement approval.', 500);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Error: ' . $e->getMessage(), 500);
        }
    }

    public function deleteAchievement(Request $request, Response $response, array $args): ResponseInterface
    {
        $achievementId = $args['id'] ?? null;

        if (empty($achievementId)) {
            return ResponseHelper::error($response, 'Achievement ID is required.', 400);
        }

        try {
            $result = $this->achievementModel->deleteAchievement($achievementId);

            if ($result) {
                return ResponseHelper::success($response, [], 'Achievement deleted successfully.');
            } else {
                return ResponseHelper::error($response, 'Failed to delete achievement.', 500);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Error: ' . $e->getMessage(), 500);
        }
    }
}
