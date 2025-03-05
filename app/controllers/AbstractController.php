
<?php
abstract class AbstractController {
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    abstract protected function render(string $view, array $data = []): void;
}