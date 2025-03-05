<?php

namespace App\Controllers;


use RuntimeException;
use App\Services\AuthenticationService;
use App\Interfaces\IAdminRepository;
use App\Exceptions\DatabaseException;
use App\Exceptions\UnauthorizedException;

class AdminController {
    private IAdminRepository $adminRepository;
    private AuthenticationService $authService;

    public function __construct(
        IAdminRepository $adminRepository, 
        AuthenticationService $authService
    ) {
        $this->adminRepository = $adminRepository;
        $this->authService = $authService;
    }

    public function getDashboardData(): array {
        try {
            if (!$this->authService->hasPermission('admin.dashboard.view')) {
                throw new UnauthorizedException('Insufficient permissions to view dashboard');
            }

            return [
                'system_logs' => $this->adminRepository->getSystemLogs(),
                'admin_actions' => $this->adminRepository->getAdminActions(),
                'user_stats' => $this->adminRepository->getUserStatistics(),
                'moderated_content' => $this->adminRepository->getModeratedContent()
            ];
        } catch (DatabaseException $e) {
            // Log l'erreur
            error_log($e->getMessage());
            throw new RuntimeException('Unable to fetch dashboard data');
        }
    }
}