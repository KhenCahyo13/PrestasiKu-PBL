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

    public function index(Request $request, Response $response): Response {
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $search = (string) ($request->getQueryParams()['search'] ?? '');
        $offset = ($page - 1) * $limit;
    
        // Fetch raw achievements data
        $achievements = $this->achievementModel->getAll($limit, $offset, $search);
        $totalAchievements = $this->achievementModel->getTotalCount($search);
        $totalPages = ceil($totalAchievements / $limit);
    
        $groupedAchievements = array();
        foreach ($achievements as $achievement) {
            $achievementId = $achievement['achievement_id'];
    
            if (!isset($groupedAchievements[$achievementId])) {
                $groupedAchievements[$achievementId] = array(
                    'achievement_id' => $achievement['achievement_id'],
                    'achievement_title' => $achievement['achievement_title'],
                    'achievement_description' => $achievement['achievement_description'],
                    'achievement_type' => $achievement['achievement_type'],
                    'achievement_scope' => $achievement['achievement_scope'],
                    'achievement_eventlocation' => $achievement['achievement_eventlocation'],
                    'achievement_eventcity' => $achievement['achievement_eventcity'],
                    'achievement_eventstart' => $achievement['achievement_eventstart'],
                    'achievement_eventend' => $achievement['achievement_eventend'],
                    'achievement_createdat' => $achievement['achievement_createdat'],
                    'achievement_updatedat' => $achievement['achievement_updatedat'],
                    'achievement_verification' => null,
                );
            }
    
            if ($groupedAchievements[$achievementId]['achievement_verification'] === null) {
                $groupedAchievements[$achievementId]['achievement_verification'] = array(
                    'verification_id' => $achievement['verification_id'],
                    'achievement_id' => $achievement['achievement_id'],
                    'verification_code' => $achievement['verification_code'],
                    'verification_status' => $achievement['verification_status'],
                    'verification_isdone' => $achievement['verification_isdone'],
                    'verification_notes' => $achievement['verification_notes'],
                    'verification_createdat' => $achievement['verification_createdat'],
                    'verification_updatedat' => $achievement['verification_updatedat'],
                );
            }
        }
    
        $groupedAchievements = array_values($groupedAchievements);
    
        return ResponseHelper::withPagination(
            $response,
            $groupedAchievements,
            $page,
            $totalPages,
            $totalAchievements,
            $limit,
            'Successfully retrieved achievements data.'
        );
    }

    public function show(Request $request, Response $response, array $args): Response {
        $achievementId = $args['id'];
        $achievements = $this->achievementModel->getById($achievementId);
    
        if (!$achievements) {
            return ResponseHelper::error($response, 'Achievement not found.', 404);
        }

        $achievement_approvers = [];
        $achievement_files = [];
        $achievement_category_details = [];

        foreach ($achievements as $achievementRow) {
            $approver = array(
                'approver_id' => $achievementRow['approver_user_id'],
                'approver_name' => $achievementRow['approver_username'] === 'admin' ? 'Admin' : $achievementRow['lecturer_name'],
                'approver_nip' => $achievementRow['lecturer_nip'],
                'approver_email' => $achievementRow['lecturer_email'],
                'approver_phonenumber' => $achievementRow['lecturer_phonenumber'],
            );

            if (!in_array($approver, $achievement_approvers)) {
                $achievement_approvers[] = $approver;
            }

            $file = array(
                'file_id' => $achievementRow['file_id'],
                'file_title' => $achievementRow['file_title'],
                'file_path' => $achievementRow['file_path'],
            );

            if (!in_array($file, $achievement_files)) {
                $achievement_files[] = $file;
            }

            $category = array(
                'category_id' => $achievementRow['category_id'],
                'category_name' => $achievementRow['category_name'],
            );

            if (!in_array($category, $achievement_category_details)) {
                $achievement_category_details[] = $category;
            }
        }

        $achievement = array(
            'achievement_id' => $achievements[0]['achievement_id'],
            'achievement_title' => $achievements[0]['achievement_title'],
            'achievement_description' => $achievements[0]['achievement_description'],
            'achievement_type' => $achievements[0]['achievement_type'],
            'achievement_scope' => $achievements[0]['achievement_scope'],
            'achievement_eventlocation' => $achievements[0]['achievement_eventlocation'],
            'achievement_eventcity' => $achievements[0]['achievement_eventcity'],
            'achievement_eventstart' => $achievements[0]['achievement_eventstart'],
            'achievement_eventend' => $achievements[0]['achievement_eventend'],
            'achievement_createdat' => $achievements[0]['achievement_createdat'],
            'achievement_updatedat' => $achievements[0]['achievement_updatedat'],
            'student_id' => $achievements[0]['student_user_id'],
            'student_name' => $achievements[0]['student_name'],
            'student_nim' => $achievements[0]['student_nim'],
            'student_email' => $achievements[0]['student_email'],
            'student_phonenumber' => $achievements[0]['student_phonenumber'],
            'achievement_verification' => array(
                'verification_id' => $achievements[0]['verification_id'],
                'verification_code' => $achievements[0]['verification_code'],
                'verification_status' => $achievements[0]['verification_status'],
                'verification_isdone' => $achievements[0]['verification_isdone'],
                'verification_notes' => $achievements[0]['verification_notes'],
            ),
            'achievement_approvers' => $achievement_approvers,
            'achievement_files' => $achievement_files,
            'achievement_category_details' => $achievement_category_details,
        );

        return ResponseHelper::success($response, $achievement, 'Successfully get achievement.');
    }    
    
    public function getApproverList(Request $request, Response $response, array $args): Response {
        $achievementId = $args['id'];
        $achievementApprovers = $this->achievementApproverModel->getApproversByAchievementId($achievementId);

        if (!$achievementApprovers) {
            return ResponseHelper::error($response, 'Achievement approvers not found.', 404);
        }

        return ResponseHelper::success($response, $achievementApprovers, 'Successfully get achievement approvers.');
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
        $achievementApproversData[] = array(
            'achievement_id' => $achievementId,
            'user_id' => 'a09db47c-cc16-4750-aee6-7c41ecc03488',
        );

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
