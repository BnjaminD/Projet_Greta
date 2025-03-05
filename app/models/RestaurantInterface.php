<?php
declare(strict_types=1);

namespace App\Models;

interface RestaurantInterface {
    public function getId(): int;
    public function getName(): string;
    public function getDescription(): string;
    public function getAddress(): string;
    public function getImagePath(): string;
    public function hydrate(array $data): void;
    public function getPhoneNumber(): string;
    public function getOpeningHours(): string;
    public function getCapacity(): int;
    public function getRating(): float;
    public function getCuisineType(): string;
}
?>