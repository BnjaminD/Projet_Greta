<?php
require_once dirname(__DIR__) . '/models/Reservation.php';

use app\models\Reservation;

class ReservationController {
    private $pdo;
    private $reservation;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->reservation = new Reservation($pdo);
    }

    /**
     * Crée une nouvelle réservation
     * 
     * @param int $restaurantId ID du restaurant
     * @param int $userId ID de l'utilisateur
     * @param string $date Date de la réservation (Y-m-d)
     * @param string $time Heure de la réservation (H:i)
     * @param int $guests Nombre de personnes
     * @param string|null $specialRequests Demandes spéciales (optionnel)
     * @return bool Succès ou échec de l'opération
     */
    public function createReservation($restaurantId, $userId, $date, $time, $guests, $specialRequests = null) {
        return $this->reservation->create($restaurantId, $userId, $date, $time, $guests, $specialRequests);
    }
    
    // ...existing code...
}
