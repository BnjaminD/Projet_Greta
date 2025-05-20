<?php

class TicketView {
    public function render($tickets, $isAdmin = false, $currentUserId = null) {
        $this->renderHeader();
        $this->renderContent($tickets, $isAdmin, $currentUserId);
        $this->renderFooter();
    }

    private function renderHeader() {
        // Correction du chemin pour inclure le header
        include dirname(__DIR__) . '/includes/header.php';
        
        // Add the ticket-specific CSS
        echo '<link rel="stylesheet" href="/app/assets/css/tickets.css">';
        
        echo '<div class="tickets-container">';
        echo '<div class="tickets-header">';
        $this->renderFilters();
        echo '</div>';
    }

    private function renderFilters() {
        ?>
        <h1><i class="fas fa-ticket-alt"></i> Gestion des Tickets</h1>
        <div class="tickets-filters">
            <button class="filter-btn active" data-filter="all">Tous</button>
            <button class="filter-btn" data-filter="pending">En attente</button>
            <button class="filter-btn" data-filter="in-progress">En cours</button>
            <button class="filter-btn" data-filter="completed">Terminés</button>
        </div>
        <?php
    }

    private function renderContent($tickets, $isAdmin, $currentUserId) {
        if (empty($tickets)) {
            echo '<div class="no-tickets">Aucun ticket disponible.</div>';
            return;
        }

        echo '<div class="tickets-grid">';
        foreach ($tickets as $ticket) {
            $comment = new CommentViewModel($ticket);
            $this->renderTicketCard($comment, $isAdmin, $currentUserId);
        }
        echo '</div>';
    }

    private function renderTicketCard(CommentViewModel $comment, $isAdmin, $currentUserId) {
        $statusClass = $this->getStatusClass($comment->getStatus());
        $canModify = $isAdmin || ($currentUserId && $currentUserId == $comment->getUserId());
        $status = $this->formatStatus($comment->getStatus());
        ?>
        <div class="ticket-card <?= $statusClass ?>" data-status="<?= $comment->getStatus() ?>">
            <div class="ticket-header">
                <span class="ticket-id">#<?= $comment->getId() ?></span>
                <span class="ticket-status"><?= $status ?></span>
            </div>
            <div class="ticket-body">
                <h3 class="ticket-restaurant"><?= htmlspecialchars($comment->getRestaurantName()) ?></h3>
                <p class="ticket-user">Par: <?= htmlspecialchars($comment->getUsername()) ?></p>
                <p class="ticket-date">Créé le: <?= date('d/m/Y H:i', strtotime($comment->getCreatedAt())) ?></p>
                <div class="ticket-content">
                    <?= htmlspecialchars($comment->getContent()) ?>
                </div>
                <?php if ($comment->getRating()): ?>
                    <div class="ticket-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= $comment->getRating() ? 'active' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($canModify): ?>
                <div class="ticket-actions">
                    <?php if ($comment->getStatus() === 'pending'): ?>
                        <button class="btn-action validate" data-action="validate" data-id="<?= $comment->getId() ?>">
                            <i class="fas fa-check"></i> Valider
                        </button>
                        <button class="btn-action progress" data-action="progress" data-id="<?= $comment->getId() ?>">
                            <i class="fas fa-spinner"></i> En cours
                        </button>
                    <?php elseif ($comment->getStatus() === 'in-progress'): ?>
                        <button class="btn-action complete" data-action="complete" data-id="<?= $comment->getId() ?>">
                            <i class="fas fa-check-circle"></i> Terminer
                        </button>
                    <?php endif; ?>
                    <?php if ($isAdmin): ?>
                        <button class="btn-action delete" data-action="delete" data-id="<?= $comment->getId() ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    private function getStatusClass($status) {
        switch ($status) {
            case 'pending':
                return 'ticket-pending';
            case 'in-progress':
                return 'ticket-in-progress';
            case 'completed':
                return 'ticket-completed';
            default:
                return '';
        }
    }

    private function formatStatus($status) {
        switch ($status) {
            case 'pending': return 'En attente';
            case 'in-progress': return 'En cours';
            case 'completed': return 'Terminé';
            default: return ucfirst(str_replace('-', ' ', $status));
        }
    }

    private function renderFooter() {
        ?>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtrage des tickets
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Activer le bouton courant
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les tickets
                    const tickets = document.querySelectorAll('.ticket-card');
                    tickets.forEach(ticket => {
                        if (filter === 'all') {
                            ticket.style.display = 'block';
                        } else {
                            const status = ticket.getAttribute('data-status');
                            ticket.style.display = (status === filter) ? 'block' : 'none';
                        }
                    });
                });
            });
            
            // Actions sur les tickets
            const actionButtons = document.querySelectorAll('.btn-action');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const commentId = this.getAttribute('data-id');
                    
                    if (confirm('Êtes-vous sûr de vouloir ' + getActionLabel(action) + ' ce ticket?')) {
                        // Créer un formulaire pour soumettre l'action
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.location.pathname;
                        
                        const inputAction = document.createElement('input');
                        inputAction.type = 'hidden';
                        inputAction.name = 'action';
                        inputAction.value = action;
                        
                        const inputId = document.createElement('input');
                        inputId.type = 'hidden';
                        inputId.name = 'comment_id';
                        inputId.value = commentId;
                        
                        form.appendChild(inputAction);
                        form.appendChild(inputId);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
            
            function getActionLabel(action) {
                switch(action) {
                    case 'validate': return 'valider';
                    case 'progress': return 'mettre en cours';
                    case 'complete': return 'terminer';
                    case 'delete': return 'supprimer';
                    default: return 'exécuter cette action sur';
                }
            }
        });
        </script>
        <?php
        include dirname(__DIR__) . '/includes/footer.php';
        ?>
        </body>
        </html>
        <?php
    }
}

// Class to handle comment data - renamed to avoid conflicts
class CommentViewModel {
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function getId() {
        return $this->data['comment_id'];
    }
    
    public function getUserId() {
        return $this->data['user_id'];
    }
    
    public function getUsername() {
        return $this->data['username'] ?? 'Utilisateur inconnu';
    }
    
    public function getRestaurantName() {
        return $this->data['restaurant_name'] ?? 'Restaurant inconnu';
    }
    
    public function getContent() {
        return $this->data['content'];
    }
    
    public function getRating() {
        return $this->data['rating'];
    }
    
    public function getStatus() {
        if (!empty($this->data['is_completed']) && (int)$this->data['is_completed'] === 1) {
            return 'completed';
        } elseif (!empty($this->data['is_in_progress']) && (int)$this->data['is_in_progress'] === 1) {
            return 'in-progress';
        } else {
            return 'pending';
        }
    }
    
    public function getIsModerated() {
        return !empty($this->data['is_moderated']) && (int)$this->data['is_moderated'] === 1;
    }
    
    public function getCreatedAt() {
        return $this->data['created_at'];
    }
}