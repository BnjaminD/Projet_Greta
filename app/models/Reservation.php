<?php
class Reservation {
    private $pdo;
    private $reservation_id;
    private $restaurant_id;
    private $user_id;
    private $reservation_time;
    private $number_of_guests;
    private $status;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($restaurant_id, $user_id, $reservation_time, $number_of_guests) {
        $query = "INSERT INTO reservation (restaurant_id, user_id, reservation_time, number_of_guests, status) 
                 VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$restaurant_id, $user_id, $reservation_time, $number_of_guests]);
    }

    public function cancel($reservation_id, $user_id) {
        $stmt = $this->pdo->prepare("UPDATE reservation SET status = 'cancelled' WHERE reservation_id = ? AND user_id = ?");
        return $stmt->execute([$reservation_id, $user_id]);
    }

    public function getByUserAndId($reservation_id, $user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reservation WHERE reservation_id = ? AND user_id = ?");
        $stmt->execute([$reservation_id, $user_id]);
        return $stmt->fetch();
    }
}
?>