<?php

namespace App\Controllers;

use App\Models\Achievement;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;
use App\Helpers\UploadFileHelper;
use App\Models\AchievementApprover;
use App\Models\AchievementCategoryDetails;
use App\Models\AchievementFile;
use App\Models\AchievementVerification;
use App\Models\Model;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\UploadedFile;

class AchievementController extends Controller {
    private $baseModel;
    private $achievementModel;
    private $achievementApproverModel;
    private $achievementFileModel;
    private $achievementCategoryDetailsModel;
    private $achievementVerificationModel;

    public function __construct() {
        $this->baseModel = new Model();
        $this->achievementModel = new Achievement();
        $this->achievementApproverModel = new AchievementApprover();
        $this->achievementFileModel = new AchievementFile();
        $this->achievementCategoryDetailsModel = new AchievementCategoryDetails();
        $this->achievementVerificationModel = new AchievementVerification();
    }

    public function store(Request $request, Response $response): ResponseInterface {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $achievementId = Uuid::uuid4()->toString();
        $userId = $_SESSION['user']['id'];

        $achievementData = array(
            'achievement_id' => $achievementId,
            'user_id' => $userId,
            'achievement_title' => $data['achievement_title'],
            'achievement_description' => $data['achievement_description'],
            'achievement_type' => $data['achievement_type'],
            'achievement_eventlocation' => $data['achievement_eventlocation'],
            'achievement_eventcity' => $data['achievement_eventcity'],
            'achievement_eventstart' => $data['achievement_eventstart'],
            'achievement_eventend' => $data['achievement_eventend'],
            'achievement_scope' => $data['achievement_scope'],
        );

        $achievementApproversData = array();
        foreach ($data['approvers'] as $approver) {
            $achievementApproversData[] = array(
                'achievement_id' => $achievementId,
                'user_id' => $approver['user_id'],
            );
        }

        $achievementFilesData = array();
        foreach ($uploadedFiles['files'] as $index => $file) {
            if ($file instanceof UploadedFile) {
                $uploadFile = UploadFileHelper::upload($file, $userId, $index === 0 ? 'Certificate' : 'Assignment');
        
                if ($uploadFile['success']) {
                    $achievementFilesData[] = array(
                        'achievement_id' => $achievementId,
                        'file_title' => $uploadFile['data']['filename'],
                        'file_path' => $uploadFile['data']['filepath']
                    );
                }
            } else {
                return ResponseHelper::error($response, 'Invalid file format.', 400);
            }
        }

        $achievementCategoriesData = array();
        foreach ($data['categories'] as $category) {
            $achievementCategoriesData[] = array(
                'achievement_id' => $achievementId,
                'category_id' => $category['category_id'],
            );
        }

        $achievementVerificationData = array(
            'achievement_id' => $achievementId,
            'verification_code' => 'MP',
            'verification_status' => 'Menunggu Persetujuan',
        );

        try {
            $this->baseModel->getDbConnection()->beginTransaction();
            // Transactions
            $this->achievementModel->create($achievementData);

            foreach ($achievementApproversData as $approver) {
                $this->achievementApproverModel->create($approver);
            }

            foreach ($achievementFilesData as $file) {
                $this->achievementFileModel->create($file);
            }

            foreach ($achievementCategoriesData as $category) {
                $this->achievementCategoryDetailsModel->create($category);
            }

            $this->achievementVerificationModel->create($achievementVerificationData);
            // End of Transactions
            $this->baseModel->getDbConnection()->commit();

            return ResponseHelper::success($response, [], 'Successfully created achievement.');
        } catch (\Exception $e) {
            $this->baseModel->getDbConnection()->rollBack();
            return ResponseHelper::error($response, $e->getMessage(), 500);
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

    public function getAchievementScopePercentage(Request $request, Response $response): ResponseInterface
    {
        try {
            $scopeData = $this->achievementModel->getAchievementScopeCounts();

            $totalAchievements = array_sum(array_column($scopeData, 'count'));

            if ($totalAchievements == 0) {
                return ResponseHelper::error($response, 'No achievements found.', 404);
            }

            $scopePercentage = array_map(function ($scope) use ($totalAchievements) {
                $scope['percentage'] = ($scope['count'] / $totalAchievements) * 100;
                return $scope;
            }, $scopeData);

            return ResponseHelper::success($response, $scopePercentage, 'Achievement scope percentages retrieved successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::error($response, 'Error: ' . $e->getMessage(), 500);
        }
    }
}
