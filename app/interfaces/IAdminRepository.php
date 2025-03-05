<?php namespace App\Interfaces;

interface IAdminRepository {
    public function getSystemLogs(int $limit = 100): array;
    public function getAdminActions(int $limit = 50): array;
    public function getUserStatistics(): array;
    public function getModeratedContent(): array;
}