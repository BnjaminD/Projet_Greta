<?php

class Comment {
    private $comment_id;
    private $user_id;
    private $restaurant_id;
    private $content;
    private $rating;
    private $is_moderated;
    private $created_at;
    private $moderated_at;
    private $moderated_by;
    private $username;
    private $restaurant_name;

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getCommentId() { return $this->comment_id; }
    public function getUserId() { return $this->user_id; }
    public function getRestaurantId() { return $this->restaurant_id; }
    public function getContent() { return $this->content; }
    public function getRating() { return $this->rating; }
    public function getIsModerated() { return $this->is_moderated; }
    public function getCreatedAt() { return $this->created_at; }
    public function getModeratedAt() { return $this->moderated_at; }
    public function getModeratedBy() { return $this->moderated_by; }
    public function getUsername() { return $this->username; }
    public function getRestaurantName() { return $this->restaurant_name; }

    public function setCommentId($value) { $this->comment_id = $value; }
    public function setUserId($value) { $this->user_id = $value; }
    public function setRestaurantId($value) { $this->restaurant_id = $value; }
    public function setContent($value) { $this->content = $value; }
    public function setRating($value) { $this->rating = $value; }
    public function setIsModerated($value) { $this->is_moderated = $value; }
    public function setCreatedAt($value) { $this->created_at = $value; }
    public function setModeratedAt($value) { $this->moderated_at = $value; }
    public function setModeratedBy($value) { $this->moderated_by = $value; }
    public function setUsername($value) { $this->username = $value; }
    public function setRestaurantName($value) { $this->restaurant_name = $value; }
}