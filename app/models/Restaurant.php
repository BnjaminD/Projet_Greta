<?php
declare(strict_types=1);

namespace App\Models;
use App\Models\RestaurantInterface;

class Restaurant implements RestaurantInterface {
    private int $id;
    private string $name;
    private string $description;
    private string $address;
    private string $phoneNumber;
    private string $openingHours;
    private int $capacity;
    private float $rating;
    private string $cuisineType;
    private string $imagePath;
    private $db;

    public function __construct(array $data) {
        $this->hydrate($data);
        $this->db = \App\Core\Database::getInstance();
    }

    public function hydrate(array $data): void {
        $this->id = (int)($data['restaurant_id'] ?? 0);
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->phoneNumber = $data['phone_number'] ?? '';
        $this->openingHours = $data['opening_hours'] ?? '';
        $this->capacity = (int)($data['capacity'] ?? 0);
        $this->rating = (float)($data['rating'] ?? 0.0);
        $this->cuisineType = $data['cuisine_type'] ?? '';
        $this->imagePath = $data['image_url'] ?? './images/restaurant/default_restaurant.png';
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getAddress(): string { return $this->address; }
    public function getPhoneNumber(): string { return $this->phoneNumber; }
    public function getOpeningHours(): string { return $this->openingHours; }
    public function getCapacity(): int { return $this->capacity; }
    public function getRating(): float { return $this->rating; }
    public function getCuisineType(): string { return $this->cuisineType; }
    public function getImagePath(): string { return $this->imagePath; }

    public function getTopRated($limit = 3): array {
        $stmt = $this->db->prepare("SELECT * FROM restaurant ORDER BY rating DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getCuisineTypes(): array {
        $stmt = $this->db->prepare("SELECT DISTINCT cuisine_type FROM restaurant");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
?>