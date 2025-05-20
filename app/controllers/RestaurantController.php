<?php
require_once __DIR__ . '/AbstractController.php';
require_once __DIR__ . '/../models/Restaurant.php';
use App\Models\Restaurant;

class RestaurantController extends AbstractController {
    private const RESTAURANT_IMAGES = [
        'The Fancy Fork' => '../images/restaurant/fancy_fork.png',
        'Pizza Paradise' => '../images/restaurant/pizza_paradise.png',
        'Sushi World' => '../images/restaurant/sushi_world.png',
        'Le Bistrot Parisien' => '../images/restaurant/bistrot_parisien.png',
        'Taj Mahal' => '../images/restaurant/taj_mahal.png',
        'El Tapas' => '../images/restaurant/el_tapas.png',
        'default' => '../images/restaurant/default_restaurant.png'
    ];

    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void {
        $restaurants = $this->findAll();
        $this->render('../restaurant/index', [
            'restaurants' => $restaurants,
            'images' => self::RESTAURANT_IMAGES
        ]);
    }

    public function show(int $id): void {
        $restaurant = $this->findById($id);
        if (!$restaurant) {
            // Gérer l'erreur
            header('Location: ../restaurants');
            return;
        }
        
        $this->render('restaurant/show', [
            'restaurant' => $restaurant,
            'images' => self::RESTAURANT_IMAGES
        ]);
    }

    public function findById(int $id): ?Restaurant
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM restaurant WHERE restaurant_id = :id");
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $data ? new Restaurant($data) : null;
        } catch (PDOException $e) {
            error_log("Database Error in findById: " . $e->getMessage());
            return null;
        }
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM restaurant ORDER BY name ASC");
            return array_map(
                fn($data) => new Restaurant($data), 
                $stmt->fetchAll(PDO::FETCH_ASSOC)
            );
        } catch (PDOException $e) {
            error_log("Database Error in findAll: " . $e->getMessage());
            return [];
        }
    }

    protected function render(string $view, array $data = []): void {
        extract($data);
        require "views/pages/$view.php";
    }
}
?>