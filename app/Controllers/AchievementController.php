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
            $responseData = [
                'success' => false,
                'message' => 'Missing required fields: user_id, achievement_title, or achievement_description.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $approvers = isset($data['approvers']) ? $data['approvers'] : [];

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
        $data['verification_notes'] = $data['verification_notes'] ?? 'Menunggu';

        try {
            $result = $this->achievementModel->create($data);

            if ($result) {
                $responseData = [
                    'success' => true,
                    'message' => 'Achievement created successfully!',
                    'data' => $data
                ];
                $statusCode = 201;
            } else {
                $responseData = [
                    'success' => false,
                    'message' => 'Failed to create achievement.'
                ];
                $statusCode = 500;
            }
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
            $statusCode = 500;
        }

        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
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
            $responseData = [
                'success' => false,
                'message' => 'user_id is required.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $pendingAchievements = $this->achievementModel->getPendingAchievementsByApprover($userId);

            if (empty($pendingAchievements)) {
                $responseData = [
                    'success' => false,
                    'message' => 'No pending achievements found.'
                ];
                $statusCode = 404;
            } else {
                $responseData = [
                    'success' => true,
                    'message' => 'Pending achievements retrieved successfully.',
                    'data' => $pendingAchievements
                ];
                $statusCode = 200;
            }

            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }


    public function getApprovedAchievements(Request $request, Response $response, array $args): ResponseInterface
    {
        $userId = $args['id'] ?? null;

        if (empty($userId)) {
            $responseData = [
                'success' => false,
                'message' => 'user_id is required.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $approvedAchievements = $this->achievementModel->getApprovedAchievementsByApprover($userId);

            if (empty($approvedAchievements)) {
                $responseData = [
                    'success' => false,
                    'message' => 'No ap achievements found.'
                ];
                $statusCode = 404;
            } else {
                $responseData = [
                    'success' => true,
                    'message' => 'Approved achievements retrieved successfully.',
                    'data' => $approvedAchievements
                ];
                $statusCode = 200;
            }

            $responseData = [
                'success' => true,
                'message' => 'Approved achievements retrieved successfully.',
                'data' => $approvedAchievements
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }


    public function approveAchievement(Request $request, Response $response, array $args): ResponseInterface
    {
        $userId = $args['id'];
        $data = $request->getParsedBody();

        if (empty($data['achievement_id']) || empty($data['action'])) {
            $responseData = [
                'success' => false,
                'message' => 'Missing required fields: achievement_id or action.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $data['user_id'] = $userId;

        if (empty($userId)) {
            $responseData = [
                'success' => false,
                'message' => 'Missing required field: user_id.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $result = $this->achievementModel->processApproveAchievement($data);

            if ($result) {
                $responseData = [
                    'success' => true,
                    'message' => 'Achievement approval process successful.',
                ];
                $statusCode = 200;
            } else {
                $responseData = [
                    'success' => false,
                    'message' => 'Failed to process achievement approval.',
                ];
                $statusCode = 500;
            }
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
            $statusCode = 500;
        }

        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

    public function getNotifications(Request $request, Response $response, array $args): ResponseInterface
    {
        $userId = $args['id'] ?? null;

        if (empty($userId)) {
            $responseData = [
                'success' => false,
                'message' => 'user_id is required.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $notifications = $this->achievementModel->getNotificationsByUserId($userId);

            $responseData = [
                'success' => true,
                'message' => 'Notifications retrieved successfully.',
                'data' => $notifications
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deleteAchievement(Request $request, Response $response, array $args): ResponseInterface
    {
        $achievementId = $args['id'] ?? null;

        if (empty($achievementId)) {
            $responseData = [
                'success' => false,
                'message' => 'Achievement ID is required.'
            ];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $result = $this->achievementModel->deleteAchievement($achievementId);

            if ($result) {
                $responseData = [
                    'success' => true,
                    'message' => 'Achievement deleted successfully.'
                ];
                $statusCode = 200;
            } else {
                $responseData = [
                    'success' => false,
                    'message' => 'Failed to delete achievement.'
                ];
                $statusCode = 500;
            }
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
            $statusCode = 500;
        }

        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

}
