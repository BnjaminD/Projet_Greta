
<?php

interface IAdminRepository {
    public function getSystemLogs(): array;
    public function getAdminActions(): array;
    public function getUserStatistics(): array;
    public function getModeratedContent(): array;
}