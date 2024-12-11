<?php

namespace App\Controllers;

use App\Models\Department;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Helpers\ResponseHelper;
use App\Models\Achievement;

class DashboardController extends Controller {
    private $achievementModel;

    public function __construct() {
        $this->achievementModel = new Achievement();
    }

    public function index(Request $request, Response $response) {
        $queryParams = $request->getQueryParams();
        $year = $queryParams['year'] ?? date('Y');
        $role = $_SESSION['user']['role'];

        $totalAchievementPerMonthInOneYear = $this->achievementModel->getTotalPerMonthInOneYear($year);
        $totalAchievementBasedOnScope = $this->achievementModel->getTotalBasedOnScope();
        $totalBasedOnVerificationStatus = $this->achievementModel->getTotalBasedOnVerificationStatus();
        $top10ByStudent = $this->achievementModel->getTop10ByStudent();

        $dashboardData = null;
        if ($role == 'Student') {
            $dashboardData = array(
                array(
                    'type' => 'Chart Top 10 by Student',
                    'data' => empty($top10ByStudent) ? null : array_values($top10ByStudent),
                ),
                array(
                    'type' => 'Total Achievement Based on Verification Status',
                    'data' => empty($totalBasedOnVerificationStatus) ? null : array_values($totalBasedOnVerificationStatus),
                )
            );
        } else if ($role == 'Admin' || $role == 'Lecturer') {
            $dashboardData = array(
                array(
                    'type' => 'Chart Per Month in One Year',
                    'data' => empty($totalAchievementPerMonthInOneYear) ? null : array_values($totalAchievementPerMonthInOneYear),
                ),
                array(
                    'type' => 'Chart Based on Scope',
                    'data' => empty($totalAchievementBasedOnScope) ? null : array_values($totalAchievementBasedOnScope),
                ),
                array(
                    'type' => 'Chart Top 10 by Student',
                    'data' => empty($top10ByStudent) ? null : array_values($top10ByStudent),
                ),
                array(
                    'type' => 'Total Achievement Based on Verification Status',
                    'data' => empty($totalBasedOnVerificationStatus) ? null : array_values($totalBasedOnVerificationStatus),
                )
            );
        }

        return ResponseHelper::success($response, $dashboardData, 'Successfully get dashboard data.');
    }
}