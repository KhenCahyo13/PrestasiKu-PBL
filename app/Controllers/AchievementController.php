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
use App\Models\AchievementLog;
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
    private $achievementLogModel;

    public function __construct() {
        $this->baseModel = new Model();
        $this->achievementModel = new Achievement();
        $this->achievementApproverModel = new AchievementApprover();
        $this->achievementFileModel = new AchievementFile();
        $this->achievementCategoryDetailsModel = new AchievementCategoryDetails();
        $this->achievementVerificationModel = new AchievementVerification();
        $this->achievementLogModel = new AchievementLog();
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
        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];
        $achievements = $this->achievementModel->getById($achievementId);
    
        if (!$achievements) {
            return ResponseHelper::error($response, 'Achievement not found.', 404);
        }

        $achievement_approvers = [];
        $achievement_files = [];
        $achievement_category_details = [];

        foreach ($achievements as $achievementRow) {
            $approver = array(
                'approver_id' => $achievementRow['approver_id'],
                'user_id' => $achievementRow['approver_user_id'],
                'approver_name' => $achievementRow['approver_username'] === 'admin' ? 'Admin' : $achievementRow['lecturer_name'],
                'approver_nip' => $achievementRow['lecturer_nip'],
                'approver_email' => $achievementRow['lecturer_email'],
                'approver_phonenumber' => $achievementRow['lecturer_phonenumber'],
                'approver_isdone' => $achievementRow['approver_isdone'],
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

        $achievement_approvalaction = null;

        if ($userRole == 'Admin') {
            $allApproverApproved = true;
            $adminAlreadyApprove = true;

            foreach ($achievements as $achievementRow) {
                $isAdmin = $achievementRow['approver_username'] === 'admin';
                $isApproved = $achievementRow['approver_isdone'] == 1;

                if ($isAdmin) {
                    $adminAlreadyApprove = $isApproved;
                } elseif (!$isApproved) {
                    $allApproverApproved = false;
                    break;
                }
            }

            if (!$allApproverApproved) {
                $achievement_approvalaction = array(
                    'action_canapprove' => false,
                    'action_messagetype' => 'warning',
                    'action_message' => 'You can\'t approve before all lecturers already finish approval.',
                );
            } elseif ($adminAlreadyApprove) {
                $achievement_approvalaction = array(
                    'action_canapprove' => false,
                    'action_messagetype' => 'warning',
                    'action_message' => 'You already approve this achievement.',
                );
            } else {
                $achievement_approvalaction = array(
                    'action_canapprove' => true,
                    'action_messagetype' => 'success',
                    'action_message' => 'You can approve this achievement.',
                );
            }
        } else if ($userRole == 'Lecturer') {
            $lecturerAlreadyApprove = false;
        
            foreach ($achievements as $achievementRow) {
                if ($achievementRow['user_id'] == $userId) {
                    if ($achievementRow['approver_isdone'] == 1) {
                        $lecturerAlreadyApprove = true;
                        break;
                    }
                }
            }
        
            if (!$lecturerAlreadyApprove) {
                $achievement_approvalaction = array(
                    'action_canapprove' => true,
                    'action_messagetype' => 'success',
                    'action_message' => 'You can approve this achievement.',
                );
            } else {
                $achievement_approvalaction = array(
                    'action_canapprove' => false,
                    'action_messagetype' => 'warning',
                    'action_message' => 'You already approved this achievement.',
                );
            }
        }
        
        if ($achievements[0]['verification_isdone'] !== NULL) {
            $achievement_approvalaction = array(
                'action_canapprove' => false,
                'action_messagetype' => 'warning',
                'action_message' => 'This achievement has already been approved.',
            );
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
            'achievement_approvalaction' => $achievement_approvalaction
        );

        return ResponseHelper::success($response, $achievement, 'Successfully get achievement.');
    }    
    
    public function getApproverList(Request $request, Response $response, array $args): Response {
        $achievementId = $args['id'];
        $achievementApprovers = $this->achievementApproverModel->getByAchievementId($achievementId);

        if (!$achievementApprovers) {
            return ResponseHelper::error($response, 'Achievement approvers not found.', 404);
        }

        return ResponseHelper::success($response, $achievementApprovers, 'Successfully get achievement approvers.');
    }

    public function getHistoryLogs(Request $request, Response $response, array $args): Response {
        $achievementId = $args['id'];
        $historyLogs = $this->achievementLogModel->getByAchievementId($achievementId);

        if (!$historyLogs) {
            return ResponseHelper::error($response, 'History logs not found.', 404);
        }

        return ResponseHelper::success($response, $historyLogs, 'Successfully get achievement history logs.');
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

    public function approveAchievement(Request $request, Response $response, array $args): ResponseInterface {
        $achievementId = $args['id'];
        $userId = $_SESSION['user']['id'];
        $userFullname = $_SESSION['user']['fullname'] ?? 'Admin';
        $data = json_decode($request->getBody(), true);
        $approvalAction = (string) ($request->getQueryParams()['action'] ?? '');

        if (empty($data['approver_id'])) {
            return ResponseHelper::error($response, 'approver_id is required.', 400);
        }

        if (empty($data['verification_id'])) {
            return ResponseHelper::error($response, 'verification_id is required.', 400);
        }

        if (empty($approvalAction)) {
            return ResponseHelper::error($response, 'Approval action is required.', 400);
        } else if ($approvalAction != 'approve' && $approvalAction != 'reject') {
            return ResponseHelper::error($response, 'Invalid approval action.', 400);
        }

        if ($approvalAction == 'reject') {
            if (empty($data['reject_notes'])) {
                return ResponseHelper::error($response, 'Notes is required.', 400);
            }
        }

        $allApproversApproved = true;
        $verificationData = array();
        $approvers = $this->achievementApproverModel->getByAchievementId($achievementId);
        $approverData = array(
            'approver_id' => $data['approver_id'],
            'approver_isdone' => 1,
        );

        $userExists = !empty(array_filter($approvers, function ($approver) use ($userId) {
            return $approver['user_id'] == $userId;
        }));

        if (!$userExists) {
            return ResponseHelper::error($response, 'You are not allowed to approve this achievement.', 403);
        }

        if ($approvalAction == 'reject') {
            $verificationData = array(
                'verification_id' => $data['verification_id'],
                'verification_code' => 'DT',
                'verification_status' => 'Ditolak',
                'verification_notes' => $data['reject_notes'],
                'verification_isdone' => 0,
            );
        } else if ($approvalAction == 'approve') {
            foreach ($approvers as $approver) {
                if ($approver['user_username'] === 'admin') {
                    continue;
                }

                if ($approver['approver_isdone'] != 1) {
                    $allApproversApproved = false;
                    break;
                }
            }

            if ($allApproversApproved) {
                $verificationData = array(
                    'verification_id' => $data['verification_id'],
                    'verification_code' => 'DS',
                    'verification_status' => 'Disetujui',
                    'verification_notes' => null,
                    'verification_isdone' => 1
                );
            }

            $logData = array(
                'achievement_id' => $achievementId,
                'log_type' => $approvalAction,
                'log_message' => 'Approver ' . $userFullname . ' ' . $approvalAction . ' this achievement.',
            );
        }
        try {
            $this->baseModel->getDbConnection()->beginTransaction();
            // Transactions
            $this->achievementApproverModel->update($approverData);
            if ($approvalAction == 'reject' || $allApproversApproved) {
                $this->achievementVerificationModel->update($verificationData);
            }
            $this->achievementLogModel->create($logData);
            // End of Transactions
            $this->baseModel->getDbConnection()->commit();

            return ResponseHelper::success(
                $response,
                [],
                $approvalAction == 'reject' ? 'Successfully rejected achievement.' : 'Successfully approved achievement.'
            );
        } catch (\Exception $e) {
            $this->baseModel->getDbConnection()->rollBack();
            return ResponseHelper::error($response, $e->getMessage(), 500);
        }
    }
}
