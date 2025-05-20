<?php
namespace app\models;

use PDO;
use Exception;

class Reservation {
    private $pdo;
    private $table = 'reservation';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
    public function create($restaurantId, $userId, $date, $time, $guests, $specialRequests = null) {
        try {
            // Vérifier d'abord la disponibilité
            if (!$this->checkAvailability($restaurantId, $date, $time, $guests)) {
                return false;
            }

            // Formater la date et l'heure pour la base de données
            $reservationTime = $date . ' ' . $time . ':00';
            
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} 
                (restaurant_id, user_id, reservation_time, number_of_guests, special_requests, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
            
            return $stmt->execute([
                $restaurantId,
                $userId,
                $reservationTime,
                $guests,
                $specialRequests
            ]);
        } catch (Exception $e) {
            error_log("Erreur lors de la création de la réservation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie la disponibilité d'une table à une date et heure donnée
     * 
     * @param int $restaurantId ID du restaurant
     * @param string $date Date (Y-m-d)
     * @param string $time Heure (H:i)
     * @param int $guests Nombre de personnes
     * @return bool Disponibilité
     */
    public function checkAvailability($restaurantId, $date, $time, $guests) {
        try {
            // Récupérer la capacité maximale du restaurant
            $stmt = $this->pdo->prepare("SELECT capacity FROM restaurant WHERE restaurant_id = ?");
            $stmt->execute([$restaurantId]);
            $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$restaurant) {
                return false;
            }

            $capacity = $restaurant['capacity'];
            
            // Vérifier le nombre de places déjà réservées à cette heure
            $reservationTime = $date . ' ' . $time . ':00';
            $timeMargin = 90; // 90 minutes entre chaque service
            
            $stmt = $this->pdo->prepare(
                "SELECT SUM(number_of_guests) as total_guests 
                FROM {$this->table} 
                WHERE restaurant_id = ? 
                AND reservation_time BETWEEN DATE_SUB(?, INTERVAL {$timeMargin} MINUTE) 
                AND DATE_ADD(?, INTERVAL {$timeMargin} MINUTE)
                AND status IN ('confirmed', 'pending')"
            );
            
            $stmt->execute([$restaurantId, $reservationTime, $reservationTime]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $alreadyBooked = $result['total_guests'] ?? 0;
            
            // Vérifier si l'ajout de nouveaux invités ne dépasse pas la capacité
            return ($alreadyBooked + $guests) <= $capacity;
        } catch (Exception $e) {
            error_log("Erreur lors de la vérification de disponibilité: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère une réservation par son ID
     * 
     * @param int $id ID de la réservation
     * @return array|null Données de la réservation ou null si non trouvée
     */
    public function getById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE reservation_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération de la réservation: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère toutes les réservations d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array Liste des réservations
     */
    public function getAllByUser($userId) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT r.*, rt.name as restaurant_name, rt.address 
                FROM {$this->table} r
                JOIN restaurant rt ON r.restaurant_id = rt.restaurant_id
                WHERE r.user_id = ?
                ORDER BY r.reservation_time DESC"
            );
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des réservations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère toutes les réservations d'un restaurant
     * 
     * @param int $restaurantId ID du restaurant
     * @param string|null $date Date optionnelle pour filtrer
     * @return array Liste des réservations
     */
    public function getAllByRestaurant($restaurantId, $date = null) {
        try {
            $sql = "SELECT r.*, u.username, u.email 
                    FROM {$this->table} r
                    JOIN user u ON r.user_id = u.user_id
                    WHERE r.restaurant_id = ?";
            $params = [$restaurantId];
            
            if ($date) {
                $sql .= " AND DATE(r.reservation_time) = ?";
                $params[] = $date;
            }
            
            $sql .= " ORDER BY r.reservation_time ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des réservations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour le statut d'une réservation
     * 
     * @param int $id ID de la réservation
     * @param string $status Nouveau statut (confirmed, cancelled, completed)
     * @return bool Succès ou échec
     */
    public function updateStatus($id, $status) {
        try {
            $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
            
            if (!in_array($status, $allowedStatuses)) {
                return false;
            }
            
            $stmt = $this->pdo->prepare(
                "UPDATE {$this->table} SET status = ?, updated_at = NOW() WHERE reservation_id = ?"
            );
            return $stmt->execute([$status, $id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Modifie une réservation existante
     * 
     * @param int $id ID de la réservation
     * @param array $data Données à mettre à jour
     * @return bool Succès ou échec
     */
    public function update($id, $data) {
        try {
            // Vérifier que la réservation existe
            $reservation = $this->getById($id);
            if (!$reservation) {
                return false;
            }
            
            // Liste des champs autorisés à être mis à jour
            $allowedFields = [
                'reservation_time', 'number_of_guests', 'special_requests', 'status'
            ];
            
            // Filtrer les données pour ne garder que les champs autorisés
            $updateData = array_intersect_key($data, array_flip($allowedFields));
            
            if (empty($updateData)) {
                return false;
            }
            
            // Construire la requête de mise à jour
            $setClause = [];
            $params = [];
            
            foreach ($updateData as $field => $value) {
                $setClause[] = "{$field} = ?";
                $params[] = $value;
            }
            
            $setClause[] = "updated_at = NOW()";
            
            $params[] = $id; // Pour la condition WHERE
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause) . " WHERE reservation_id = ?";
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour de la réservation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une réservation
     * 
     * @param int $id ID de la réservation
     * @return bool Succès ou échec
     */
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE reservation_id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de la réservation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtient les statistiques de réservation pour un restaurant
     * 
     * @param int $restaurantId ID du restaurant
     * @param string $startDate Date de début (format Y-m-d)
     * @param string $endDate Date de fin (format Y-m-d)
     * @return array Statistiques de réservation
     */
    public function getRestaurantStats($restaurantId, $startDate, $endDate) {
        try {
            $sql = "SELECT 
                    COUNT(*) as total_reservations,
                    SUM(number_of_guests) as total_guests,
                    COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed,
                    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed
                    FROM {$this->table}
                    WHERE restaurant_id = ?
                    AND DATE(reservation_time) BETWEEN ? AND ?";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$restaurantId, $startDate, $endDate]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            return [
                'total_reservations' => 0,
                'total_guests' => 0,
                'confirmed' => 0,
                'cancelled' => 0,
                'completed' => 0
            ];
        }
    }
}