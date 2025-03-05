<?php
require_once '../models/Reservation.php';

class ReservationController {
    private $pdo;
    private $reservation;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->reservation = new Reservation($pdo);
    }

    public function createReservation($restaurant_id, $user_id, $date, $time, $guests) {
        $reservation_time = date('Y-m-d H:i:s', strtotime("$date $time"));
        return $this->reservation->create($restaurant_id, $user_id, $reservation_time, $guests);
    }

    public function cancelReservation($reservation_id, $user_id) {
        if (!$this->reservation->getByUserAndId($reservation_id, $user_id)) {
            return ['success' => false, 'message' => 'Réservation non trouvée ou non autorisée'];
        }
        
        $success = $this->reservation->cancel($reservation_id, $user_id);
        return [
            'success' => $success,
            'message' => $success ? 'La réservation a été annulée avec succès' : 'Erreur lors de l\'annulation'
        ];
    }
}
?>
